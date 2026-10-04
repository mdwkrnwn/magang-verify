<?php

$base = "/";
$active = "mahasiswa";

$kontak = $m['kontak'] ?? [];

$menu = [
    ['profil', 'Profil', 'user'],
    ['portofolio', 'Portofolio', 'folder'],
    ['sertifikat', 'Sertifikat', 'bolt'],
    ['proyek', 'Proyek', 'layers'],
    ['pengalaman', 'Pengalaman', 'briefcase'],
    ['kontak', 'Kontak', 'mail'],
];

$card = 'bg-white rounded-2xl border border-slate-100 shadow-sm p-4 sm:p-5 md:p-6 scroll-mt-24';

function judul(string $ic, string $t): void
{
    echo '<h2 class="flex items-center gap-3 font-semibold text-slate-900 mb-3">
            <span class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 grid place-items-center">'
        . icon($ic, 'w-4 h-4') .
        '</span>'
        . $t .
        '</h2>';
}

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . '/../../../../components/landingPage/Head.php'; ?>

<body class="bg-slate-50/60 font-[Poppins] pt-20 text-gray-900">

    <?php include __DIR__ . '/../../../../components/landingPage/Navbar.php'; ?>


    <div class="max-w-7xl mx-auto px-4 sm:px-6 py-4 sm:px-6">

        <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Breadcrumb.php'; ?>

        <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Hero.php'; ?>


        <div
            id="profil-content-area"
            class="relative mt-5 sm:mt-6 grid gap-4 sm:gap-6 lg:grid-cols-[210px_minmax(0,1fr)_290px] items-start">

            <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Sidebar.php'; ?>


            <main class="space-y-4 min-w-0">

                <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Tentang.php'; ?>

                <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Keahlian.php'; ?>

                <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Pengalaman.php'; ?>

                <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Proyek.php'; ?>

                <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Sertifikat.php'; ?>

                <?php if (empty($m['tentang'])): ?>

                    <p class="text-sm text-slate-500 text-center py-6">
                        Profil lengkap mahasiswa ini belum diisi.
                    </p>

                <?php endif; ?>

            </main>


            <?php include __DIR__ . '/../../../../components/landingPage/mahasiswa/profil/Kontak.php'; ?>

        </div>

    </div>


    <?php include __DIR__ . '/../../../../components/landingPage/Footer.php'; ?>

    <?php include __DIR__ . '/../../../../components/landingPage/Script.php'; ?>

</body>

</html>