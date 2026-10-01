<?php

// Butuh:
// $total
// $page
// $perPage
// $urut

$dari = $total
    ? ($page - 1) * $perPage + 1
    : 0;

$sampai = min(
    $total,
    $page * $perPage
);

?>

<div
    class="flex flex-wrap
    items-center justify-between
    gap-3 text-sm"
>

    <!-- Informasi jumlah -->

    <p class="text-slate-700">

        Menampilkan
        <?= $dari ?>–<?= $sampai ?>
        dari
        <?= $total ?>
        mahasiswa

    </p>


    <!-- Sorting -->

    <form
        method="get"
        class="flex items-center gap-2
        text-slate-600"
    >

        <!-- Search -->

        <?php if (!empty($_GET['q'])): ?>

            <input
                type="hidden"
                name="q"
                value="<?= e($_GET['q']) ?>"
            >

        <?php endif; ?>


        <!-- Jurusan -->

        <?php if (!empty($_GET['jurusan'])): ?>

            <input
                type="hidden"
                name="jurusan"
                value="<?= e($_GET['jurusan']) ?>"
            >

        <?php endif; ?>


        <!-- Prodi -->

        <?php if (!empty($_GET['prodi'])): ?>

            <input
                type="hidden"
                name="prodi"
                value="<?= e($_GET['prodi']) ?>"
            >

        <?php endif; ?>


        <!-- Angkatan -->

        <?php if (!empty($_GET['angkatan'])): ?>

            <input
                type="hidden"
                name="angkatan"
                value="<?= e($_GET['angkatan']) ?>"
            >

        <?php endif; ?>


        <!-- Keahlian -->

        <?php if (!empty($_GET['keahlian'])): ?>

            <?php foreach (
                (array)$_GET['keahlian']
                as $skill
            ): ?>

                <input
                    type="hidden"
                    name="keahlian[]"
                    value="<?= e($skill) ?>"
                >

            <?php endforeach; ?>

        <?php endif; ?>


        <!-- Label -->

        <label for="urut">
            Urutkan:
        </label>


        <!-- Sort -->

        <select
            id="urut"
            name="urut"
            onchange="this.form.submit()"
            class="h-10 px-3 rounded-lg
            border border-slate-200
            bg-white
            focus:outline-none
            focus:ring-2
            focus:ring-blue-500"
        >

            <option
                value="terbaru"
                <?= $urut === 'terbaru'
                    ? 'selected'
                    : ''
                ?>
            >
                Terbaru
            </option>

            <option
                value="nama"
                <?= $urut === 'nama'
                    ? 'selected'
                    : ''
                ?>
            >
                Nama A–Z
            </option>

            <option
                value="proyek"
                <?= $urut === 'proyek'
                    ? 'selected'
                    : ''
                ?>
            >
                Proyek Terbanyak
            </option>

        </select>

    </form>

</div>