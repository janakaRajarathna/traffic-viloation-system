<?php $bodyClass = 'bg-background font-body text-on-surface antialiased min-h-screen'; ?>
<style>
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
        <span class="text-xl font-extrabold tracking-tight text-on-surface font-headline">Profile Details</span>
    </div>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-5xl mx-auto space-y-8 flex-1 transition-all">
    <div class="space-y-6">
        <div class="glass-panel p-8 rounded-xl shadow-lg flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
            <div class="w-24 h-24 rounded-full overflow-hidden bg-primary/10 flex items-center justify-center text-primary border-4 border-white shadow-md relative z-10">
                <?php if ($user->profilePic): ?>
                    <img src="<?= e($user->profilePic) ?>" alt="Profile Picture" class="w-full h-full object-cover" />
                <?php else: ?>
                    <span class="material-symbols-outlined text-5xl" data-icon="person">person</span>
                <?php endif; ?>
            </div>
            <div class="flex-1 text-center md:text-left relative z-10">
                <h1 class="font-headline font-extrabold text-3xl text-on-surface"><?= e($user->fullName) ?></h1>
                <p class="text-on-surface-variant font-medium"><?= e(ucfirst((string) $user->role)) ?> Portfolio</p>
                <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-4">
                    <span class="px-3 py-1 bg-surface-container-high rounded-full text-xs font-bold text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" data-icon="id_card">id_card</span>
                        NIC: <?= e((string) $user->NIC) ?>
                    </span>
                    <span class="px-3 py-1 bg-surface-container-high rounded-full text-xs font-bold text-on-surface-variant flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm" data-icon="call">call</span>
                        <?= e(str_pad((string) $user->telNo, 10, '0', STR_PAD_LEFT)) ?>
                    </span>
                </div>
            </div>
            <a href="<?= url('app_citizen_settings') ?>" class="px-6 py-3 bg-surface-container-high hover:bg-surface-container-highest rounded-xl text-sm font-bold transition-all flex items-center gap-2">
                <span class="material-symbols-outlined text-lg" data-icon="edit">edit</span>
                Edit Profile
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm space-y-4">
                <h3 class="font-headline font-bold text-lg border-b border-surface pb-3">Legal Credentials</h3>
                <div class="space-y-4">
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant text-sm font-medium">Driving License</span>
                        <span class="font-bold text-on-surface"><?= e($user->licenceNo !== null ? (string) $user->licenceNo : 'Not Provided') ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant text-sm font-medium">Registration Date</span>
                        <span class="font-bold text-on-surface"><?= $user->createdAt ? e($user->createdAt->format('M d, Y')) : 'N/A' ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-on-surface-variant text-sm font-medium">Account Status</span>
                        <span class="px-2 py-0.5 bg-green-100 text-green-800 rounded text-[10px] font-black uppercase tracking-tighter">Verified</span>
                    </div>
                </div>
            </div>

            <div class="bg-surface-container-lowest p-6 rounded-xl shadow-sm space-y-4">
                <h3 class="font-headline font-bold text-lg border-b border-surface pb-3">Vehicle Assets</h3>
                <div class="flex flex-col items-center justify-center py-4 text-center">
                    <?php if (!empty($user->vehicles)): ?>
                        <div class="w-full space-y-3">
                            <?php foreach ($user->vehicles as $vehicle): ?>
                                <div class="flex items-center gap-3 p-3 bg-surface-container-low rounded-lg">
                                    <div class="w-10 h-10 bg-primary/5 rounded flex items-center justify-center text-primary">
                                        <span class="material-symbols-outlined" data-icon="directions_car">directions_car</span>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-bold text-on-surface"><?= e($vehicle->vehicleNo) ?></p>
                                        <p class="text-xs text-on-surface-variant"><?= e($vehicle->model) ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php else: ?>
                        <span class="material-symbols-outlined text-4xl text-outline-variant mb-2" data-icon="car_tag">car_tag</span>
                        <p class="text-on-surface-variant text-sm font-medium">No vehicles registered under this account.</p>
                        <a href="#" class="text-primary text-xs font-bold mt-2 hover:underline">Register a vehicle</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("citizen-sidebar");
        const openBtn = document.getElementById("open-sidebar-btn");
        const closeBtn = document.getElementById("close-sidebar-btn");
        const overlay = document.getElementById("sidebar-overlay");

        if (openBtn) openBtn.addEventListener("click", () => {
            sidebar.classList.remove("-translate-x-full");
            overlay.classList.remove("hidden");
        });
        if (closeBtn) closeBtn.addEventListener("click", () => {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        });
        if (overlay) overlay.addEventListener("click", () => {
            sidebar.classList.add("-translate-x-full");
            overlay.classList.add("hidden");
        });
    });
</script>
