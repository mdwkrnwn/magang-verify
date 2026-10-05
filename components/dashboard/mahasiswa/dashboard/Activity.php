<?php
/** @var array $dashboard */
$aktivitas = $dashboard['aktivitas'] ?? [];

$icon = static function (string $module): string {
    return match ($module) {
        'portofolio' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3.75 7.5h16.5v11.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V7.5Zm4.5 0V5.25a1.5 1.5 0 0 1 1.5-1.5h4.5a1.5 1.5 0 0 1 1.5 1.5V7.5"/></svg>',
        'sertifikat' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m12 3.75 2.18 4.42 4.88.71-3.53 3.44.83 4.86L12 14.88l-4.36 2.3.83-4.86-3.53-3.44 4.88-.71L12 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="m9.5 15.5-.75 4.5L12 18.25 15.25 20l-.75-4.5"/></svg>',
        'pengajuan', 'pendaftaran' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3.75h9l3 3v13.5H6a1.5 1.5 0 0 1-1.5-1.5V5.25A1.5 1.5 0 0 1 6 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3.75v3h3M8.5 11h7M8.5 14.5h7"/></svg>',
        'logbook' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 3.75h9l3 3v13.5H6a1.5 1.5 0 0 1-1.5-1.5V5.25A1.5 1.5 0 0 1 6 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15 3.75v3h3M8.5 11h7M8.5 14.5h7M8.5 18h4"/></svg>',
        'profil' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 7.5a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.25a7.5 7.5 0 0 1 15 0"/></svg>',
        'pengaturan', 'auth' => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3.75a2.25 2.25 0 0 1 2.25 2.25v.43a6.75 6.75 0 0 1 1.8 1.04l.38-.22a2.25 2.25 0 1 1 2.25 3.9l-.37.22c.1.64.1 1.3 0 1.94l.37.22a2.25 2.25 0 1 1-2.25 3.9l-.38-.22a6.75 6.75 0 0 1-1.8 1.04v.43a2.25 2.25 0 1 1-4.5 0v-.43a6.75 6.75 0 0 1-1.8-1.04l-.38.22a2.25 2.25 0 1 1-2.25-3.9l.37-.22a6.75 6.75 0 0 1 0-1.94l-.37-.22a2.25 2.25 0 1 1 2.25-3.9l.38.22a6.75 6.75 0 0 1 1.8-1.04V6A2.25 2.25 0 0 1 12 3.75Z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 15.25a3.25 3.25 0 1 0 0-6.5 3.25 3.25 0 0 0 0 6.5Z"/></svg>',
        default => '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 3.75v16.5M3.75 12h16.5"/></svg>',
    };
};
?>

<section class="p-5 bg-white border border-gray-100 shadow-sm sm:p-7 rounded-2xl">
    <div class="flex items-start justify-between gap-3 mb-6">
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h2>
            <p class="mt-1 text-sm text-gray-500">Riwayat aktivitas akun Anda.</p>
        </div>
    </div>

    <?php if ($aktivitas): ?>
        <div class="space-y-5">
            <?php foreach ($aktivitas as $item): ?>
                <div class="flex gap-3.5">
                    <div class="flex items-center justify-center w-10 h-10 text-blue-600 bg-blue-50 rounded-full shrink-0">
                        <?= $icon((string) ($item['modul'] ?? '')) ?>
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-medium text-gray-800 break-words"><?= e($item['aksi']) ?></p>
                        <p class="mt-0.5 text-sm text-gray-500 break-words"><?= e($item['deskripsi']) ?></p>
                        <p class="mt-1 text-xs text-gray-400"><?= e($item['waktu_label']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="p-5 text-center border border-dashed border-gray-200 rounded-xl">
            <p class="text-sm font-medium text-gray-700">Belum ada aktivitas.</p>
            <p class="mt-1 text-sm text-gray-400">Aktivitas Anda akan muncul setelah melakukan tindakan di sistem.</p>
        </div>
    <?php endif; ?>
</section>
