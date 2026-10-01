<?php

require_once __DIR__ . '/../../../data/dashboard/mahasiswa/Portofolio.php';

class PortofolioController
{
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
    | Ambil seluruh data portofolio
    |--------------------------------------------------------------------------
    */

    private function getAllPortofolio()
    {
        global $portofolio;

        $this->startSession();

        /*
        |----------------------------------------------------------------------
        | Jika session belum memiliki data portofolio,
        | gunakan data awal dari Portofolio.php
        |----------------------------------------------------------------------
        */

        if (!isset($_SESSION['portofolio_data'])) {

            $_SESSION['portofolio_data'] = $portofolio;

            /*
            |------------------------------------------------------------------
            | Ambil data tambahan dari sistem tambah versi sebelumnya
            |------------------------------------------------------------------
            */

            if (!empty($_SESSION['portofolio_tambahan'])) {

                $_SESSION['portofolio_data'] = array_merge(
                    $_SESSION['portofolio_data'],
                    $_SESSION['portofolio_tambahan']
                );

                unset($_SESSION['portofolio_tambahan']);
            }
        }

        return $_SESSION['portofolio_data'];
    }


    /*
    |--------------------------------------------------------------------------
    | Simpan data portofolio ke session
    |--------------------------------------------------------------------------
    */

    private function savePortofolio($data)
    {
        $this->startSession();

        $_SESSION['portofolio_data'] = $data;
    }


    /*
    |--------------------------------------------------------------------------
    | Index
    |--------------------------------------------------------------------------
    */

    public function index($params = [])
    {
        /*
        |----------------------------------------------------------------------
        | Gunakan data dari session
        |----------------------------------------------------------------------
        */

        $portofolio = $this->getAllPortofolio();


        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        $q = trim($_GET['q'] ?? '');

        $tahun = trim($_GET['tahun'] ?? '');

        $status = trim($_GET['status'] ?? '');

        $teknologi = trim($_GET['teknologi'] ?? '');


        /*
        |--------------------------------------------------------------------------
        | Data pilihan filter
        |--------------------------------------------------------------------------
        */

        $tahunList = [];

        $teknologiList = [];


        foreach ($portofolio as $item) {

            $itemTahun = trim(
                $item['tahun'] ?? ''
            );

            if ($itemTahun !== '') {

                $tahunList[] = $itemTahun;
            }


            foreach (
                ($item['teknologi'] ?? [])
                as $itemTeknologi
            ) {

                $itemTeknologi = trim(
                    $itemTeknologi
                );

                if ($itemTeknologi !== '') {

                    $teknologiList[] = $itemTeknologi;
                }
            }
        }


        $tahunList = array_values(
            array_unique($tahunList)
        );

        rsort($tahunList);


        $teknologiList = array_values(
            array_unique($teknologiList)
        );

        sort($teknologiList);


        /*
        |--------------------------------------------------------------------------
        | Filtering
        |--------------------------------------------------------------------------
        */

        $hasilFilter = array_filter(
            $portofolio,
            function ($item) use (
                $q,
                $tahun,
                $status,
                $teknologi
            ) {

                /*
                |------------------------------------------------------------------
                | Search
                |------------------------------------------------------------------
                */

                if ($q !== '') {

                    $keyword = strtolower($q);

                    $judul = strtolower(
                        $item['judul'] ?? ''
                    );

                    $deskripsi = strtolower(
                        $item['deskripsi'] ?? ''
                    );

                    $peran = strtolower(
                        $item['peran'] ?? ''
                    );

                    $teknologiText = strtolower(
                        implode(
                            ' ',
                            $item['teknologi'] ?? []
                        )
                    );


                    if (
                        strpos(
                            $judul,
                            $keyword
                        ) === false &&

                        strpos(
                            $deskripsi,
                            $keyword
                        ) === false &&

                        strpos(
                            $peran,
                            $keyword
                        ) === false &&

                        strpos(
                            $teknologiText,
                            $keyword
                        ) === false
                    ) {

                        return false;
                    }
                }


                /*
                |------------------------------------------------------------------
                | Tahun
                |------------------------------------------------------------------
                */

                if (
                    $tahun !== '' &&
                    ($item['tahun'] ?? '') !== $tahun
                ) {

                    return false;
                }


                /*
                |------------------------------------------------------------------
                | Status
                |------------------------------------------------------------------
                */

                if (
                    $status !== '' &&
                    ($item['verifikasi']['status'] ?? '') !== $status
                ) {

                    return false;
                }


                /*
                |------------------------------------------------------------------
                | Teknologi
                |------------------------------------------------------------------
                */

                if ($teknologi !== '') {

                    $itemTeknologi = array_map(
                        'strtolower',
                        $item['teknologi'] ?? []
                    );


                    if (
                        !in_array(
                            strtolower($teknologi),
                            $itemTeknologi,
                            true
                        )
                    ) {

                        return false;
                    }
                }


                return true;
            }
        );


        $hasilFilter = array_values(
            $hasilFilter
        );


        /*
        |--------------------------------------------------------------------------
        | Pagination
        |--------------------------------------------------------------------------
        */

        $perPage = 6;

        $totalData = count(
            $hasilFilter
        );


        $totalPage = max(
            1,
            (int) ceil(
                $totalData / $perPage
            )
        );


        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
        );


