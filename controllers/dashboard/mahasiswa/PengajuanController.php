<?php

require_once __DIR__ . '/../../../data/dashboard/mahasiswa/Pengajuan.php';

class PengajuanController
{
    public function index($params = [])
    {
        global $pengajuanData;

        /*
         * ==========================================
         * 1. MAHASISWA AKTIF
         * ==========================================
         */

        $mahasiswaSlug = 'mahasiswa-demo';


        /*
         * ==========================================
         * 2. AMBIL DATA MILIK MAHASISWA
         * ==========================================
         */

        $pengajuan = array_values(
            array_filter(
                $pengajuanData,
                function ($item) use ($mahasiswaSlug) {
                    return (
                        ($item['mahasiswaSlug'] ?? '') ===
                        $mahasiswaSlug
                    );
                }
            )
        );


        /*
         * ==========================================
         * 3. FILTER SEARCH
         * ==========================================
         */

        $search = trim(
            $_GET['search'] ?? ''
        );

        if ($search !== '') {

            $keyword = strtolower($search);

            $pengajuan = array_values(
                array_filter(
                    $pengajuan,
                    function ($item) use ($keyword) {

                        $perusahaan = strtolower(
                            $item['perusahaan'] ?? ''
                        );

                        $posisi = strtolower(
                            $item['posisi'] ?? ''
                        );

                        return (
                            str_contains(
                                $perusahaan,
                                $keyword
                            )
                            ||
                            str_contains(
                                $posisi,
                                $keyword
                            )
                        );
                    }
                )
            );
        }


        /*
         * ==========================================
         * 4. FILTER STATUS
         * ==========================================
         */

        $status = trim(
            $_GET['status'] ?? ''
        );

        if ($status !== '') {

            $pengajuan = array_values(
                array_filter(
                    $pengajuan,
                    function ($item) use ($status) {
                        return (
                            ($item['status'] ?? '') ===
                            $status
                        );
                    }
                )
            );
        }


        /*
         * ==========================================
         * 5. SORTING
         * ==========================================
         */

        $sort = $_GET['sort'] ?? 'terbaru';

        $bulan = [
            'januari' => 1,
            'februari' => 2,
            'maret' => 3,
            'april' => 4,
            'mei' => 5,
            'juni' => 6,
            'juli' => 7,
            'agustus' => 8,
            'september' => 9,
            'oktober' => 10,
            'november' => 11,
            'desember' => 12,
        ];

        $parseTanggal = function ($tanggal) use ($bulan) {

            $parts = preg_split(
                '/\s+/',
                strtolower(trim($tanggal))
            );

            if (count($parts) !== 3) {
                return 0;
            }

            $day = (int) $parts[0];
            $month = $bulan[$parts[1]] ?? 0;
            $year = (int) $parts[2];

            if (!$day || !$month || !$year) {
                return 0;
            }

            return mktime(
                0,
                0,
                0,
                $month,
                $day,
                $year
            );
        };

        usort(
            $pengajuan,
            function ($a, $b) use (
                $parseTanggal,
                $sort
            ) {

                $tanggalA = $parseTanggal(
                    $a['tanggal'] ?? ''
                );

                $tanggalB = $parseTanggal(
                    $b['tanggal'] ?? ''
                );

                if ($sort === 'terlama') {
                    return $tanggalA <=> $tanggalB;
                }

                return $tanggalB <=> $tanggalA;
            }
        );


        /*
         * ==========================================
         * 6. PAGINATION
         * ==========================================
         */

        $perPage = 5;

        $totalData = count($pengajuan);

        $pages = max(
            1,
            (int) ceil($totalData / $perPage)
        );

        $page = max(
            1,
            (int) ($_GET['page'] ?? 1)
        );

        if ($page > $pages) {
            $page = $pages;
        }

        $offset = (
            $page - 1
        ) * $perPage;

        $pengajuan = array_slice(
            $pengajuan,
            $offset,
            $perPage
        );


        /*
         * ==========================================
         * 7. URL PAGINATION
         * ==========================================
         */

        $url = function ($pageNumber) use (
            $search,
            $status,
            $sort
        ) {

            $query = [];

            if ($search !== '') {
                $query['search'] = $search;
            }

            if ($status !== '') {
                $query['status'] = $status;
            }

            if ($sort !== '') {
                $query['sort'] = $sort;
            }

            $query['page'] = $pageNumber;

            return url(
                '/dashboard/mahasiswa/pengajuan?' .
                http_build_query($query)
            );
        };


        /*
         * ==========================================
         * 8. VIEW
         * ==========================================
         */

        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/pengajuan/index.php';
    }


    public function detail($params = [])
    {
        global $pengajuanData;

        $mahasiswaSlug = 'mahasiswa-demo';

        $slug = $params['slug'] ?? '';

        $pengajuanDetail = null;

        foreach ($pengajuanData as $item) {

            if (
                ($item['mahasiswaSlug'] ?? '') ===
                $mahasiswaSlug
                &&
                ($item['slug'] ?? '') ===
                $slug
            ) {
                $pengajuanDetail = $item;
                break;
            }
        }

        if (!$pengajuanDetail) {

            http_response_code(404);

            require __DIR__ .
                '/../../../pages/errors/404.php';

            exit;
        }

        require __DIR__ .
            '/../../../pages/dashboard/mahasiswa/pengajuan/detail.php';
    }
}