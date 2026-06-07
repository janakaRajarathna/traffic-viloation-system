<?php $bodyClass = 'bg-background font-body text-on-surface antialiased min-h-screen'; ?>

<?php require APP_ROOT . 'views/partials/citizen_sidebar.php'; ?>

<header class="fixed top-0 w-full md:w-[calc(100%-16rem)] md:right-0 z-40 bg-surface/80 backdrop-blur-xl flex justify-between items-center px-6 h-16 border-b border-surface-container-highest transition-all">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
        <span class="text-xl font-extrabold tracking-tight text-on-surface font-headline">Account Settings</span>
    </div>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-4xl mx-auto space-y-8 flex-1 transition-all">
    <div class="space-y-10">
        <section class="space-y-4">
            <div class="px-2">
                <h2 class="font-headline font-extrabold text-2xl text-on-surface">Personal Information</h2>
                <p class="text-on-surface-variant text-sm font-medium">Update your public profile and contact details.</p>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm">
                <form class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Full Name</label>
                            <input type="text" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e($user->fullName) ?>">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Telephone Number</label>
                            <input type="tel" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e((string) $user->telNo) ?>">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">National ID Card</label>
                            <input type="text" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium opacity-60 cursor-not-allowed" value="<?= e((string) $user->NIC) ?>" readonly>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">License Number</label>
                            <input type="text" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e($user->licenceNo !== null ? (string) $user->licenceNo : '') ?>">
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="bg-primary text-white px-8 py-3 rounded-lg font-bold shadow-lg shadow-primary/20 hover:scale-[1.02] active:scale-95 transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </section>

        <section class="space-y-4">
            <div class="px-2">
                <h2 class="font-headline font-extrabold text-2xl text-on-surface">Security & Privacy</h2>
                <p class="text-on-surface-variant text-sm font-medium">Manage your password and account security.</p>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm">
                <form class="space-y-6">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Current Password</label>
                        <input type="password" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium">
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">New Password</label>
                            <input type="password" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium">
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Confirm New Password</label>
                            <input type="password" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium">
                        </div>
                    </div>
                    <div class="flex justify-end pt-4">
                        <button type="submit" class="border-2 border-primary text-primary px-8 py-3 rounded-lg font-bold hover:bg-primary/5 active:scale-95 transition-all">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </section>
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
