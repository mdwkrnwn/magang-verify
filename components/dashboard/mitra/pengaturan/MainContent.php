<?php

$success = $success ?? null;

?>

<section class="px-4 py-6 sm:px-6 lg:px-8">

    <div class="mx-auto max-w-8xl space-y-6">

        <!-- Heading -->
        <div>
            <div class="flex items-center gap-2 text-sm text-slate-500">
                <span>Dashboard</span>
                <span>/</span>
                <span class="font-medium text-blue-600">
                    Pengaturan
                </span>
            </div>

            <h1 class="mt-3 text-2xl font-bold text-slate-800">
                Pengaturan Tampilan
            </h1>

            <p class="mt-1 text-sm text-slate-500">
                Sesuaikan tampilan dashboard sesuai preferensi Anda.
            </p>
        </div>

        <!-- Success Notification -->
        <?php if ($success): ?>
            <div class="flex items-start gap-3 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-emerald-800">

                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    class="mt-0.5 h-5 w-5 shrink-0"
                    viewBox="0 0 24 24"
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    aria-hidden="true">

                    <circle cx="12" cy="12" r="10" />
                    <path d="m9 12 2 2 4-4" />

                </svg>

                <p class="text-sm font-medium">
                    <?= e($success) ?>
                </p>

            </div>
        <?php endif; ?>

        <!-- Theme Settings -->
        <?php include __DIR__ . '/Tampilan.php'; ?>

    </div>

</section>