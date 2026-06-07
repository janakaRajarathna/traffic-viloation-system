<?php $bodyClass = 'bg-surface text-on-surface min-h-screen font-body'; ?>
<style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, h4, h5, .headline { font-family: 'Manrope', sans-serif; }
    .glass-panel { backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); }
    .primary-gradient { background: linear-gradient(135deg, #0061a4 0%, #2196f3 100%); }
</style>

<aside class="h-screen w-64 fixed left-0 top-0 bg-surface/80 backdrop-blur-xl flex flex-col py-8 px-4 z-50 border-r border-outline-variant/10">
    <div class="mb-10 px-4">
        <h1 class="text-xl font-bold tracking-tight text-on-surface">Dash Cam</h1>
        <p class="text-xs text-on-surface-variant font-medium uppercase tracking-wider">Violation Management</p>
    </div>

    <a class="mb-8 flex items-center justify-center gap-2 primary-gradient text-white py-3 px-4 rounded-xl font-semibold shadow-[0_12px_32px_rgba(0,97,164,0.15)] active:scale-95 transition-transform" href="<?= url('app_violations') ?>">
        <span class="material-symbols-outlined" data-icon="add">add</span>
        New Violation
    </a>

    <nav class="flex-1 space-y-1">
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-primary font-bold border-r-4 border-primary bg-surface-container-low transition-colors" href="<?= url('app_police') ?>">
            <span class="material-symbols-outlined" data-icon="dashboard">dashboard</span>
            <span>Dashboard</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="<?= url('app_violations') ?>">
            <span class="material-symbols-outlined" data-icon="gavel">gavel</span>
            <span>Violations</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="<?= url('app_citizen_reports') ?>">
            <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
            <span>Citizen Reports</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
            <span class="material-symbols-outlined" data-icon="search_check">search_check</span>
            <span>Vehicle Search</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
            <span class="material-symbols-outlined" data-icon="analytics">analytics</span>
            <span>Analytics</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
            <span class="material-symbols-outlined" data-icon="settings">settings</span>
            <span>Settings</span>
        </a>
    </nav>

    <div class="mt-auto space-y-1 pt-4 border-t border-outline-variant/10">
        <div class="px-4 py-3">
            <p class="text-sm font-bold text-on-surface truncate"><?= e($user->fullName ?? 'Officer') ?></p>
            <p class="text-xs text-on-surface-variant truncate">Badge / NIC <?= e((string) ($user->NIC ?? 'N/A')) ?></p>
        </div>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-on-surface-variant hover:bg-surface-container-low transition-colors" href="#">
            <span class="material-symbols-outlined" data-icon="help">help</span>
            <span>Support</span>
        </a>
        <a class="flex items-center gap-3 px-4 py-3 rounded-lg text-error hover:bg-error-container/20 transition-colors" href="<?= url('app_logout') ?>">
            <span class="material-symbols-outlined" data-icon="logout">logout</span>
            <span>Logout</span>
        </a>
    </div>
</aside>

<header class="flex justify-between items-center w-full px-8 py-4 ml-64 max-w-[calc(100%-16rem)] bg-surface/80 backdrop-blur-xl sticky top-0 z-40 border-b border-outline-variant/10">
    <div class="flex items-center gap-8">
        <h2 class="text-lg font-black text-on-surface">Enforcement Portal</h2>
        <div class="relative group">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline" data-icon="search">search</span>
            <input class="pl-10 pr-4 py-2 bg-surface-container-highest rounded-full border-none focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all text-sm w-64" placeholder="Search vehicle or ID..." type="text"/>
        </div>
    </div>

    <div class="flex items-center gap-6">
        <div class="hidden md:flex items-center gap-6 text-sm font-medium text-on-surface-variant">
            <a class="hover:text-primary transition-all" href="#">Directives</a>
            <a class="hover:text-primary transition-all" href="#">Bylaws</a>
            <a class="hover:text-primary transition-all" href="#">Resources</a>
        </div>
        <div class="flex items-center gap-3 border-l border-outline-variant/30 pl-6">
            <button type="button" class="p-2 text-on-surface-variant hover:opacity-80">
                <span class="material-symbols-outlined" data-icon="notifications">notifications</span>
            </button>
            <button type="button" class="p-2 text-on-surface-variant hover:opacity-80">
                <span class="material-symbols-outlined" data-icon="history">history</span>
            </button>
            <div class="flex items-center gap-3 ml-2">
                <div class="w-10 h-10 rounded-full bg-primary/10 text-primary flex items-center justify-center shadow-sm">
                    <span class="material-symbols-outlined" data-icon="local_police">local_police</span>
                </div>
                <div class="text-left leading-tight hidden xl:block">
                    <p class="text-sm font-bold text-on-surface"><?= e($user->fullName ?? 'Officer') ?></p>
                    <p class="text-[10px] text-on-surface-variant font-medium">Badge / NIC <?= e((string) ($user->NIC ?? 'N/A')) ?></p>
                </div>
            </div>
        </div>
    </div>
</header>

<main class="ml-64 p-8 max-w-[1400px]">
    <header class="mb-10 flex justify-between items-end">
        <div>
            <p class="text-primary font-semibold text-sm mb-1">On duty, <?= e($user->fullName ?? 'Officer') ?></p>
            <h3 class="text-3xl font-extrabold text-on-surface headline">Operational Overview</h3>
        </div>
        <div class="flex gap-2">
            <button type="button" class="bg-surface-container-high text-primary px-4 py-2 rounded-xl text-sm font-semibold hover:opacity-90 transition-all">
                Export Weekly Report
            </button>
        </div>
    </header>

    <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-10">
        <div class="md:col-span-2 bg-surface-container-lowest p-6 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] flex flex-col justify-between h-48">
            <div class="flex justify-between items-start">
                <div class="p-3 bg-primary/10 rounded-xl text-primary">
                    <span class="material-symbols-outlined" data-icon="receipt_long">receipt_long</span>
                </div>
                <span class="text-xs font-bold text-on-secondary-container bg-secondary-container px-2 py-1 rounded-full"><?= e($monthlyTrend) ?></span>
            </div>
            <div>
                <p class="text-on-surface-variant text-sm font-medium">Total Violations Recorded</p>
                <h4 class="text-5xl font-extrabold text-on-surface headline tracking-tight"><?= e(number_format((float) $totalViolations)) ?></h4>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] flex flex-col justify-between h-48">
            <div class="p-3 bg-tertiary-fixed/30 rounded-xl text-tertiary w-fit">
                <span class="material-symbols-outlined" data-icon="pending_actions">pending_actions</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-sm font-medium">Pending Citations</p>
                <h4 class="text-4xl font-extrabold text-on-surface headline"><?= e((string) $pendingViolations) ?></h4>
            </div>
        </div>

        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] flex flex-col justify-between h-48">
            <div class="p-3 bg-on-secondary-container/10 rounded-xl text-on-secondary-container w-fit">
                <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
            </div>
            <div>
                <p class="text-on-surface-variant text-sm font-medium">Citizen Reports Pending</p>
                <h4 class="text-4xl font-extrabold text-on-surface headline"><?= e((string) $pendingCitizenReports) ?></h4>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-10">
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] flex items-center justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-medium">Paid Violations (Closure Rate)</p>
                <h4 class="text-3xl font-extrabold text-on-surface headline"><?= e((string) $closureRate) ?>%</h4>
            </div>
            <div class="p-3 bg-green-100 rounded-xl text-green-700">
                <span class="material-symbols-outlined" data-icon="verified">verified</span>
            </div>
        </div>
        <div class="bg-surface-container-lowest p-6 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] flex items-center justify-between">
            <div>
                <p class="text-on-surface-variant text-sm font-medium">Total Citizen Reports</p>
                <h4 class="text-3xl font-extrabold text-on-surface headline"><?= e((string) $totalCitizenReports) ?></h4>
            </div>
            <a class="text-sm font-bold text-primary hover:underline" href="<?= url('app_citizen_reports') ?>">Review all</a>
        </div>
    </div>

    <section class="bg-surface-container-lowest rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] overflow-hidden">
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center">
            <h4 class="text-lg font-bold text-on-surface headline">Recent Violations</h4>
            <a class="text-sm font-semibold text-primary flex items-center gap-1 hover:underline" href="<?= url('app_violations') ?>">
                Record new violation
                <span class="material-symbols-outlined text-sm" data-icon="add">add</span>
            </a>
        </div>

        <div class="flex flex-col">
            <div class="grid grid-cols-6 gap-4 px-6 py-4 bg-surface-container-low text-xs font-bold uppercase tracking-widest text-on-surface-variant">
                <div class="col-span-1">Vehicle Plate</div>
                <div class="col-span-2">Violation Type</div>
                <div class="col-span-1">Date &amp; Time</div>
                <div class="col-span-1 text-right">Fine Amount</div>
                <div class="col-span-1 text-center">Status</div>
            </div>

            <?php if (empty($recentViolations)): ?>
                <div class="px-6 py-8 text-center text-on-surface-variant">
                    No violations recorded yet.
                    <a class="text-primary font-bold hover:underline" href="<?= url('app_violations') ?>">Record the first violation</a>
                </div>
            <?php else: ?>
                <?php foreach ($recentViolations as $index => $violation): ?>
                    <?php
                        $statusClass = $violation->status === 'Paid'
                            ? 'bg-on-secondary-container/10 text-on-secondary-container'
                            : (in_array($violation->status, ['Unpaid'], true)
                                ? 'bg-error-container text-on-error-container'
                                : 'bg-tertiary-container/20 text-on-tertiary-container');
                        $violationDate = $violation->createdAt ?? $violation->incidentDate;
                        $rowBg = ($index + 1) % 2 === 0 ? 'bg-surface-container-low/30' : '';
                    ?>
                    <div class="grid grid-cols-6 gap-4 px-6 py-5 items-center <?= e($rowBg) ?> hover:bg-surface-container-low transition-colors group">
                        <div class="col-span-1">
                            <span class="bg-on-surface text-surface px-3 py-1.5 rounded-lg font-mono text-sm font-bold tracking-widest shadow-sm">
                                <?= e($violation->vehicleNumber ?? 'N/A') ?>
                            </span>
                        </div>
                        <div class="col-span-2 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-primary/10 text-primary flex items-center justify-center">
                                <span class="material-symbols-outlined text-sm" data-icon="gavel">gavel</span>
                            </div>
                            <span class="font-semibold text-on-surface"><?= e($violation->violationType) ?></span>
                        </div>
                        <div class="col-span-1 text-sm text-on-surface-variant">
                            <?= $violationDate ? e($violationDate->format('M d, Y h:i A')) : '—' ?>
                        </div>
                        <div class="col-span-1 text-right font-bold text-on-surface font-headline">
                            $<?= e(number_format((float) $violation->fineAmount, 2)) ?>
                        </div>
                        <div class="col-span-1 flex justify-center">
                            <span class="px-4 py-1 rounded-full text-xs font-bold <?= e($statusClass) ?>">
                                <?= e($violation->status) ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>

    <section class="bg-surface-container-lowest rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] overflow-hidden mt-10">
        <div class="p-6 border-b border-outline-variant/10 flex justify-between items-center">
            <h4 class="text-lg font-bold text-on-surface headline">Recent Citizen Reports</h4>
            <a class="text-sm font-semibold text-primary hover:underline" href="<?= url('app_citizen_reports') ?>">View all reports</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low text-xs font-bold uppercase tracking-widest text-on-surface-variant">
                        <th class="px-6 py-4">Report ID</th>
                        <th class="px-6 py-4">Description</th>
                        <th class="px-6 py-4">Location</th>
                        <th class="px-6 py-4">Submitted</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4 text-right">Evidence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/10">
                    <?php if (empty($recentCitizenReports)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-8 text-center text-on-surface-variant">No citizen reports submitted yet.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentCitizenReports as $report): ?>
                            <?php
                                $desc = (string) $report->description;
                                $shortDesc = strlen($desc) > 40 ? substr($desc, 0, 40) . '…' : $desc;
                            ?>
                            <tr class="hover:bg-surface-container-low/30">
                                <td class="px-6 py-4 font-mono text-sm">#CR-<?= e((string) $report->id) ?></td>
                                <td class="px-6 py-4 font-medium"><?= e($shortDesc) ?></td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant"><?= e($report->location) ?></td>
                                <td class="px-6 py-4 text-sm text-on-surface-variant"><?= $report->createdAt ? e($report->createdAt->format('M d, Y')) : '—' ?></td>
                                <td class="px-6 py-4">
                                    <span class="px-3 py-1 rounded-full text-xs font-bold bg-tertiary-fixed text-on-tertiary-fixed-variant"><?= e($report->status) ?></span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a class="text-primary font-bold text-sm hover:opacity-70" href="<?= url('app_evidence_report', ['id' => $report->id]) ?>" target="_blank" rel="noopener">View</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <?php if (!empty($topLocation)): ?>
            <div class="px-6 py-4 bg-surface-container-low/50 text-sm text-on-surface-variant">
                <span class="material-symbols-outlined text-sm align-middle mr-1">location_on</span>
                Most reported violation area: <strong class="text-on-surface"><?= e($topLocation) ?></strong>
            </div>
        <?php endif; ?>
    </section>
</main>
