<?php $bodyClass = 'bg-background text-on-surface antialiased'; ?>
<style>
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .btn-gradient {
        background: linear-gradient(135deg, #0061a4 0%, #2196f3 100%);
    }
    body {
        font-family: 'Inter', sans-serif;
    }
    h1, h2, h3, .font-headline {
        font-family: 'Manrope', sans-serif;
    }
</style>

<aside class="h-screen w-64 fixed left-0 top-0 bg-[#f6faff]/80 backdrop-blur-xl flex flex-col py-8 px-4 z-50">
    <div class="mb-10 px-2">
        <h1 class="text-xl font-bold tracking-tight text-[#171c20]">Dash Cam</h1>
        <p class="text-xs font-medium text-on-surface-variant opacity-70 uppercase tracking-widest mt-1">Violation Management</p>
    </div>
    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="<?= url('app_police') ?>">
            <span class="material-symbols-outlined text-[22px]">dashboard</span>
            <span class="font-medium">Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#0061a4] font-bold border-r-4 border-[#0061a4] bg-[#f0f4fa] group" href="<?= url('app_violations') ?>">
            <span class="material-symbols-outlined text-[22px]">gavel</span>
            <span class="font-medium">Violations</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="<?= url('app_citizen_reports') ?>">
            <span class="material-symbols-outlined text-[22px]">campaign</span>
            <span class="font-medium">Citizen Reports</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="#">
            <span class="material-symbols-outlined text-[22px]">search_check</span>
            <span class="font-medium">Vehicle Search</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="#">
            <span class="material-symbols-outlined text-[22px]">analytics</span>
            <span class="font-medium">Analytics</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="#">
            <span class="material-symbols-outlined text-[22px]">settings</span>
            <span class="font-medium">Settings</span>
        </a>
    </nav>
    <div class="mt-auto border-t border-outline-variant/10 pt-4 space-y-1">
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-[#404752] hover:bg-[#f0f4fa] transition-colors group" href="#">
            <span class="material-symbols-outlined text-[22px]">help</span>
            <span class="font-medium">Support</span>
        </a>
        <a class="flex items-center gap-3 px-3 py-3 rounded-xl text-error hover:bg-error-container/20 transition-colors group" href="<?= url('app_logout') ?>">
            <span class="material-symbols-outlined text-[22px]">logout</span>
            <span class="font-medium">Logout</span>
        </a>
    </div>
</aside>

<main class="ml-64 min-h-screen">
    <header class="flex justify-between items-center w-full px-8 py-4 sticky top-0 z-40 bg-[#f6faff]/80 backdrop-blur-xl">
        <div class="flex items-center gap-8">
            <h2 class="text-lg font-black text-[#171c20]">Enforcement Portal</h2>
            <div class="hidden md:flex gap-6 items-center">
                <a class="text-sm font-semibold text-[#404752] hover:text-[#0061a4] transition-all" href="#">Directives</a>
                <a class="text-sm font-semibold text-[#404752] hover:text-[#0061a4] transition-all" href="#">Bylaws</a>
                <a class="text-sm font-semibold text-[#404752] hover:text-[#0061a4] transition-all" href="#">Resources</a>
            </div>
        </div>
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 mr-4">
                <button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors" type="button">
                    <span class="material-symbols-outlined">notifications</span>
                </button>
                <button class="p-2 rounded-full text-on-surface-variant hover:bg-surface-container-high transition-colors" type="button">
                    <span class="material-symbols-outlined">history</span>
                </button>
            </div>
            <div class="flex items-center gap-3 pl-4 border-l border-outline-variant/30">
                <div class="text-right">
                    <p class="text-sm font-bold text-on-surface leading-tight"><?= e($user->fullName ?? 'Officer') ?></p>
                    <p class="text-[11px] text-on-surface-variant font-medium">NIC <?= e((string) ($user->NIC ?? '')) ?></p>
                </div>
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center border-2 border-primary-container">
                    <span class="material-symbols-outlined">local_police</span>
                </div>
            </div>
        </div>
    </header>

    <section class="p-10 max-w-5xl mx-auto">
        <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

        <div class="mb-10">
            <div class="flex items-center gap-2 mb-2">
                <a class="text-[#0061a4] text-sm font-bold flex items-center hover:underline" href="<?= url('app_police') ?>">
                    <span class="material-symbols-outlined text-sm mr-1">arrow_back</span>
                    Back to Dashboard
                </a>
            </div>
            <h3 class="text-[2.75rem] font-extrabold text-on-surface tracking-tight leading-none mb-3">Record New Violation</h3>
            <p class="text-on-surface-variant max-w-2xl text-lg">Enter the legal details of the observed infraction. Ensure all digital evidence is attached for automated evidence verification.</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.06)]">
                    <form id="violation-form" method="post" action="<?= url('app_violations') ?>" class="grid grid-cols-1 md:grid-cols-2 gap-8">
                        <div class="space-y-2">
                            <label class="text-sm font-bold text-on-surface-variant px-1" for="vehicleNumber">Vehicle Number</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">directions_car</span>
                                <input id="vehicleNumber" name="vehicle_number" class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all font-headline font-bold text-lg placeholder:font-normal placeholder:text-outline/50" placeholder="e.g. ABC-1234" type="text" required />
                            </div>
                        </div>

                        <div class="space-y-2">
                            <label class="text-sm font-bold text-on-surface-variant px-1" for="driverLicense">Driver License Number</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">badge</span>
                                <input id="driverLicense" name="driver_licence" class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all font-headline font-bold text-lg placeholder:font-normal placeholder:text-outline/50" placeholder="e.g. 33333333" type="text" required />
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-on-surface-variant px-1" for="violationType">Violation Type</label>
                            <div class="relative">
                                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-outline">warning</span>
                                <select id="violationType" name="violation_type" class="w-full pl-12 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 appearance-none transition-all font-medium" required>
                                    <option value="" disabled selected>Select violation category</option>
                                    <?php foreach ($violationTypes as $type): ?>
                                        <option value="<?= e($type) ?>"><?= e($type) ?></option>
                                    <?php endforeach; ?>
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-outline pointer-events-none">expand_more</span>
                            </div>
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-on-surface-variant px-1" for="location">Location</label>
                            <input id="location" name="location" class="w-full px-4 py-4 bg-surface-container-highest border-none rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all font-medium" placeholder="Street or intersection" type="text" />
                        </div>

                        <div class="space-y-2 md:col-span-2">
                            <label class="text-sm font-bold text-on-surface-variant px-1" for="fineAmount">Fine Amount</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-on-surface-variant font-extrabold text-xl">$</span>
                                <input id="fineAmount" name="fine_amount" class="w-full pl-10 pr-4 py-4 bg-surface-container-highest border-none rounded-xl focus:bg-white focus:ring-2 focus:ring-primary/20 transition-all font-headline font-extrabold text-2xl text-primary" placeholder="0.00" type="number" min="1" step="1" required />
                            </div>
                        </div>
                    </form>
                </div>

                <div class="bg-surface-container-lowest p-8 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.06)]">
                    <h4 class="text-lg font-bold text-on-surface mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-primary">cloud_upload</span>
                        Evidence (Image/Video)
                    </h4>
                    <div class="border-2 border-dashed border-outline-variant/40 rounded-xl p-12 text-center bg-surface-container-low/30 hover:bg-surface-container-low transition-colors cursor-pointer group">
                        <div class="w-16 h-16 bg-primary-fixed rounded-full flex items-center justify-center mx-auto mb-4 group-hover:scale-110 transition-transform">
                            <span class="material-symbols-outlined text-primary text-3xl">add_photo_alternate</span>
                        </div>
                        <p class="text-on-surface font-bold text-lg">Drag &amp; Drop Evidence Files</p>
                        <p class="text-on-surface-variant text-sm mt-1">Upload high-resolution .jpg or .mp4 files (Max 50MB)</p>
                        <button class="mt-6 px-6 py-2 bg-white text-primary font-bold rounded-lg shadow-sm border border-outline-variant/20 hover:shadow-md transition-all" type="button">
                            Browse Files
                        </button>
                    </div>
                </div>
            </div>

            <div class="space-y-6">
                <div class="bg-surface-container-high/50 p-6 rounded-xl border border-white">
                    <h4 class="text-sm font-black text-on-surface-variant uppercase tracking-widest mb-4">Verification Check</h4>
                    <ul class="space-y-4">
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <div>
                                <p class="text-sm font-bold text-on-surface">Vehicle Registry Connected</p>
                                <p class="text-xs text-on-surface-variant">Real-time lookup is active.</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-green-600" style="font-variation-settings: 'FILL' 1;">check_circle</span>
                            <div>
                                <p class="text-sm font-bold text-on-surface">GPS Tagging Enabled</p>
                                <p class="text-xs text-on-surface-variant">40.7128° N, 74.0060° W</p>
                            </div>
                        </li>
                        <li class="flex gap-3">
                            <span class="material-symbols-outlined text-outline/40">pending</span>
                            <div>
                                <p class="text-sm font-bold text-on-surface-variant">Facial ID Scan</p>
                                <p class="text-xs text-on-surface-variant italic">Waiting for photo evidence...</p>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="bg-primary text-white p-6 rounded-xl relative overflow-hidden shadow-xl">
                    <div class="relative z-10">
                        <h4 class="text-lg font-bold mb-2">Ready to Submit?</h4>
                        <p class="text-white/80 text-sm mb-6">Submitting this violation will immediately notify the registrant and initiate the 14-day appeal window.</p>
                        <button class="w-full py-4 bg-white text-primary font-black rounded-xl hover:scale-[1.02] active:scale-95 transition-all shadow-lg shadow-black/10" type="submit" form="violation-form">
                            ISSUE CITATION
                        </button>
                    </div>
                    <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                </div>

                <div class="bg-error-container/30 p-6 rounded-xl">
                    <div class="flex items-start gap-3">
                        <span class="material-symbols-outlined text-error">info</span>
                        <div>
                            <p class="text-sm font-bold text-on-error-container">Officer Note</p>
                            <p class="text-xs text-on-error-container/80 mt-1">False citations are subject to internal review and may result in disciplinary action under Section 4-B.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="mt-12 mb-20 px-10 text-center">
        <button class="text-on-surface-variant font-bold text-sm hover:text-primary transition-colors flex items-center justify-center mx-auto gap-2" type="button">
            <span class="material-symbols-outlined text-base">delete</span>
            Discard Draft
        </button>
    </footer>
</main>