        if ($page > $totalPage) {

            $page = $totalPage;
        }


        $offset = (
            $page - 1
        ) * $perPage;


        $tampil = array_slice(
            $hasilFilter,
            $offset,
            $perPage
        );


        /*
        |--------------------------------------------------------------------------
        | Data pagination untuk view
        |--------------------------------------------------------------------------
        */

        $pagination = [
            'current_page' => $page,
            'per_page' => $perPage,
            'total_data' => $totalData,
            'total_page' => $totalPage,
        ];


        /*
        |--------------------------------------------------------------------------
        | View
        |--------------------------------------------------------------------------
        */

        require __DIR__
            . '/../../../pages/dashboard/mahasiswa/portofolio/index.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Detail
    |--------------------------------------------------------------------------
    */

    public function detail($params = [])
    {
        $portofolio = $this->getAllPortofolio();

        $slug = trim(
            $params['slug'] ?? ''
        );


        $portofolioDetail = null;


        foreach ($portofolio as $item) {

            if (
                ($item['slug'] ?? '') === $slug
            ) {

                $portofolioDetail = $item;

                break;
            }
        }


        if (!$portofolioDetail) {

            http_response_code(404);

            require __DIR__
                . '/../../../pages/errors/404.php';

            exit;
        }


        require __DIR__
            . '/../../../pages/dashboard/mahasiswa/portofolio/detail.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Tambah
    |--------------------------------------------------------------------------
    */

    public function tambah($params = [])
    {
        $this->startSession();

        $errors = [];


        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $judul = trim(
                $_POST['judul'] ?? ''
            );

            $deskripsi = trim(
                $_POST['deskripsi'] ?? ''
            );

            $teknologiInput = trim(
                $_POST['teknologi'] ?? ''
            );

            $peran = trim(
                $_POST['peran'] ?? ''
            );

            $tahun = trim(
                $_POST['tahun'] ?? ''
            );

            $github = trim(
                $_POST['github'] ?? ''
            );

            $demo = trim(
                $_POST['demo'] ?? ''
            );


            /*
            |------------------------------------------------------------------
            | Validasi
            |------------------------------------------------------------------
            */

            if ($judul === '') {

                $errors['judul'] =
                    'Judul portofolio wajib diisi.';
            }


            if ($deskripsi === '') {

                $errors['deskripsi'] =
                    'Deskripsi portofolio wajib diisi.';
            }


            if ($teknologiInput === '') {

                $errors['teknologi'] =
                    'Teknologi wajib diisi.';
            }


            if ($peran === '') {

                $errors['peran'] =
                    'Peran wajib diisi.';
            }


            if ($tahun === '') {

                $errors['tahun'] =
                    'Tahun wajib diisi.';
            }


            /*
            |------------------------------------------------------------------
            | Simpan
            |------------------------------------------------------------------
            */

            if (empty($errors)) {

                $portofolio =
                    $this->getAllPortofolio();


                $teknologi = array_filter(
                    array_map(
                        'trim',
                        explode(
                            ',',
                            $teknologiInput
                        )
                    )
                );


                /*
                |----------------------------------------------------------------
                | Slug
                |----------------------------------------------------------------
                */

                $slug = slugify(
                    $judul
                );


                $slugs = array_column(
                    $portofolio,
                    'slug'
                );


                $originalSlug = $slug;

                $counter = 2;


                while (
                    in_array(
                        $slug,
                        $slugs,
                        true
                    )
                ) {

                    $slug =
                        $originalSlug
                        . '-'
                        . $counter;

                    $counter++;
                }


                /*
                |----------------------------------------------------------------
                | ID
                |----------------------------------------------------------------
                */

                $lastId = 0;


                foreach ($portofolio as $item) {

                    $lastId = max(
                        $lastId,
                        (int) (
                            $item['id'] ?? 0
                        )
                    );
                }


                /*
                |----------------------------------------------------------------
                | Data baru
                |----------------------------------------------------------------
                */

                $dataBaru = [

                    'id' => $lastId + 1,

                    'slug' => $slug,

                    'judul' => $judul,

                    'deskripsi' => $deskripsi,

                    'teknologi' =>
                        array_values(
                            $teknologi
                        ),

                    'peran' => $peran,

                    'tahun' => $tahun,

                    'tautan' => [

                        'github' => $github,

                        'demo' => $demo,

                    ],

                    'verifikasi' => [

                        'status' =>
                            'belum_terverifikasi',

                        'label' =>
                            'Belum Terverifikasi',

                    ],

                ];


                /*
                |----------------------------------------------------------------
                | Tambahkan ke dataset
                |----------------------------------------------------------------
                */

                $portofolio[] = $dataBaru;


                /*
                |----------------------------------------------------------------
                | Simpan ke session
                |----------------------------------------------------------------
                */

                $this->savePortofolio(
                    $portofolio
                );


                /*
                |----------------------------------------------------------------
                | Redirect detail
                |----------------------------------------------------------------
                */

                header(
                    'Location: '
                    . url(
                        '/dashboard/mahasiswa/portofolio/detail/'
                        . $slug
                    )
                );

                exit;
            }
        }


        $isEdit = false;

        $portofolioEdit = null;


        require __DIR__
            . '/../../../pages/dashboard/mahasiswa/portofolio/tambah.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Edit
    |--------------------------------------------------------------------------
    */

    public function edit($params = [])
    {
        $this->startSession();


        $slug = trim(
            $params['slug'] ?? ''
        );


        $portofolio =
            $this->getAllPortofolio();


        $portofolioEdit = null;


        foreach ($portofolio as $item) {

            if (
                ($item['slug'] ?? '') === $slug
            ) {

                $portofolioEdit = $item;

                break;
            }
        }


        /*
        |----------------------------------------------------------------------
        | Data tidak ditemukan
        |----------------------------------------------------------------------
        */

        if (!$portofolioEdit) {

            http_response_code(404);

            require __DIR__
                . '/../../../pages/errors/404.php';

            exit;
        }


        $errors = [];


        /*
        |--------------------------------------------------------------------------
        | Update
        |--------------------------------------------------------------------------
        */

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            $judul = trim(
                $_POST['judul'] ?? ''
            );

            $deskripsi = trim(
                $_POST['deskripsi'] ?? ''
            );

            $teknologiInput = trim(
                $_POST['teknologi'] ?? ''
            );

            $peran = trim(
                $_POST['peran'] ?? ''
            );

            $tahun = trim(
                $_POST['tahun'] ?? ''
            );

            $github = trim(
                $_POST['github'] ?? ''
            );

            $demo = trim(
                $_POST['demo'] ?? ''
            );


            /*
            |------------------------------------------------------------------
            | Validasi
            |------------------------------------------------------------------
            */

            if ($judul === '') {

                $errors['judul'] =
                    'Judul portofolio wajib diisi.';
            }


            if ($deskripsi === '') {

                $errors['deskripsi'] =
                    'Deskripsi portofolio wajib diisi.';
            }


            if ($teknologiInput === '') {

                $errors['teknologi'] =
                    'Teknologi wajib diisi.';
            }


            if ($peran === '') {

                $errors['peran'] =
                    'Peran wajib diisi.';
            }


            if ($tahun === '') {

                $errors['tahun'] =
                    'Tahun wajib diisi.';
            }


            /*
            |------------------------------------------------------------------
            | Jika valid
            |------------------------------------------------------------------
            */

            if (empty($errors)) {

                $teknologi = array_filter(
                    array_map(
                        'trim',
                        explode(
                            ',',
                            $teknologiInput
                        )
                    )
                );


                /*
                |----------------------------------------------------------------
                | Slug baru
                |----------------------------------------------------------------
                */

                $slugBaru = slugify(
                    $judul
                );


                $slugs = array_column(
                    $portofolio,
                    'slug'
                );


                /*
                | Jangan menganggap slug milik data
                | yang sedang diedit sebagai duplikat.
                */

                $slugs = array_values(
                    array_filter(
                        $slugs,
                        function ($itemSlug) use ($slug) {

                            return $itemSlug !== $slug;
                        }
                    )
                );


                $originalSlug = $slugBaru;

                $counter = 2;


                while (
                    in_array(
                        $slugBaru,
                        $slugs,
                        true
                    )
                ) {

                    $slugBaru =
                        $originalSlug
                        . '-'
                        . $counter;

                    $counter++;
                }


                /*
                |----------------------------------------------------------------
                | Update data
                |----------------------------------------------------------------
                */

                foreach (
                    $portofolio
                    as &$item
                ) {

                    if (
                        ($item['slug'] ?? '')
                        === $slug
                    ) {

                        $item = [

                            'id' =>
                                $portofolioEdit['id'],

                            'slug' =>
                                $slugBaru,

                            'judul' =>
                                $judul,

                            'deskripsi' =>
                                $deskripsi,

                            'teknologi' =>
                                array_values(
                                    $teknologi
                                ),

                            'peran' =>
                                $peran,

                            'tahun' =>
                                $tahun,

                            'tautan' => [

                                'github' =>
                                    $github,

                                'demo' =>
                                    $demo,

                            ],

                            /*
                            |----------------------------------------------------
                            | Status verifikasi tidak berubah ketika edit.
                            |----------------------------------------------------
                            */

                            'verifikasi' =>
                                $portofolioEdit[
                                    'verifikasi'
                                ],
                        ];

                        break;
                    }
                }


                unset($item);


                /*
                |----------------------------------------------------------------
                | Simpan
                |----------------------------------------------------------------
                */

                $this->savePortofolio(
                    $portofolio
                );


                /*
                |----------------------------------------------------------------
                | Redirect
                |----------------------------------------------------------------
                */

                header(
                    'Location: '
                    . url(
                        '/dashboard/mahasiswa/portofolio/detail/'
                        . $slugBaru
                    )
                );

                exit;
            }


            /*
            |------------------------------------------------------------------
            | Jika validasi gagal
            |------------------------------------------------------------------
            */

            $portofolioEdit = array_merge(
                $portofolioEdit,
                [

                    'judul' =>
                        $judul,

                    'deskripsi' =>
                        $deskripsi,

                    'teknologi' =>
                        array_filter(
                            array_map(
                                'trim',
                                explode(
                                    ',',
                                    $teknologiInput
                                )
                            )
                        ),

                    'peran' =>
                        $peran,

                    'tahun' =>
                        $tahun,

                    'tautan' => [

                        'github' =>
                            $github,

                        'demo' =>
                            $demo,

                    ],

                ]
            );
        }


        $isEdit = true;


        require __DIR__
            . '/../../../pages/dashboard/mahasiswa/portofolio/tambah.php';
    }


