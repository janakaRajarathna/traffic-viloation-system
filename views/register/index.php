<?php $bodyClass = 'bg-surface text-on-surface min-h-screen flex flex-col items-center justify-center'; ?>
<header class="fixed top-0 w-full z-50 bg-surface/80 backdrop-blur-md flex items-center justify-between px-6 h-16">
    <div class="flex items-center gap-2">
        <span class="material-symbols-outlined text-primary">gavel</span>
        <span class="text-xl font-bold tracking-tighter">Civic Flow</span>
    </div>
</header>
<main class="w-full max-w-xl px-4 py-24 md:py-32 mx-auto">
    <div class="bg-surface-container-lowest rounded-xl shadow-lg p-8 md:p-10">
        <h1 class="font-extrabold text-3xl mb-2">Create Account</h1>
        <p class="text-on-surface-variant mb-8">Join the digital civic infrastructure.</p>
        <form class="space-y-6" method="POST" action="<?= url('app_register_process') ?>">
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase text-on-surface-variant" for="fullName">Full Name</label>
                <input class="w-full px-4 py-3.5 bg-surface-container-highest rounded-lg border-none" name="fullName" id="fullName" type="text" required />
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase" for="NIC">NIC</label>
                    <input class="w-full px-4 py-3.5 bg-surface-container-highest rounded-lg border-none" name="NIC" id="NIC" required />
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold uppercase" for="telNo">Telephone</label>
                    <input class="w-full px-4 py-3.5 bg-surface-container-highest rounded-lg border-none" name="telNo" id="telNo" type="tel" required />
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase" for="vehicleNo">Vehicle Number (optional)</label>
                <input class="w-full px-4 py-3.5 bg-surface-container-highest rounded-lg border-none" name="vehicleNo" id="vehicleNo" />
            </div>
            <div class="space-y-3">
                <label class="block text-xs font-bold uppercase">Role</label>
                <div class="flex flex-wrap gap-2">
                    <?php foreach (['citizen', 'driver', 'police', 'admin'] as $r): ?>
                    <label class="flex-1 min-w-[100px] cursor-pointer">
                        <input class="peer sr-only" name="role" type="radio" value="<?= e($r) ?>" <?= $r === 'citizen' ? 'checked' : '' ?> />
                        <div class="w-full text-center py-3 rounded-full text-sm font-semibold bg-surface-container-high peer-checked:bg-primary peer-checked:text-white"><?= e(ucfirst($r)) ?></div>
                    </label>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase" for="licenceNo">License Number (drivers)</label>
                <input class="w-full px-4 py-3.5 bg-surface-container-highest rounded-lg border-none" name="licenceNo" id="licenceNo" />
            </div>
            <div class="space-y-2">
                <label class="block text-xs font-bold uppercase" for="password">Password</label>
                <div class="relative">
                    <input class="w-full pl-4 pr-12 py-3.5 bg-surface-container-highest rounded-lg border-none" name="password" id="password" type="password" required />
                    <button type="button" onclick="togglePasswordVisibility('password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center" title="Toggle password visibility">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
            </div>
            <button class="w-full bg-gradient-to-r from-primary to-primary-container text-white py-4 rounded-xl font-bold text-lg" type="submit">Register</button>
            <p class="text-center text-on-surface-variant">Already have an account? <a class="text-primary font-bold" href="<?= url('app_login') ?>">Login</a></p>
        </form>
    </div>
</main>

<script>
function togglePasswordVisibility(inputId, button) {
    const input = document.getElementById(inputId);
    const icon = button.querySelector('span');
    if (input.type === 'password') {
        input.type = 'text';
        icon.textContent = 'visibility_off';
    } else {
        input.type = 'password';
        icon.textContent = 'visibility';
    }
}
</script>
