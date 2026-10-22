<div>

    <!-- Header -->
    <?php include __DIR__ . "/Header.php"; ?>


    <!-- Main Layout -->
    <div
        class="grid grid-cols-1 gap-5
               lg:grid-cols-[minmax(0,1fr)_320px]
               lg:items-start"
    >

        <!-- Left -->
        <div class="min-w-0 space-y-5">

            <?php include __DIR__ . "/FormasiInfo.php"; ?>

            <?php include __DIR__ . "/DataPengajuan.php"; ?>

            <?php include __DIR__ . "/Dokumen.php"; ?>

            <?php include __DIR__ . "/Pernyataan.php"; ?>

            <?php include __DIR__ . "/Action.php"; ?>

        </div>


        <!-- Right -->
        <aside class="min-w-0">

            <div
                class="lg:sticky lg:top-24"
            >

                <?php include __DIR__ . '/Alur.php'; ?>

            </div>

        </aside>

    </div>

</div>