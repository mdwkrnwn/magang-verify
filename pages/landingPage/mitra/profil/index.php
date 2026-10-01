<?php

$active = "mitra";

require_once __DIR__ . "/../../../../data/landingPage/Mitra.php";

$slug = $params['slug'] ?? '';
$m = null;

foreach ($mitra as $row) {
    if ($row['slug'] === $slug) {
        $m = $row;
        break;
    }
}

if (!$m) {
    http_response_code(404);
}

?>

<!DOCTYPE html>
<html lang="id">

<?php include __DIR__ . "/../../../../components/landingPage/Head.php"; ?>

<body class="bg-slate-50/60 font-[Poppins] pt-20 text-gray-900">

<?php include __DIR__ . "/../../../../components/landingPage/Navbar.php"; ?>

<?php if ($m): ?>

    <div class="mb-6">
        <?php include __DIR__ . "/../../../../components/landingPage/mitra/Detail.php"; ?>
    </div>

<?php else: ?>

    <main class="max-w-xl mx-auto px-6 py-24 text-center">
        <h1 class="text-2xl font-bold text-slate-900">
            Mitra tidak ditemukan
        </h1>

        <p class="mt-3 text-sm text-slate-500">
            Alamat yang Anda buka tidak cocok dengan mitra mana pun di direktori.
        </p>

        <a
            href="<?= url('/mitra') ?>"
            class="inline-flex mt-6 px-5 py-2.5 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700 transition"
        >
            Kembali ke Daftar Mitra
        </a>
    </main>

<?php endif; ?>

<?php include __DIR__ . "/../../../../components/landingPage/Footer.php"; ?>

<?php include __DIR__ . '/../../../../components/landingPage/Script.php'; ?>

</body>
</html>