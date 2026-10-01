<?php

require_once __DIR__ . '/../../../data/dashboard/mahasiswa/FormasiMagang.php';

class LamarController
{
    public function index($params = [])
    {
        global $formasiMagang;

        $slug = trim(
            $params['slug'] ?? ''
        );

        $formasi = null;

        foreach ($formasiMagang as $item) {

            if (($item['slug'] ?? '') === $slug) {

                $formasi = $item;

                break;
            }
        }

        /*
        |-----------------------
        | Formasi tidak ditemukan
        |-----------------------
        */

        if (!$formasi) {

            http_response_code(404);

            require __DIR__ . '/../../../pages/errors/404.php';

            exit;
        }


        /*
        |-----------------------
        | Formasi penuh
        |-----------------------
        |
        | Mahasiswa tidak boleh
        | masuk ke proses Lamar
        | apabila kuota penuh.
        |
        */

        if (($formasi['status'] ?? '') !== 'tersedia') {

            http_response_code(409);

            require __DIR__ . '/../../../pages/errors/404.php';

            exit;
        }


        /*
        |-----------------------
        | Data mahasiswa
        |-----------------------
        |
        | Untuk sementara menggunakan
        | data session apabila tersedia.
        |
        | Nanti bisa diganti dengan
        | data dari database mahasiswa.
        |
        */

        $sessionUser = $_SESSION['user'] ?? [];


        $mahasiswa = [
            'nama' => $sessionUser['nama']
                ?? 'Mahasiswa',

            'nim' => $sessionUser['nim']
                ?? '-',

            'prodi' => $sessionUser['prodi']
                ?? '-',

            'jurusan' => $sessionUser['jurusan']
                ?? 'Teknologi Informasi',
        ];


        /*
        |-----------------------
        | Data form
        |-----------------------
        */

        $nama = trim(
            $_POST['nama'] ?? $mahasiswa['nama']
        );

        $nim = trim(
            $_POST['nim'] ?? $mahasiswa['nim']
        );

        $prodi = trim(
            $_POST['prodi'] ?? $mahasiswa['prodi']
        );

        $setuju = isset(
            $_POST['pernyataan']
        );


        $errors = [];

        $success = false;


        /*
        |-----------------------
        | Submit
        |-----------------------
        */

        if (
            $_SERVER['REQUEST_METHOD'] === 'POST'
            && isset($_POST['submit_pengajuan'])
        ) {

            if ($nama === '') {

                $errors['nama'] =
                    'Nama mahasiswa wajib diisi.';
            }


            if ($nim === '') {

                $errors['nim'] =
                    'NIM wajib diisi.';
            }


            if ($prodi === '') {

                $errors['prodi'] =
                    'Program studi wajib diisi.';
            }


            if (
                empty($_FILES['proposal']['name'])
            ) {

                $errors['proposal'] =
                    'Proposal Pengajuan wajib diunggah.';
            }


            if (
                empty(
                    $_FILES['fakta_integritas']['name']
                )
            ) {

                $errors['fakta_integritas'] =
                    'Fakta Integritas wajib diunggah.';
            }


            if (!$setuju) {

                $errors['pernyataan'] =
                    'Pernyataan wajib disetujui.';
            }


            /*
            |-----------------------
            | Sementara
            |-----------------------
            |
            | Belum menyimpan database.
            |
            */

            if (empty($errors)) {

                $success = true;
            }
        }


        require __DIR__ . '/../../../pages/dashboard/mahasiswa/lamar/index.php';
    }
}