<?php
$currentUri = $_SERVER['REQUEST_URI'] ?? '';
$isDashboard = str_contains($currentUri, '/citizen') && !str_contains($currentUri, '/profile') && !str_contains($currentUri, '/settings') && !str_contains($currentUri, '/notifications');
$isReports = str_contains($currentUri, '/citizen-reports');
$isProfile = str_contains($currentUri, '/citizen/profile');
$isSettings = str_contains($currentUri, '/citizen/settings');
$isNotifications = str_contains($currentUri, '/citizen/notifications');

$unreadCount = 0;
if (isset($user->id)) {
    $unreadCount = (new \App\Repository\NotificationRepository())->countUnreadByUser((int) $user->id);
}
?>
<div id="sidebar-overlay" class="fixed inset-0 bg-on-surface/20 backdrop-blur-sm z-40 hidden md:hidden transition-opacity"></div>
<aside id="citizen-sidebar" class="h-screen w-64 fixed left-0 top-0 bg-surface/80 backdrop-blur-xl flex flex-col py-8 px-4 shadow-[0_12px_32px_rgba(0,97,164,0.06)] z-50 transform -translate-x-full md:translate-x-0 transition-transform duration-300 ease-in-out border-r border-outline-variant/10">
    <div class="mb-10 px-2 flex justify-between items-center">
        <div>
            <h1 class="text-xl font-headline font-bold tracking-tight text-on-surface">Dash Cam</h1>
            <p class="text-xs text-on-surface-variant uppercase tracking-widest mt-1">Citizen Portal</p>
        </div>
        <button id="close-sidebar-btn" type="button" class="md:hidden flex items-center justify-center w-8 h-8 rounded-full text-on-surface-variant hover:bg-surface-container transition-colors">
            <span class="material-symbols-outlined text-[20px]">close</span>
        </button>
    </div>
    <nav class="flex-1 space-y-1 overflow-y-auto mt-2">
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?= $isDashboard ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:bg-surface-container-low' ?>" href="<?= url('app_citizen') ?>">
            <span class="material-symbols-outlined">dashboard</span>
            <span class="font-medium font-body">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?= $isReports ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:bg-surface-container-low' ?>" href="<?= url('app_citizen_reports') ?>">
            <span class="material-symbols-outlined">campaign</span>
            <span class="font-medium font-body">Citizen Reports</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?= $isNotifications ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:bg-surface-container-low' ?>" href="<?= url('app_citizen_notifications') ?>">
            <div class="relative">
                <span class="material-symbols-outlined">notifications</span>
                <?php if ($unreadCount > 0): ?>
                    <span class="absolute -top-1.5 -right-1.5 w-4 h-4 bg-error text-white font-extrabold text-[9px] rounded-full flex items-center justify-center animate-pulse"><?= $unreadCount ?></span>
                <?php endif; ?>
            </div>
            <span class="font-medium font-body">Notifications</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?= $isProfile ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:bg-surface-container-low' ?>" href="<?= url('app_citizen_profile') ?>">
            <span class="material-symbols-outlined">person</span>
            <span class="font-medium font-body">Profile Details</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all group <?= $isSettings ? 'text-primary font-bold border-r-4 border-primary bg-surface-container-low' : 'text-on-surface-variant hover:bg-surface-container-low' ?>" href="<?= url('app_citizen_settings') ?>">
            <span class="material-symbols-outlined">settings</span>
            <span class="font-medium font-body">Settings</span>
        </a>
    </nav>
    <a class="mt-4 mb-8 bg-gradient-to-r from-primary to-primary-container text-white py-3 px-4 rounded-xl font-bold flex items-center justify-center gap-2 shadow-[0_10px_20px_rgba(0,97,164,0.2)] active:scale-95 transition-transform" href="<?= url('app_citizen_reports') ?>">
        <span class="material-symbols-outlined">add</span>
        New Report
    </a>
    <div class="pt-6 border-t border-outline-variant/10 space-y-1">
        <div class="flex items-center gap-3 px-2 py-2 mb-4">
            <div class="w-10 h-10 rounded-full overflow-hidden bg-surface-container-high border flex items-center justify-center">
                <?php if (!empty($user->profilePic)): ?>
                    <img src="<?= e($user->profilePic) ?>" alt="Profile Photo" class="w-full h-full object-cover" />
                <?php else: ?>
                    <span class="material-symbols-outlined text-on-surface-variant">person</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-bold text-on-surface truncate"><?= e($user->fullName ?? 'Citizen') ?></p>
                <p class="text-xs text-on-surface-variant truncate">NIC: <?= e((string) ($user->NIC ?? '—')) ?></p>
            </div>
        </div>
        <a class="flex items-center gap-3 px-4 py-3 rounded-xl text-error hover:bg-error-container transition-colors group" href="<?= url('app_logout') ?>">
            <span class="material-symbols-outlined text-error">logout</span>
            <span class="font-medium font-body text-error">Logout</span>
        </a>
    </div>
</aside>
