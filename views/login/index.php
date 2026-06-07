<?php
$bodyClass = 'bg-surface font-body text-on-surface min-h-screen flex flex-col antialiased bg-login-gradient';
?>
<div class="fixed inset-0 pointer-events-none overflow-hidden opacity-40">
    <div class="absolute -top-[20%] -left-[10%] w-[60%] h-[60%] rounded-full bg-primary-fixed-dim blur-[120px] opacity-20"></div>
</div>
<main class="flex-grow flex items-center justify-center p-6 relative z-10">
    <div class="w-full max-w-[480px] bg-surface-container-lowest rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.06)] overflow-hidden">
        <div class="px-10 pt-12 pb-6 text-center">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-xl bg-primary-container/10 mb-6">
                <span class="material-symbols-outlined text-primary text-3xl">gavel</span>
            </div>
            <h1 class="font-headline text-2xl font-extrabold tracking-tight text-on-surface mb-2">Dash Cam</h1>
            <p class="font-body text-on-surface-variant text-sm">Enforcement Management Portal</p>
        </div>
        <form action="<?= url('app_login') ?>" method="POST" class="px-10 pb-12 space-y-6">
            <?php require APP_ROOT . 'views/partials/flashes.php'; ?>
            <div class="space-y-3">
                <label class="font-label text-xs font-semibold text-on-surface-variant uppercase tracking-wider">Sign in as</label>
                <input type="hidden" name="role" id="selected_role" value="admin">
                <div class="grid grid-cols-2 gap-2" id="role_buttons">
                    <?php foreach (['admin', 'police', 'driver', 'citizen'] as $role): ?>
                    <button data-role="<?= e($role) ?>" class="role-btn flex items-center gap-3 px-4 py-3 rounded-lg bg-surface-container-low text-on-surface-variant border-2 border-transparent hover:bg-surface-container-high transition-all" type="button" onclick="selectRole('<?= e($role) ?>', this)">
                        <span class="text-sm capitalize"><?= e($role) ?></span>
                    </button>
                    <?php endforeach; ?>
                </div>
            </div>
            <script>
                function selectRole(role, element) {
                    document.getElementById('selected_role').value = role;
                    document.querySelectorAll('.role-btn').forEach(btn => {
                        btn.classList.remove('text-primary', 'font-bold', 'border-primary');
                        btn.classList.add('text-on-surface-variant', 'border-transparent');
                    });
                    element.classList.remove('text-on-surface-variant', 'border-transparent');
                    element.classList.add('text-primary', 'font-bold', 'border-primary');
                }
                document.querySelector('[data-role="admin"]')?.click();
            </script>
            <div class="space-y-4">
                <div class="space-y-1.5">
                    <label class="font-label text-sm font-medium text-on-surface" for="NIC">National Identity Card (NIC)</label>
                    <input class="w-full pl-11 pr-4 py-3 bg-surface-container-highest border-none rounded-lg focus:ring-2 focus:ring-primary/20 outline-none" id="nic" name="NIC" placeholder="e.g. 199012345678" type="text" required />
                </div>
                <div class="space-y-1.5">
                    <label class="font-label text-sm font-medium text-on-surface" for="password">Password</label>
                    <input class="w-full px-4 py-3 bg-surface-container-highest border-none rounded-lg focus:ring-2 focus:ring-primary/20 outline-none" id="password" name="password" type="password" required />
                </div>
            </div>
            <button class="w-full primary-cta-gradient text-on-primary font-headline font-bold py-4 rounded-xl shadow-lg flex items-center justify-center gap-2" type="submit">
                Login to Portal
                <span class="material-symbols-outlined text-[20px]">arrow_forward</span>
            </button>
            <p class="text-center text-sm text-on-surface-variant pt-4">
                Don't have an account? <a class="text-primary font-bold hover:underline" href="<?= url('app_register') ?>">Register</a>
            </p>
        </form>
    </div>
</main>
