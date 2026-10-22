<?php

$items = is_array($items ?? null) ? $items : [];
$unreadCount = max(0, (int) ($unreadCount ?? 0));
$error = $error ?? null;

$internalTarget = static function (string $path): ?string {
    $path = trim($path);
    if (
        $path === ''
        || !str_starts_with($path, '/')
        || str_starts_with($path, '//')
        || str_contains($path, '\\')
        || preg_match('/[\x00-\x1F\x7F]/', $path)
    ) {
        return null;
    }
    $parts = parse_url($path);
    if ($parts === false || isset($parts['host']) || isset($parts['scheme'])) {
        return null;
    }
    return url($path);
};
?>

<main class="min-h-screen pt-20 bg-slate-50 lg:ml-64">
    <div class="max-w-5xl px-4 py-6 mx-auto sm:px-6 sm:py-8 lg:px-8">
        <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-sm font-medium text-blue-600">Pusat informasi</p>
                <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">Notifikasi</h1>
                <p class="mt-2 text-sm text-gray-500" aria-live="polite">
                    <?= $unreadCount ?> notifikasi belum dibaca
                </p>
                <p class="mt-1 text-xs text-gray-400">Menampilkan paling banyak 50 notifikasi terbaru.</p>
            </div>
            <?php if ($unreadCount > 0): ?>
                <form method="POST" action="<?= e(url('/dashboard/notifikasi')) ?>">
                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                    <input type="hidden" name="action" value="mark_all_read">
                    <button type="submit" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-gray-200 bg-white px-4 py-2 text-sm font-semibold text-gray-700 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        Tandai semua dibaca
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <?php if ($error): ?>
            <div class="mt-5 rounded-xl border border-red-200 bg-red-50 p-4 text-sm text-red-700" role="alert">
                <?= e($error) ?>
            </div>
        <?php endif; ?>

        <section class="mt-6 overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-sm" aria-label="Daftar notifikasi">
            <?php if (!$items): ?>
                <div class="px-6 py-14 text-center">
                    <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-blue-50 text-blue-600" aria-hidden="true">✓</div>
                    <h2 class="mt-4 text-base font-semibold text-gray-900">Belum ada notifikasi</h2>
                    <p class="mt-1 text-sm text-gray-500">Pembaruan proses magang akan ditampilkan di sini.</p>
                </div>
            <?php else: ?>
                <ul class="divide-y divide-gray-100">
                    <?php foreach ($items as $item): ?>
                        <?php
                        $target = $internalTarget((string) ($item['tautan'] ?? ''));
                        $isUnread = !in_array($item['sudah_dibaca'] ?? false, [true, 1, '1', 't', 'true'], true);
                        ?>
                        <li class="flex flex-col gap-3 p-5 sm:flex-row sm:items-start sm:justify-between <?= $isUnread ? 'bg-blue-50/40' : '' ?>">
                            <div class="flex min-w-0 gap-3">
                                <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full <?= $isUnread ? 'bg-blue-600' : 'bg-gray-300' ?>" aria-label="<?= $isUnread ? 'Belum dibaca' : 'Sudah dibaca' ?>"></span>
                                <div class="min-w-0">
                                    <h2 class="text-sm font-semibold text-gray-900"><?= e($item['judul'] ?? 'Notifikasi') ?></h2>
                                    <p class="mt-1 whitespace-pre-line break-words text-sm leading-6 text-gray-600"><?= e($item['pesan'] ?? '') ?></p>
                                    <time class="mt-2 block text-xs text-gray-400" datetime="<?= e($item['created_at'] ?? '') ?>"><?= e($item['created_at'] ?? '') ?></time>
                                    <?php if ($target !== null): ?>
                                        <a class="mt-3 inline-flex min-h-10 items-center text-sm font-semibold text-blue-700 underline-offset-4 hover:underline focus:outline-none focus:ring-2 focus:ring-blue-500" href="<?= e($target) ?>">Buka terkait</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if ($isUnread): ?>
                                <form method="POST" action="<?= e(url('/dashboard/notifikasi')) ?>" class="shrink-0">
                                    <input type="hidden" name="_csrf_token" value="<?= e(csrfToken()) ?>">
                                    <input type="hidden" name="action" value="mark_read">
                                    <input type="hidden" name="notification_id" value="<?= (int) ($item['id'] ?? 0) ?>">
                                    <button type="submit" class="inline-flex min-h-10 items-center rounded-lg px-3 text-sm font-medium text-gray-600 transition hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Tandai dibaca
                                    </button>
                                </form>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>
    </div>
</main>
