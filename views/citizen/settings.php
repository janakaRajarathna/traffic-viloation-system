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
    <?php require APP_ROOT . 'views/partials/flashes.php'; ?>
    <div class="space-y-10">
        <section class="space-y-4">
            <div class="px-2">
                <h2 class="font-headline font-extrabold text-2xl text-on-surface">Personal Information</h2>
                <p class="text-on-surface-variant text-sm font-medium">Update your public profile and contact details.</p>
            </div>
            <div class="bg-surface-container-lowest p-8 rounded-xl shadow-sm">
                <form method="POST" action="<?= url('app_citizen_settings') ?>" enctype="multipart/form-data" class="space-y-6">
                    <input type="hidden" name="action" value="update_profile">
                    
                    <!-- Profile Picture Upload Section -->
                    <div class="flex flex-col sm:flex-row items-center gap-6 pb-6 border-b border-surface-container-high">
                        <div class="relative group">
                            <div class="w-24 h-24 rounded-full overflow-hidden bg-primary/10 flex items-center justify-center text-primary border-4 border-white shadow-md relative">
                                <?php if ($user->profilePic): ?>
                                    <img id="avatar-preview" src="<?= e($user->profilePic) ?>" alt="Avatar" class="w-full h-full object-cover">
                                <?php else: ?>
                                    <div id="avatar-placeholder" class="flex items-center justify-center w-full h-full">
                                        <span class="material-symbols-outlined text-5xl" data-icon="person">person</span>
                                    </div>
                                    <img id="avatar-preview" src="" alt="Avatar" class="w-full h-full object-cover hidden">
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="space-y-2 text-center sm:text-left">
                            <label class="block text-sm font-bold text-on-surface">Profile Picture</label>
                            <p class="text-xs text-on-surface-variant">JPG, PNG or JPEG. Max size: <?= format_bytes(get_max_upload_size(), 0) ?> (based on system limits).</p>
                            <div class="flex flex-wrap gap-2 justify-center sm:justify-start">
                                <label class="cursor-pointer bg-surface-container-high hover:bg-surface-container-highest px-4 py-2 rounded-lg text-xs font-bold transition-all inline-block border border-outline-variant/30">
                                    <span>Choose New Photo</span>
                                    <input type="file" name="profile_pic" id="profile-pic-input" class="hidden" accept="image/png, image/jpeg, image/jpg" onchange="previewAvatar(this)">
                                </label>
                                <?php if ($user->profilePic): ?>
                                    <button type="submit" name="remove_profile_pic" value="1" class="text-error hover:bg-error-container/20 px-4 py-2 rounded-lg text-xs font-bold transition-all border border-error/20">
                                        Remove Photo
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Full Name</label>
                            <input type="text" name="full_name" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e($user->fullName) ?>" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Telephone Number</label>
                            <input type="tel" name="tel_no" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e(str_pad((string) $user->telNo, 10, '0', STR_PAD_LEFT)) ?>" pattern="[0-9]{10}" title="Phone number must contain exactly 10 digits" required>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">National ID Card</label>
                            <input type="text" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium opacity-60 cursor-not-allowed" value="<?= e((string) $user->NIC) ?>" readonly>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">License Number</label>
                            <input type="text" name="licence_no" class="w-full px-4 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" value="<?= e($user->licenceNo !== null ? (string) $user->licenceNo : '') ?>" pattern="B[0-9]{7}" title="License number must start with a capital B followed by exactly 7 digits (e.g. B1234567)">
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
                <form method="POST" action="<?= url('app_citizen_settings') ?>" class="space-y-6">
                    <input type="hidden" name="action" value="update_password">
                    <div class="space-y-2">
                        <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Current Password</label>
                        <div class="relative">
                            <input type="password" id="current_password" name="current_password" class="w-full pl-4 pr-12 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" required>
                            <button type="button" onclick="togglePasswordVisibility('current_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center" title="Toggle password visibility">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">New Password</label>
                            <div class="relative">
                                <input type="password" id="new_password" name="new_password" class="w-full pl-4 pr-12 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" required>
                                <button type="button" onclick="togglePasswordVisibility('new_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center" title="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
                        </div>
                        <div class="space-y-2">
                            <label class="block text-xs font-bold uppercase tracking-wider text-on-surface-variant px-1">Confirm New Password</label>
                            <div class="relative">
                                <input type="password" id="confirm_password" name="confirm_password" class="w-full pl-4 pr-12 py-3 bg-surface-container-low border-none rounded-lg focus:ring-2 focus:ring-primary/20 font-medium" required>
                                <button type="button" onclick="togglePasswordVisibility('confirm_password', this)" class="absolute right-3 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors flex items-center justify-center" title="Toggle password visibility">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </button>
                            </div>
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
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const preview = document.getElementById('avatar-preview');
                const placeholder = document.getElementById('avatar-placeholder');
                
                if (preview) {
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                }
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

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
