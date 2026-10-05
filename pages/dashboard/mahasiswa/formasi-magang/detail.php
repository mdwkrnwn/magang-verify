<?php

$base = url('/');

$active = 'formasi-magang';

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
                   lg:px-8 lg:py-8">

            <!-- Header Formasi -->
            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Header.php"; ?>


            <!-- Content -->
            <div
                class="grid grid-cols-1 gap-5
                       lg:grid-cols-[minmax(0,1fr)_320px]
                       lg:items-start">

                <!-- Main Content -->
                <div class="min-w-0 space-y-5">

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Deskripsi.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Keahlian.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Persyaratan.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Alur.php"; ?>

                </div>


                <!-- Sidebar -->
                <aside
                    class="min-w-0 space-y-5">

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Summary.php"; ?>

                    <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/formasi-magang/detail/Sidebar.php"; ?>

                </aside>

            </div>

            <?php include __DIR__ . "/../../../../components/dashboard/mahasiswa/Footer.php"; ?>

        </div>

    </main>

</body>

</html>