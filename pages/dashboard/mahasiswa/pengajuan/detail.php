<?php

$base = url('/');
$active = 'pengajuan';

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Head.php"; ?>

<body class="bg-slate-50 font-[Poppins] text-gray-900 overflow-x-hidden">

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Sidebar.php"; ?>

    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Header.php"; ?>

    <main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">

        <div
            class="w-full max-w-[1600px] px-4 py-5 mx-auto
                   sm:px-6 sm:py-6
                   lg:px-8 lg:py-8"
        >

            <!-- Header Detail -->
            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/detail/Header.php"; ?>


            <div class="grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">

                <!-- Informasi Pengajuan -->
                <div class="space-y-6">

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/detail/InformasiPengajuan.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/detail/InformasiPekerjaan.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/detail/Dokumen.php"; ?>

                </div>


                <!-- Status Pengajuan -->
                <aside>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/pengajuan/detail/StatusPengajuan.php"; ?>

                </aside>

            </div>


            <!-- Footer -->
            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Footer.php"; ?>

        </div>

    </main>

</body>

</html>