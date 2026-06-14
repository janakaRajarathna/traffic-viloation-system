<?php $bodyClass = 'bg-background font-body text-on-surface antialiased min-h-screen'; ?>
<style>
.material-symbols-outlined {
    font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
}
.glass-panel {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
</style>

<?php require APP_ROOT . 'views/partials/citizen_sidebar.php'; ?>

<header class="fixed top-0 w-full md:w-[calc(100%-16rem)] md:right-0 z-40 bg-surface/80 backdrop-blur-xl flex justify-between items-center px-6 h-16 border-b border-surface-container-highest transition-all">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
        <span class="text-xl font-extrabold tracking-tight text-primary font-headline md:hidden">Dash Cam</span>
        <span class="text-xl font-extrabold tracking-tight text-on-surface font-headline hidden md:block">Notifications</span>
    </div>
    <div class="flex items-center gap-4">
        <a href="<?= url('app_logout') ?>" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-error-container transition-colors rounded-full active:scale-95 duration-150">
            <span class="material-symbols-outlined" data-icon="logout">logout</span>
        </a>
    </div>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-4xl mx-auto space-y-8 flex-1 transition-all">
    <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

    <section class="space-y-2">
        <h1 class="font-headline font-extrabold text-on-surface text-4xl tracking-tight">Notification Center</h1>
        <p class="text-on-surface-variant font-medium">Keep track of your submitted reports and issued citations.</p>
    </section>

    <div class="bg-surface-container-lowest rounded-2xl p-6 md:p-8 shadow-[0_12px_32px_rgba(0,97,164,0.04)] border border-outline-variant/10">
        <div class="divide-y divide-outline-variant/10">
            <?php if (empty($notifications)): ?>
                <div class="py-12 text-center text-on-surface-variant">
                    <span class="material-symbols-outlined text-5xl opacity-40 mb-3 block">notifications_off</span>
                    <p class="font-semibold text-lg">No notifications yet</p>
                    <p class="text-sm mt-1">You will receive notifications here when your reports are updated or citations are issued.</p>
                </div>
            <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                    <?php 
                        $isFine = str_contains(strtolower((string) $notification->title), 'fine') || str_contains(strtolower((string) $notification->title), 'citation');
                        $icon = $isFine ? 'gavel' : 'campaign';
                        $iconClass = $isFine ? 'bg-error/10 text-error' : 'bg-primary/10 text-primary';
                    ?>
                    <div class="py-6 flex gap-4 first:pt-0 last:pb-0 items-start hover:bg-surface-container-low/30 px-4 -mx-4 rounded-xl transition-colors">
                        <div class="p-3 rounded-xl <?= $iconClass ?> shrink-0 flex items-center justify-center">
                            <span class="material-symbols-outlined"><?= $icon ?></span>
                        </div>
                        <div class="flex-1 space-y-1">
                            <div class="flex justify-between items-start gap-4">
                                <h4 class="font-bold text-on-surface text-base md:text-lg"><?= e($notification->title) ?></h4>
                                <span class="text-xs text-on-surface-variant shrink-0 mt-1"><?= $notification->createdAt ? e($notification->createdAt->format('M d, Y h:i A')) : '—' ?></span>
                            </div>
                            <p class="text-on-surface-variant text-sm md:text-base leading-relaxed"><?= e($notification->message) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("citizen-sidebar");
        const overlay = document.getElementById("sidebar-overlay");
        const openBtn = document.getElementById("open-sidebar-btn");
        const closeBtn = document.getElementById("close-sidebar-btn");

        function openSidebar() {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        }

        function closeSidebar() {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        }

        if (openBtn) openBtn.addEventListener("click", openSidebar);
        if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
        if (overlay) overlay.addEventListener("click", closeSidebar);
    });
</script>
