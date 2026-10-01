<?php

require_once __DIR__ . '/../../../data/dashboard/mahasiswa/Sertifikat.php';

class SertifikatController
{
    /*
    |--------------------------------------------------------------------------
    | Versi Data
    |--------------------------------------------------------------------------
    |
    | Digunakan agar perubahan struktur data awal,
    | seperti penambahan field gambar, dapat dimuat ulang
    | ke session development.
    |
    */

    private const DATA_VERSION = 'sertifikat_v2';


    /*
    |--------------------------------------------------------------------------
    | Session
    |--------------------------------------------------------------------------
    */

    private function startSession()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Ambil seluruh data sertifikat
    |--------------------------------------------------------------------------
    |
    | Data awal berasal dari Sertifikat.php.
    | Setelah ada perubahan CRUD, session menjadi sumber data sementara.
    |
    */

    private function getAllSertifikat()
    {
        global $sertifikat;

        $this->startSession();

        if (
            !isset($_SESSION['sertifikat_data']) ||
            ($_SESSION['sertifikat_data_version'] ?? '') !== self::DATA_VERSION
        ) {
            $_SESSION['sertifikat_data'] = $sertifikat;
            $_SESSION['sertifikat_data_version'] = self::DATA_VERSION;
        }

        return $_SESSION['sertifikat_data'];
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan data sertifikat
    |--------------------------------------------------------------------------
    */

    private function saveSertifikat($data)
    {
        $this->startSession();

        $_SESSION['sertifikat_data'] = array_values($data);

        $_SESSION['sertifikat_data_version'] =
            self::DATA_VERSION;
    }


    /*
    |--------------------------------------------------------------------------
    | Cari sertifikat berdasarkan slug
    |--------------------------------------------------------------------------
    */

    private function findBySlug($slug)
    {
        $sertifikat = $this->getAllSertifikat();

        foreach ($sertifikat as $item) {

            if (
                ($item['slug'] ?? '') === $slug
            ) {
                return $item;
            }
        }

        return null;
    }


    /*
    |--------------------------------------------------------------------------
    | Cek apakah sertifikat boleh diedit/dihapus
    |--------------------------------------------------------------------------
    |
    | Hanya status belum_terverifikasi yang dapat dimodifikasi.
    |
    */

    private function canModify($sertifikat)
    {
        return (
            ($sertifikat['verifikasi']['status'] ?? '') ===
            'belum_terverifikasi'
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Generate slug unik
    |--------------------------------------------------------------------------
    */

    private function generateSlug(
        $nama,
        $excludeSlug = null
    ) {
        $baseSlug = slugify($nama);

        if ($baseSlug === '') {
            $baseSlug = 'sertifikat';
        }

        $sertifikat = $this->getAllSertifikat();

        $slug = $baseSlug;

        $counter = 2;

        while (true) {

            $found = false;

            foreach ($sertifikat as $item) {

                $itemSlug =
                    $item['slug'] ?? '';

                if (
                    $itemSlug === $slug &&
                    $itemSlug !== $excludeSlug
                ) {
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                return $slug;
            }

            $slug =
                $baseSlug . '-' . $counter;

            $counter++;
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Generate ID baru
    |--------------------------------------------------------------------------
    */

    private function generateId()
    {
        $sertifikat =
            $this->getAllSertifikat();

        if (empty($sertifikat)) {
            return 1;
        }

        $ids =
            array_column(
                $sertifikat,
                'id'
            );

        $ids =
            array_map(
                'intval',
                $ids
            );

        return max($ids) + 1;
    }


    /*
    |--------------------------------------------------------------------------
    | Upload gambar sertifikat
    |--------------------------------------------------------------------------
    */

    private function storeUploadedImage(
        $slug,
        $id,
        $required = false
    ) {
        if (
            !isset($_FILES['gambar']) ||
            ($_FILES['gambar']['error'] ?? UPLOAD_ERR_NO_FILE) ===
            UPLOAD_ERR_NO_FILE
        ) {

            if ($required) {

                return [
                    'filename' => null,
                    'error' =>
                    'Foto sertifikat wajib diunggah.',
                ];
            }

            return [
                'filename' => null,
                'error' => null,
            ];
        }


        $file =
            $_FILES['gambar'];


        if (
            ($file['error'] ?? UPLOAD_ERR_NO_FILE) !==
            UPLOAD_ERR_OK
        ) {

            return [
                'filename' => null,
                'error' =>
                'Foto sertifikat gagal diunggah.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Maksimal 5 MB
        |--------------------------------------------------------------------------
        */

        if (
            ($file['size'] ?? 0) >
            5 * 1024 * 1024
        ) {

            return [
                'filename' => null,
                'error' =>
                'Ukuran foto maksimal 5 MB.',
            ];
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi MIME file sebenarnya
        |--------------------------------------------------------------------------
        */

        $finfo =
            new finfo(FILEINFO_MIME_TYPE);

        $mime =
            $finfo->file(
                $file['tmp_name']
            );


        $allowedMime = [

            'image/jpeg' => 'jpg',

            'image/png' => 'png',

            'image/webp' => 'webp',

        ];


        if (
            !isset($allowedMime[$mime])
        ) {

            return [
                'filename' => null,
                'error' =>
                'Format foto harus JPG, PNG, atau WEBP.',
            ];
        }


        $extension =
            $allowedMime[$mime];


        /*
        |--------------------------------------------------------------------------
        | Folder upload
        |--------------------------------------------------------------------------
        */

        $uploadDirectory =
            __DIR__ .
            '/../../../assets/images/sertifikat';


        if (
            !is_dir($uploadDirectory)
        ) {

            if (
                !mkdir(
                    $uploadDirectory,
                    0755,
                    true
                )
            ) {

                return [
                    'filename' => null,
                    'error' =>
                    'Folder penyimpanan foto tidak dapat dibuat.',
                ];
            }
        }


        /*
        |--------------------------------------------------------------------------
        | Nama file unik
        |--------------------------------------------------------------------------
        */

        $filename =
            'sertifikat-' .
            (int) $id .
            '-' .
            $slug .
            '-' .
            bin2hex(
                random_bytes(5)
            ) .
            '.' .
            $extension;


        $destination =
            $uploadDirectory .
            '/' .
            $filename;


        if (
            !move_uploaded_file(
                $file['tmp_name'],
                $destination
            )
        ) {

            return [
                'filename' => null,
                'error' =>
                'Foto sertifikat gagal disimpan.',
            ];
        }


        return [
            'filename' => $filename,
            'error' => null,
        ];
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus file gambar lama
    |--------------------------------------------------------------------------
    */

    private function deleteImage($filename)
    {
        $filename =
            basename(
                (string) $filename
            );

        if (
            $filename === ''
        ) {
            return;
        }


        $directory =
            __DIR__ .
            '/../../../assets/images/sertifikat';


        $path =
            $directory .
            '/' .
            $filename;


        if (
            is_file($path)
        ) {
            @unlink($path);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index($params = [])
    {
        $sertifikat =
            $this->getAllSertifikat();


        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $q =
            trim(
                $_GET['q'] ?? ''
            );

        $tahun =
            trim(
                $_GET['tahun'] ?? ''
            );

        $status =
            trim(
                $_GET['status'] ?? ''
            );


        /*
        |--------------------------------------------------------------------------
        | Data pilihan tahun
        |--------------------------------------------------------------------------
        */

        $tahunList = [];


        foreach (
            $sertifikat
            as $item
        ) {

            $tanggalTerbit =
                trim(
                    $item['tanggal_terbit'] ?? ''
                );


            if (
                $tanggalTerbit === ''
            ) {
                continue;
            }


            $timestamp =
                strtotime(
                    $tanggalTerbit
                );


            if (
                $timestamp === false
            ) {
                continue;
            }


            $tahunList[] =
                date(
                    'Y',
                    $timestamp
                );
        }


        $tahunList =
            array_values(
                array_unique(
                    $tahunList
                )
            );


        rsort($tahunList);


        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        $hasilFilter =
            array_filter(
                $sertifikat,
                function ($item) use (
                    $q,
                    $tahun,
                    $status
                ) {

                    if (
                        $q !== ''
                    ) {

                        $keyword =
                            strtolower($q);


                        $nama =
                            strtolower(
                                $item['nama'] ?? ''
                            );


                        $penerbit =
                            strtolower(
                                $item['penerbit'] ?? ''
                            );


                        $nomorSertifikat =
                            strtolower(
                                $item['nomor_sertifikat'] ?? ''
                            );


                        $deskripsi =
                            strtolower(
                                $item['deskripsi'] ?? ''
                            );


                        if (
                            strpos(
                                $nama,
                                $keyword
                            ) === false &&

                            strpos(
                                $penerbit,
                                $keyword
                            ) === false &&

                            strpos(
                                $nomorSertifikat,
                                $keyword
                            ) === false &&

                            strpos(
                                $deskripsi,
                                $keyword
                            ) === false
                        ) {

                            return false;
                        }
                    }


                    if (
                        $tahun !== ''
                    ) {

                        $tanggalTerbit =
                            trim(
                                $item['tanggal_terbit'] ?? ''
                            );


                        $timestamp =
                            strtotime(
                                $tanggalTerbit
                            );


                        if (
                            $timestamp === false
                        ) {
                            return false;
                        }


                        $itemTahun =
                            date(
                                'Y',
                                $timestamp
                            );


                        if (
                            $itemTahun !==
                            $tahun
                        ) {
                            return false;
                        }
                    }


                    if (
                        $status !== '' &&
                        ($item['verifikasi']['status'] ?? '') !==
                        $status
                    ) {

                        return false;
                    }


                    return true;
                }
            );


        $hasilFilter =
            array_values(
                $hasilFilter
            );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 6;


        $totalData =
            count(
                $hasilFilter
            );


        $totalPage =
            max(
                1,
                (int) ceil(
                    $totalData /
                        $perPage
                )
            );


        $page =
            max(
                1,
                (int) (
                    $_GET['page'] ?? 1
                )
            );


        if (
            $page > $totalPage
        ) {
            $page = $totalPage;
        }


        $offset =
            (
                $page - 1
            ) *
            $perPage;


        $tampil =
            array_slice(
                $hasilFilter,
                $offset,
                $perPage
            );


        $pagination = [

            'current_page' =>
            $page,

            'per_page' =>
            $perPage,

            'total_data' =>
            $totalData,

            'total_page' =>
            $totalPage,

        ];


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/sertifikat/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function detail($params = [])
    {
        $slug =
            $params['slug'] ?? '';


        $sertifikat =
            $this->findBySlug(
                $slug
            );


        if (
            !$sertifikat
        ) {

            http_response_code(404);

            require __DIR__ .
                '/../../../pages/errors/404.php';

            exit;
        }


        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/sertifikat/detail.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Tambah
    |--------------------------------------------------------------------------
    */

    public function tambah($params = [])
    {
        $errors = [];


        $old = [

            'nama' =>
            '',

            'penerbit' =>
            '',

            'tanggal_terbit' =>
            '',

            'nomor_sertifikat' =>
            '',

            'deskripsi' =>
            '',

            'gambar' =>
            '',

            'tautan' =>
            '',

        ];


        /*
        |--------------------------------------------------------------------------
        | Submit
        |--------------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $old = [

                'nama' =>
                trim(
                    $_POST['nama'] ?? ''
                ),

                'penerbit' =>
                trim(
                    $_POST['penerbit'] ?? ''
                ),

                'tanggal_terbit' =>
                trim(
                    $_POST['tanggal_terbit'] ?? ''
                ),

                'nomor_sertifikat' =>
                trim(
                    $_POST['nomor_sertifikat'] ?? ''
                ),

                'deskripsi' =>
                trim(
                    $_POST['deskripsi'] ?? ''
                ),

                'gambar' =>
                '',

                'tautan' =>
                trim(
                    $_POST['tautan'] ?? ''
                ),

            ];


            /*
            |--------------------------------------------------------------------------
            | Validasi Nama
            |--------------------------------------------------------------------------
            */

            if (
                $old['nama'] === ''
            ) {

                $errors['nama'] =
                    'Nama sertifikat wajib diisi.';
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi Penerbit
            |--------------------------------------------------------------------------
            */

            if (
                $old['penerbit'] === ''
            ) {

                $errors['penerbit'] =
                    'Penerbit wajib diisi.';
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi Tanggal
            |--------------------------------------------------------------------------
            */

            if (
                $old['tanggal_terbit'] === ''
            ) {

                $errors['tanggal_terbit'] =
                    'Tanggal terbit wajib diisi.';
            } else {

                $tanggalValid =
                    DateTime::createFromFormat(
                        'Y-m-d',
                        $old['tanggal_terbit']
                    );


                if (
                    !$tanggalValid ||
                    $tanggalValid->format('Y-m-d') !==
                    $old['tanggal_terbit']
                ) {

                    $errors['tanggal_terbit'] =
                        'Format tanggal terbit tidak valid.';
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi Nomor
            |--------------------------------------------------------------------------
            */

            if (
                $old['nomor_sertifikat'] === ''
            ) {

                $errors['nomor_sertifikat'] =
                    'Nomor sertifikat wajib diisi.';
            }


            /*
            |--------------------------------------------------------------------------
            | Validasi Tautan
            |--------------------------------------------------------------------------
            */

            if (
                $old['tautan'] !== '' &&
                !filter_var(
                    $old['tautan'],
                    FILTER_VALIDATE_URL
                )
            ) {

                $errors['tautan'] =
                    'Tautan sertifikat tidak valid.';
            }


            /*
            |--------------------------------------------------------------------------
            | Upload Foto
            |--------------------------------------------------------------------------
            */

            if (
                empty($errors)
            ) {

                $newId =
                    $this->generateId();


                $slug =
                    $this->generateSlug(
                        $old['nama']
                    );


                $uploadResult =
                    $this->storeUploadedImage(
                        $slug,
                        $newId,
                        true
                    );


                if (
                    $uploadResult['error']
                ) {

                    $errors['gambar'] =
                        $uploadResult['error'];
                } else {

                    $old['gambar'] =
                        $uploadResult['filename'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Simpan
            |--------------------------------------------------------------------------
            */

            if (
                empty($errors)
            ) {

                $sertifikat =
                    $this->getAllSertifikat();


                $newItem = [

                    'id' =>
                    $newId,

                    'slug' =>
                    $slug,

                    'nama' =>
                    $old['nama'],

                    'penerbit' =>
                    $old['penerbit'],

                    'tanggal_terbit' =>
                    $old['tanggal_terbit'],

                    'nomor_sertifikat' =>
                    $old['nomor_sertifikat'],

                    'deskripsi' =>
                    $old['deskripsi'],

                    'gambar' =>
                    $old['gambar'],

                    'tautan' => [

                        'sertifikat' =>
                        $old['tautan'],

                    ],

                    'verifikasi' => [

                        'status' =>
                        'belum_terverifikasi',

                        'label' =>
                        'Belum Terverifikasi',

                    ],

                ];


                $sertifikat[] =
                    $newItem;


                $this->saveSertifikat(
                    $sertifikat
                );


                header(
                    'Location: ' .
                        url(
                            '/dashboard/mahasiswa/sertifikat/detail/' .
                                $slug
                        )
                );

                exit;
            }
        }


        $mode = 'tambah';


        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/sertifikat/form.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($params = [])
    {
        $slug =
            $params['slug'] ?? '';


        $sertifikat =
            $this->findBySlug(
                $slug
            );


        /*
        |--------------------------------------------------------------------------
        | Tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (
            !$sertifikat
        ) {

            http_response_code(404);

            require __DIR__ .
                '/../../../pages/errors/404.php';

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | Status sudah terverifikasi
        |--------------------------------------------------------------------------
        */

        if (
            !$this->canModify(
                $sertifikat
            )
        ) {

            http_response_code(403);

            exit('Sertifikat yang sudah diverifikasi tidak dapat diedit.');
        }


        $errors = [];


        $old = [

            'nama' =>
            $sertifikat['nama'] ?? '',

            'penerbit' =>
            $sertifikat['penerbit'] ?? '',

            'tanggal_terbit' =>
            $sertifikat['tanggal_terbit'] ?? '',

            'nomor_sertifikat' =>
            $sertifikat['nomor_sertifikat'] ?? '',

            'deskripsi' =>
            $sertifikat['deskripsi'] ?? '',

            'gambar' =>
            $sertifikat['gambar'] ?? '',

            'tautan' =>
            $sertifikat['tautan']['sertifikat'] ?? '',

        ];


        /*
        |--------------------------------------------------------------------------
        | Submit
        |--------------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
        ) {

            $old = [

                'nama' =>
                trim(
                    $_POST['nama'] ?? ''
                ),

                'penerbit' =>
                trim(
                    $_POST['penerbit'] ?? ''
                ),

                'tanggal_terbit' =>
                trim(
                    $_POST['tanggal_terbit'] ?? ''
                ),

                'nomor_sertifikat' =>
                trim(
                    $_POST['nomor_sertifikat'] ?? ''
                ),

                'deskripsi' =>
                trim(
                    $_POST['deskripsi'] ?? ''
                ),

                'gambar' =>
                $sertifikat['gambar'] ?? '',

                'tautan' =>
                trim(
                    $_POST['tautan'] ?? ''
                ),

            ];


            /*
            |--------------------------------------------------------------------------
            | Validasi
            |--------------------------------------------------------------------------
            */

            if (
                $old['nama'] === ''
            ) {

                $errors['nama'] =
                    'Nama sertifikat wajib diisi.';
            }


            if (
                $old['penerbit'] === ''
            ) {

                $errors['penerbit'] =
                    'Penerbit wajib diisi.';
            }


            if (
                $old['tanggal_terbit'] === ''
            ) {

                $errors['tanggal_terbit'] =
                    'Tanggal terbit wajib diisi.';
            } else {

                $tanggalValid =
                    DateTime::createFromFormat(
                        'Y-m-d',
                        $old['tanggal_terbit']
                    );


                if (
                    !$tanggalValid ||
                    $tanggalValid->format('Y-m-d') !==
                    $old['tanggal_terbit']
                ) {

                    $errors['tanggal_terbit'] =
                        'Format tanggal terbit tidak valid.';
                }
            }


            if (
                $old['nomor_sertifikat'] === ''
            ) {

                $errors['nomor_sertifikat'] =
                    'Nomor sertifikat wajib diisi.';
            }


            if (
                $old['tautan'] !== '' &&
                !filter_var(
                    $old['tautan'],
                    FILTER_VALIDATE_URL
                )
            ) {

                $errors['tautan'] =
                    'Tautan sertifikat tidak valid.';
            }


            /*
            |--------------------------------------------------------------------------
            | Generate slug
            |--------------------------------------------------------------------------
            */

            $newSlug =
                $this->generateSlug(
                    $old['nama'],
                    $slug
                );


            /*
            |--------------------------------------------------------------------------
            | Upload gambar baru
            |--------------------------------------------------------------------------
            */

            $newImage =
                $sertifikat['gambar'] ?? '';


            if (
                empty($errors)
            ) {

                $uploadResult =
                    $this->storeUploadedImage(
                        $newSlug,
                        $sertifikat['id'],
                        false
                    );


                if (
                    $uploadResult['error']
                ) {

                    $errors['gambar'] =
                        $uploadResult['error'];
                } elseif (
                    $uploadResult['filename']
                ) {

                    $newImage =
                        $uploadResult['filename'];
                }
            }


            /*
            |--------------------------------------------------------------------------
            | Update data
            |--------------------------------------------------------------------------
            */

            if (
                empty($errors)
            ) {

                $allSertifikat =
                    $this->getAllSertifikat();


                foreach (
                    $allSertifikat
                    as $index => $item
                ) {

                    if (
                        ($item['slug'] ?? '') !==
                        $slug
                    ) {
                        continue;
                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Pastikan status masih aman
                    |--------------------------------------------------------------------------
                    */

                    if (
                        !$this->canModify(
                            $item
                        )
                    ) {

                        http_response_code(403);

                        exit('Sertifikat yang sudah diverifikasi tidak dapat diedit.');
                    }


                    $allSertifikat[$index]['slug'] =
                        $newSlug;


                    $allSertifikat[$index]['nama'] =
                        $old['nama'];


                    $allSertifikat[$index]['penerbit'] =
                        $old['penerbit'];


                    $allSertifikat[$index]['tanggal_terbit'] =
                        $old['tanggal_terbit'];


                    $allSertifikat[$index]['nomor_sertifikat'] =
                        $old['nomor_sertifikat'];


                    $allSertifikat[$index]['deskripsi'] =
                        $old['deskripsi'];


                    $allSertifikat[$index]['gambar'] =
                        $newImage;


                    $allSertifikat[$index]['tautan'] = [

                        'sertifikat' =>
                        $old['tautan'],

                    ];


                    /*
                    |--------------------------------------------------------------------------
                    | Status tetap belum terverifikasi
                    |--------------------------------------------------------------------------
                    */

                    $allSertifikat[$index]['verifikasi'] =
                        $item['verifikasi'] ?? [

                            'status' =>
                            'belum_terverifikasi',

                            'label' =>
                            'Belum Terverifikasi',

                        ];


                    /*
                    |--------------------------------------------------------------------------
                    | Hapus gambar lama setelah gambar baru tersimpan
                    |--------------------------------------------------------------------------
                    */

                    if (
                        $newImage !==
                        ($item['gambar'] ?? '')
                    ) {

                        $this->deleteImage(
                            $item['gambar'] ?? ''
                        );
                    }


                    break;
                }


                $this->saveSertifikat(
                    $allSertifikat
                );


                header(
                    'Location: ' .
                        url(
                            '/dashboard/mahasiswa/sertifikat/detail/' .
                                $newSlug
                        )
                );

                exit;
            }
        }


        $mode = 'edit';


        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/sertifikat/form.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus
    |--------------------------------------------------------------------------
    */

    public function hapus($params = [])
    {
        /*
        |--------------------------------------------------------------------------
        | Hanya POST
        |--------------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] !== 'POST'
        ) {

            http_response_code(405);

            exit('Method Not Allowed');
        }


        $slug =
            $params['slug'] ?? '';


        $sertifikat =
            $this->findBySlug(
                $slug
            );


        /*
        |--------------------------------------------------------------------------
        | Tidak ditemukan
        |--------------------------------------------------------------------------
        */

        if (
            !$sertifikat
        ) {

            http_response_code(404);

            require __DIR__ .
                '/../../../pages/errors/404.php';

            exit;
        }


        /*
        |--------------------------------------------------------------------------
        | Status tidak boleh dihapus
        |--------------------------------------------------------------------------
        */

        if (
            !$this->canModify(
                $sertifikat
            )
        ) {

            http_response_code(403);

            exit('Sertifikat yang sudah diverifikasi tidak dapat dihapus.');
        }


        $allSertifikat =
            $this->getAllSertifikat();


        $hasil = [];


        foreach (
            $allSertifikat
            as $item
        ) {

            if (
                ($item['slug'] ?? '') ===
                $slug
            ) {
                continue;
            }


            $hasil[] =
                $item;
        }


        /*
        |--------------------------------------------------------------------------
        | Hapus gambar
        |--------------------------------------------------------------------------
        */

        $this->deleteImage(
            $sertifikat['gambar'] ?? ''
        );


        /*
        |--------------------------------------------------------------------------
        | Simpan
        |--------------------------------------------------------------------------
        */

        $this->saveSertifikat(
            $hasil
        );


        header(
            'Location: ' .
                url(
                    '/dashboard/mahasiswa/sertifikat'
                )
        );

        exit;
    }


    /*
    |--------------------------------------------------------------------------
    | Download / Buka Sertifikat
    |--------------------------------------------------------------------------
    */

    public function download($params = [])
    {
        $slug = trim(
            (string) ($params['slug'] ?? '')
        );

        $sertifikat = $this->findBySlug($slug);

        if (!$sertifikat) {
            http_response_code(404);

            exit('Sertifikat tidak ditemukan.');
        }


        /*
        |--------------------------------------------------------------------------
        | Ambil gambar
        |--------------------------------------------------------------------------
        */

        $gambar = basename(
            trim(
                (string) ($sertifikat['gambar'] ?? '')
            )
        );

        if ($gambar === '') {
            http_response_code(404);

            exit('Foto sertifikat tidak tersedia.');
        }


        /*
        |--------------------------------------------------------------------------
        | Folder gambar
        |--------------------------------------------------------------------------
        */

        $imageDir = realpath(
            __DIR__ . '/../../../assets/images/sertifikat'
        );

        if ($imageDir === false) {
            http_response_code(500);

            exit('Folder foto sertifikat tidak ditemukan.');
        }


        /*
        |--------------------------------------------------------------------------
        | File gambar
        |--------------------------------------------------------------------------
        */

        $filePath = realpath(
            $imageDir .
                DIRECTORY_SEPARATOR .
                $gambar
        );


        /*
        |--------------------------------------------------------------------------
        | Security check
        |--------------------------------------------------------------------------
        */

        if (
            $filePath === false ||
            !is_file($filePath) ||
            !str_starts_with(
                $filePath,
                $imageDir . DIRECTORY_SEPARATOR
            )
        ) {
            http_response_code(404);

            exit('Foto sertifikat tidak ditemukan.');
        }


        /*
        |--------------------------------------------------------------------------
        | Validasi gambar
        |--------------------------------------------------------------------------
        */

        $imageInfo = @getimagesize(
            $filePath
        );

        if ($imageInfo === false) {
            http_response_code(422);

            exit('File foto sertifikat tidak valid.');
        }


        $widthPx = (int) (
            $imageInfo[0] ?? 0
        );

        $heightPx = (int) (
            $imageInfo[1] ?? 0
        );

        $mime = strtolower(
            (string) (
                $imageInfo['mime'] ?? ''
            )
        );


        if (
            $widthPx <= 0 ||
            $heightPx <= 0
        ) {
            http_response_code(422);

            exit('Ukuran foto sertifikat tidak valid.');
        }


        $allowedMime = [
            'image/jpeg',
            'image/png',
            'image/webp',
        ];


        if (
            !in_array(
                $mime,
                $allowedMime,
                true
            )
        ) {
            http_response_code(422);

            exit('Format foto sertifikat tidak didukung.');
        }


        /*
        |--------------------------------------------------------------------------
        | Composer
        |--------------------------------------------------------------------------
        */

        $autoload =
            __DIR__ .
            '/../../../vendor/autoload.php';


        if (!is_file($autoload)) {
            http_response_code(500);

            exit('Dompdf belum terpasang. ' .
                'Jalankan: composer require dompdf/dompdf');
        }


        require_once $autoload;


        if (
            !class_exists(
                'Dompdf\\Dompdf'
            )
        ) {
            http_response_code(500);

            exit('Library Dompdf tidak tersedia.');
        }


        /*
        |--------------------------------------------------------------------------
        | Baca gambar
        |--------------------------------------------------------------------------
        */

        $imageData = @file_get_contents(
            $filePath
        );


        if ($imageData === false) {
            http_response_code(500);

            exit('Foto sertifikat tidak dapat dibaca.');
        }


        /*
        |--------------------------------------------------------------------------
        | Data URI
        |--------------------------------------------------------------------------
        */

        $dataUri =
            'data:' .
            $mime .
            ';base64,' .
            base64_encode(
                $imageData
            );


        /*
        |--------------------------------------------------------------------------
        | Rasio gambar
        |--------------------------------------------------------------------------
        */

        $imageRatio =
            $widthPx /
            $heightPx;


        /*
        |--------------------------------------------------------------------------
        | Ukuran A4
        |--------------------------------------------------------------------------
        */

        $a4PortraitWidth =
            210;

        $a4PortraitHeight =
            297;

        $a4LandscapeWidth =
            297;

        $a4LandscapeHeight =
            210;


        $a4PortraitRatio =
            $a4PortraitWidth /
            $a4PortraitHeight;


        /*
        |--------------------------------------------------------------------------
        | Tentukan orientasi
        |--------------------------------------------------------------------------
        */

        if (
            $imageRatio >=
            $a4PortraitRatio
        ) {

            /*
            |--------------------------------------------------------------------------
            | Landscape
            |--------------------------------------------------------------------------
            */

            $orientation =
                'landscape';

            $pageWidth =
                $a4LandscapeWidth;

            $pageHeight =
                $a4LandscapeHeight;
        } else {

            /*
            |--------------------------------------------------------------------------
            | Portrait
            |--------------------------------------------------------------------------
            */

            $orientation =
                'portrait';

            $pageWidth =
                $a4PortraitWidth;

            $pageHeight =
                $a4PortraitHeight;
        }


        /*
        |--------------------------------------------------------------------------
        | Hitung ukuran gambar agar FIT dalam 1 halaman
        |--------------------------------------------------------------------------
        */

        $pageRatio =
            $pageWidth /
            $pageHeight;


        if (
            $imageRatio >
            $pageRatio
        ) {

            /*
            |--------------------------------------------------------------------------
            | Gambar lebih lebar
            |--------------------------------------------------------------------------
            */

            $imageWidth =
                $pageWidth;

            $imageHeight =
                $pageWidth /
                $imageRatio;
        } else {

            /*
            |--------------------------------------------------------------------------
            | Gambar lebih tinggi
            |--------------------------------------------------------------------------
            */

            $imageHeight =
                $pageHeight;

            $imageWidth =
                $pageHeight *
                $imageRatio;
        }


        /*
        |--------------------------------------------------------------------------
        | Hitung posisi tengah
        |--------------------------------------------------------------------------
        */

        $left =
            ($pageWidth - $imageWidth) / 2;

        $top =
            ($pageHeight - $imageHeight) / 2;


        /*
        |--------------------------------------------------------------------------
        | HTML PDF
        |--------------------------------------------------------------------------
        |
        | Gambar ditempatkan dengan ukuran pasti.
        | Tidak akan membuat halaman tambahan.
        |
        */

        $html = '
    <!DOCTYPE html>
    
    <html lang="id">
    
    <head>
    
    <meta charset="UTF-8">
    
    <style>
    
        @page {
            margin: 0;
            padding: 0;
        }
    
        html,
        body {
            width: ' . $pageWidth . 'mm;
            height: ' . $pageHeight . 'mm;
            margin: 0;
            padding: 0;
        }
    
        .certificate {
            position: relative;
            width: ' . $pageWidth . 'mm;
            height: ' . $pageHeight . 'mm;
            margin: 0;
            padding: 0;
            overflow: hidden;
        }
    
        .certificate img {
            position: absolute;
    
            left: ' . $left . 'mm;
            top: ' . $top . 'mm;
    
            width: ' . $imageWidth . 'mm;
            height: ' . $imageHeight . 'mm;
    
            margin: 0;
            padding: 0;
        }
    
    </style>
    
    </head>
    
    <body>
    
    <div class="certificate">
    
        <img
            src="' . $dataUri . '"
            alt="Sertifikat"
        >
    
    </div>
    
    </body>
    
    </html>';


        /*
        |--------------------------------------------------------------------------
        | Dompdf options
        |--------------------------------------------------------------------------
        */

        $options =
            new \Dompdf\Options();


        $options->set(
            'defaultFont',
            'DejaVu Sans'
        );


        $options->set(
            'isRemoteEnabled',
            false
        );


        /*
        |--------------------------------------------------------------------------
        | Generate PDF
        |--------------------------------------------------------------------------
        */

        $dompdf =
            new \Dompdf\Dompdf(
                $options
            );


        $dompdf->setPaper(
            'A4',
            $orientation
        );


        $dompdf->loadHtml(
            $html,
            'UTF-8'
        );


        $dompdf->render();


        /*
        |--------------------------------------------------------------------------
        | Nama file
        |--------------------------------------------------------------------------
        */

        $downloadName =
            slugify(
                $sertifikat['nama']
                    ?? 'sertifikat'
            );


        if ($downloadName === '') {
            $downloadName =
                'sertifikat';
        }


        $downloadName .= '.pdf';


        /*
        |--------------------------------------------------------------------------
        | Bersihkan output buffer
        |--------------------------------------------------------------------------
        */

        while (
            ob_get_level() > 0
        ) {
            ob_end_clean();
        }


        /*
        |--------------------------------------------------------------------------
        | Download
        |--------------------------------------------------------------------------
        */

        $dompdf->stream(
            $downloadName,
            [
                'Attachment' => true,
            ]
        );


        exit;
    }
}