    /*
    |--------------------------------------------------------------------------
    | Hapus
    |--------------------------------------------------------------------------
    */

    public function hapus($params = [])
    {
        /*
        |----------------------------------------------------------------------
        | Hapus hanya boleh melalui POST
        |----------------------------------------------------------------------
        */

        if (
            $_SERVER['REQUEST_METHOD']
            !== 'POST'
        ) {

            http_response_code(405);

            echo 'Method Not Allowed';

            exit;
        }


        $slug = trim(
            $params['slug'] ?? ''
        );


        $portofolio =
            $this->getAllPortofolio();


        $ditemukan = false;

        $portofolioBaru = [];


        foreach ($portofolio as $item) {

            if (
                ($item['slug'] ?? '')
                === $slug
            ) {

                $ditemukan = true;

                continue;
            }


            $portofolioBaru[] = $item;
        }


        /*
        |----------------------------------------------------------------------
        | Data tidak ditemukan
        |----------------------------------------------------------------------
        */

        if (!$ditemukan) {

            http_response_code(404);

            require __DIR__
                . '/../../../pages/errors/404.php';

            exit;
        }


        /*
        |----------------------------------------------------------------------
        | Simpan data setelah dihapus
        |----------------------------------------------------------------------
        */

        $this->savePortofolio(
            $portofolioBaru
        );


        /*
        |----------------------------------------------------------------------
        | Kembali ke daftar
        |----------------------------------------------------------------------
        */

        header(
            'Location: '
            . url(
                '/dashboard/mahasiswa/portofolio'
            )
        );

        exit;
    }
}