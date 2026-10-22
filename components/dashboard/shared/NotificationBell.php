<?php

$hasDashboardCount = isset($dashboard) && is_array($dashboard)
    && array_key_exists('notifikasi_belum_dibaca', $dashboard);
$unreadNotifications = isset($unreadCount)
    ? max(0, (int) $unreadCount)
    : ($hasDashboardCount ? max(0, (int) $dashboard['notifikasi_belum_dibaca']) : 0);

if (!isset($unreadCount) && !$hasDashboardCount && isset($_SESSION['user']['id'])) {
    try {
        require_once __DIR__ . '/../../../models/Notifikasi.php';
        $notificationModel = new Notifikasi();
        $unreadNotifications = $notificationModel->countUnread((int) $_SESSION['user']['id']);
    } catch (Throwable $exception) {
        error_log('Unable to load unread notification count for header.');
    }
}

$notificationLabel = $unreadNotifications > 0
    ? 'Notifikasi, ' . $unreadNotifications . ' belum dibaca'
    : 'Notifikasi';
?>
<a
    href="<?= e(url('/dashboard/notifikasi')) ?>"
    aria-label="<?= e($notificationLabel) ?>"
    class="relative flex h-11 w-11 shrink-0 items-center justify-center rounded-xl border border-gray-200 bg-white text-gray-500 transition hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
    </svg>
    <?php if ($unreadNotifications > 0): ?>
        <span class="absolute right-1 top-1 flex min-w-5 h-5 items-center justify-center rounded-full bg-red-500 px-1 text-[10px] font-bold text-white"><?= e($unreadNotifications > 9 ? '9+' : (string) $unreadNotifications) ?></span>
    <?php endif; ?>
</a>
