<?php

// Butuh: $total, $page, $perPage, $urut (dari Process.php)
$dari   = $total ? ($page - 1) * $perPage + 1 : 0;
$sampai = min($total, $page * $perPage);

// Parameter filter dibawa sebagai input tersembunyi; "page" sengaja tidak
// dibawa supaya ganti urutan kembali ke halaman 1.
$bawa = ['q', 'jurusan', 'prodi', 'angkatan', 'keahlian'];

?>

<div class="flex flex-wrap items-center justify-between gap-3 text-sm">

    <p class="text-slate-700">
        Menampilkan <?= $dari ?>–<?= $sampai ?> dari <?= $total ?> mahasiswa
    </p>

    <form method="get" class="flex items-center gap-2 text-slate-600">

        <?php foreach ($bawa as $k): ?>
            <?php if (($_GET[$k] ?? '') !== ''): ?>
                <input type="hidden" name="<?= $k ?>" value="<?= e($_GET[$k]) ?>">
            <?php endif; ?>
        <?php endforeach; ?>

        <label for="urut">Urutkan:</label>

        <select
            id="urut"
            name="urut"
            onchange="this.form.submit()"
            class="h-10 px-3 rounded-lg border border-slate-200 bg-white
            focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
            <option value="terbaru" <?= $urut === 'terbaru' ? 'selected' : '' ?>>Terbaru</option>
            <option value="nama" <?= $urut === 'nama' ? 'selected' : '' ?>>Nama A–Z</option>
            <option value="proyek" <?= $urut === 'proyek' ? 'selected' : '' ?>>Proyek Terbanyak</option>
        </select>

    </form>

</div>