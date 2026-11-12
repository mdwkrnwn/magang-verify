<div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

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