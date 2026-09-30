<footer class="bg-white border-t border-slate-100 ">
    <div class="max-w-7xl mx-auto px-6 py-10 grid gap-8 md:grid-cols-2 lg:grid-cols-[1.2fr_1fr_1.5fr_1.2fr]">

        <!-- Brand -->
        <div class="min-w-0">
            <img
                src="<?= url('/assets/images/logo.png') ?>"
                alt="VerifyMagang"
                class="h-10 w-auto"
            >

            <p class="text-xs text-slate-500 mt-2">
                Portofolio Terverifikasi, Masa Depan Lebih Dekat
            </p>

            <div class="flex gap-4 mt-5 text-slate-800">
                <a href="#" aria-label="Instagram">
                    <?= icon('instagram') ?>
                </a>

                <a href="#" aria-label="LinkedIn">
                    <?= icon('briefcase') ?>
                </a>

                <a href="#" aria-label="YouTube">
                    <?= icon('youtube') ?>
                </a>
            </div>
        </div>


        <!-- Navigasi -->
        <div class="min-w-0">
            <h4 class="text-sm font-semibold text-slate-900 mb-3">
                Navigasi
            </h4>

            <ul class="space-y-2 text-sm text-slate-600">

                <li>
                    <a
                        class="hover:text-blue-600"
                        href="<?= url('/') ?>"
                    >
                        Beranda
                    </a>
                </li>

                <li>
                    <a
                        class="hover:text-blue-600"
                        href="<?= url('/mahasiswa') ?>"
                    >
                        Daftar Mahasiswa
                    </a>
                </li>

                <li>
                    <a
                        class="hover:text-blue-600"
                        href="<?= url('/mitra') ?>"
                    >
                        Daftar Mitra
                    </a>
                </li>

                <li>
                    <a
                        class="hover:text-blue-600"
                        href="<?= url('/tentang') ?>"
                    >
                        Tentang
                    </a>
                </li>

            </ul>
        </div>


        <!-- Kontak -->
        <div class="min-w-0">
            <h4 class="text-sm font-semibold text-slate-900 mb-3">
                Kontak
            </h4>

            <ul class="space-y-3 text-sm text-slate-600">

                <li class="flex gap-2 items-center">
                    <span class="text-blue-600">
                        <?= icon('mail', 'w-4 h-4') ?>
                    </span>

                    verifymagang@polinema.ac.id
                </li>

                <li class="flex gap-2">
                    <span class="text-blue-600 mt-0.5">
                        <?= icon('pin', 'w-4 h-4') ?>
                    </span>

                    <span>
                        Politeknik Negeri Malang<br>
                        Jl. Soekarno Hatta No. 9, Malang, Jawa Timur
                    </span>
                </li>

            </ul>
        </div>


        <!-- Quote -->
        <p class="text-xs text-slate-500 border-l-2 border-blue-200 pl-3 self-start">
            “Membangun Talenta, Menghubungkan Masa Depan”
        </p>

    </div>


    <!-- Copyright -->
    <div class="border-t border-slate-100">
        <div class="max-w-7xl mx-auto px-6 py-4 flex flex-col items-center justify-center gap-2 text-xs text-slate-500 text-center">

            <span>
                © <?= date('Y') ?> VerifyMagang. All rights reserved.
            </span>

            <span class="flex items-center justify-center gap-5">

                <a
                    href="#"
                    class="hover:text-blue-600"
                >
                    Privasi
                </a>

                <a
                    href="#"
                    class="hover:text-blue-600"
                >
                    Syarat dan Ketentuan
                </a>

            </span>

        </div>
    </div>

</footer>