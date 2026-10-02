<?php
// Halaman profil mahasiswa.
?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="mx-auto max-w-[1600px] px-4 py-6 sm:px-6 sm:py-8 lg:px-8">

        <!-- Header halaman -->
        <div class="mb-7">
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 sm:text-3xl">
                Profil Saya
            </h1>
            <p class="mt-2 text-sm text-gray-500 sm:text-base">
                Kelola informasi pribadi dan profil profesional Anda.
            </p>
        </div>

        <!-- Baris pertama: profil, data diri, dan kontak -->
        <div class="grid grid-cols-1 items-stretch gap-5 lg:grid-cols-12 lg:gap-6">
            <!-- Card kiri -->
            <div class="min-w-0 h-full lg:col-span-4">
                <?php include __DIR__ . '/profile/ProfileCard.php'; ?>
            </div>

            <!-- Card kanan -->
            <div class="min-w-0 h-full lg:col-span-8">
                <div class="h-full min-w-0 rounded-2xl border border-gray-100 bg-white p-5 shadow-sm sm:p-6 lg:p-7">
                    <?php include __DIR__ . '/personal/PersonalInfo.php'; ?>

                    <div class="my-6 border-t border-gray-100 sm:my-7"></div>

                    <?php include __DIR__ . '/personal/ContactInfo.php'; ?>
                </div>
            </div>

            <!-- Bio tetap di bawah kedua card -->
            <div class="min-w-0 lg:col-span-12">
                <?php include __DIR__ . '/bio/BioCard.php'; ?>
            </div>
        </div>

        <!-- Modal edit -->
        <?php include __DIR__ . '/forms/EditPersonalForm.php'; ?>
        <?php include __DIR__ . '/forms/EditContactForm.php'; ?>
        <?php include __DIR__ . '/forms/EditBioForm.php'; ?>
        <?php include __DIR__ . '/forms/EditPhotoForm.php'; ?>

        <!-- Footer -->
        <?php include __DIR__ . '/../Footer.php'; ?>

    </div>
</main>

<script src="<?= e(url('/assets/js/profil-mahasiswa.js')) ?>" defer></script>