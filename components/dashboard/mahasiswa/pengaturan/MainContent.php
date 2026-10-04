<div class="mx-auto max-w-7xl p-4 sm:p-6 lg:p-8">

    <!-- Page Header -->

    <div class="mb-7">

        <h1
            class="text-2xl font-bold tracking-tight
                   text-slate-950 sm:text-3xl"
        >
            Pengaturan
        </h1>

        <p
            class="mt-1 text-sm text-slate-500
                   sm:text-base"
        >
            Kelola preferensi keamanan dan tampilan aplikasi.
        </p>

    </div>


    <!-- Keamanan -->

    <?php
    include __DIR__ . '/Keamanan.php';
    ?>


    <!-- Tampilan -->

    <?php
    include __DIR__ . '/Tampilan.php';
    ?>

</div>