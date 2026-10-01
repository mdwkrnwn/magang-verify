<?php

require_once __DIR__ . '/../../../data/dashboard/mahasiswa/FormasiMagang.php';

class FormasiMagangController
{
    public function index($params = [])
    {
        global $formasiMagang;

        $q = trim($_GET['q'] ?? '');

        $fLokasi = trim($_GET['lokasi'] ?? '');
        $fDurasi = trim($_GET['durasi'] ?? '');
        $fStatus = trim($_GET['status'] ?? '');

        $perPage = 5;

        /*
         * Opsi filter
         */
        $optLokasi = array_values(
            array_unique(
                array_column($formasiMagang, 'kota')
            )
        );

        $optDurasi = array_values(
            array_unique(
                array_column($formasiMagang, 'durasi')
            )
        );

        $optStatus = array_values(
            array_unique(
                array_column($formasiMagang, 'status')
            )
        );

        /*
         * Filter
         */
        $hasil = array_values(
            array_filter(
                $formasiMagang,
                function ($item) use (
                    $q,
                    $fLokasi,
                    $fDurasi,
                    $fStatus
                ) {
                    $cari = strtolower($q);

                    $teks = strtolower(
                        implode(' ', [
                            $item['perusahaan'],
                            $item['posisi'],
                            $item['lokasi'],
                            $item['deskripsi'],
                            implode(' ', $item['keahlian']),
                            implode(' ', $item['prodi']),
                        ])
                    );

                    return (
                        $q === ''
                        || str_contains($teks, $cari)
                    )
                        && (
                            $fLokasi === ''
                            || $item['kota'] === $fLokasi
                        )
                        && (
                            $fDurasi === ''
                            || $item['durasi'] === $fDurasi
                        )
                        && (
                            $fStatus === ''
                            || $item['status'] === $fStatus
                        );
                }
            )
        );

        /*
         * Pagination
         */
        $total = count($hasil);

        $pages = max(
            1,
            (int) ceil($total / $perPage)
        );

        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
        );

        $page = min(
            $page,
            $pages
        );

        $tampil = array_slice(
            $hasil,
            ($page - 1) * $perPage,
            $perPage
        );

        /*
         * URL pagination
         * Mempertahankan filter yang sedang aktif.
         */
        $url = function ($pageNumber) {
            return '?' . http_build_query(
                array_merge(
                    $_GET,
                    [
                        'page' => $pageNumber,
                    ]
                )
            );
        };

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/index.php';
    }

    public function detail($params = [])
    {
        global $formasiMagang;

        $slug = $params['slug'] ?? '';

        $formasi = null;

        foreach ($formasiMagang as $item) {

            if ($item['slug'] === $slug) {
                $formasi = $item;
                break;
            }
        }

        if (!$formasi) {
            http_response_code(404);

            require __DIR__ . '/../../../pages/errors/404.php';

            exit;
        }

        require __DIR__ . '/../../../pages/dashboard/mahasiswa/formasi-magang/detail.php';
    }
}
