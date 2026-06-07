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
body {
    min-height: max(884px, 100dvh);
}
</style>

<?php require APP_ROOT . 'views/partials/citizen_sidebar.php'; ?>

<header class="fixed top-0 w-full md:w-[calc(100%-16rem)] md:right-0 z-40 bg-surface/80 backdrop-blur-xl flex justify-between items-center px-6 h-16 border-b border-surface-container-highest transition-all">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined" data-icon="menu">menu</span>
        </button>
        <span class="text-xl font-extrabold tracking-tight text-primary font-headline md:hidden">Dash Cam</span>
        <span class="text-xl font-extrabold tracking-tight text-on-surface font-headline hidden md:block">Overview</span>
    </div>
    <div class="flex items-center gap-4">
        <a href="<?= url('app_logout') ?>" class="w-10 h-10 flex items-center justify-center text-slate-500 hover:bg-error-container transition-colors rounded-full active:scale-95 duration-150">
            <span class="material-symbols-outlined" data-icon="logout">logout</span>
        </a>
    </div>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-7xl mx-auto space-y-8 flex-1 transition-all">
    <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

    <section class="space-y-2">
        <h1 class="font-headline font-extrabold text-on-surface text-4xl tracking-tight">Citizen Overview</h1>
        <p class="text-on-surface-variant font-medium">
            <?php if ($hasLicense): ?>
                Manage your traffic documentation and outstanding dues.
            <?php else: ?>
                Submit community reports and track their review status.
            <?php endif; ?>
        </p>
    </section>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 glass-panel p-8 rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.06)] bg-gradient-to-br from-white/90 to-blue-50/50 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 relative overflow-hidden">
            <div class="absolute -right-12 -top-12 w-48 h-48 bg-primary/5 rounded-full blur-3xl"></div>
            <div class="space-y-6 relative z-10">
                <div>
                    <p class="text-xs font-bold uppercase tracking-widest text-primary mb-1">Citizen Identity</p>
                    <h2 class="font-headline font-bold text-3xl text-on-surface"><?= e($user->fullName ?? 'N/A') ?></h2>
                </div>
                <div class="flex flex-wrap gap-8">
                    <div>
                        <p class="text-xs text-on-surface-variant font-semibold">NIC Number</p>
                        <p class="font-headline font-bold text-lg"><?= e((string) $user->NIC) ?></p>
                    </div>
                    <div>
                        <p class="text-xs text-on-surface-variant font-semibold">Driver's License</p>
                        <p class="font-headline font-bold text-lg"><?= $hasLicense ? e((string) $user->licenceNo) : 'Not registered' ?></p>
                    </div>
                </div>
            </div>
            <div class="w-full md:w-auto flex flex-col gap-2 relative z-10">
                <div class="p-4 bg-surface-container-lowest rounded-xl shadow-sm">
                    <p class="text-xs text-on-surface-variant font-semibold">License Status</p>
                    <div class="flex items-center gap-2 mt-1">
                        <?php if ($hasLicense): ?>
                            <span class="w-2 h-2 rounded-full bg-secondary"></span>
                            <span class="font-bold text-secondary">ACTIVE</span>
                        <?php else: ?>
                            <span class="w-2 h-2 rounded-full bg-outline"></span>
                            <span class="font-bold text-on-surface-variant">NO LICENSE</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-surface-container-low p-8 rounded-xl flex flex-col justify-center">
            <?php if ($hasLicense): ?>
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Outstanding Dues</p>
                <h3 class="font-headline font-extrabold text-5xl text-primary">$<?= e(number_format((float) $outstandingTotal, 2)) ?></h3>
                <p class="text-sm text-on-surface-variant mt-4 flex items-center gap-1">
                    <?php if ($pendingViolationCount > 0): ?>
                        <span class="material-symbols-outlined text-sm" data-icon="warning">warning</span>
                        <?= e((string) $pendingViolationCount) ?> violation<?= $pendingViolationCount != 1 ? 's' : '' ?> pending payment
                    <?php else: ?>
                        <span class="material-symbols-outlined text-sm" data-icon="check_circle">check_circle</span>
                        No outstanding fines
                    <?php endif; ?>
                </p>
            <?php else: ?>
                <p class="text-xs font-bold uppercase tracking-widest text-on-surface-variant mb-2">Reports Submitted</p>
                <h3 class="font-headline font-extrabold text-5xl text-primary"><?= e((string) $reportsCount) ?></h3>
                <p class="text-sm text-on-surface-variant mt-4 flex items-center gap-1">
                    <span class="material-symbols-outlined text-sm" data-icon="campaign">campaign</span>
                    Community reports filed by you
                </p>
            <?php endif; ?>
        </div>
    </div>

    <section class="space-y-4">
        <div class="flex items-center justify-between">
            <?php if ($hasLicense): ?>
                <h2 class="font-headline font-bold text-2xl px-2">Recent Violations</h2>
            <?php else: ?>
                <h2 class="font-headline font-bold text-2xl px-2">My Citizen Reports</h2>
            <?php endif; ?>
            <a class="text-primary font-semibold text-sm hover:underline" href="<?= url('app_citizen_reports') ?>">View all reports</a>
        </div>
        <div class="bg-surface-container-lowest rounded-xl overflow-hidden shadow-[0_12px_32px_rgba(0,97,164,0.04)]">
            <div class="overflow-x-auto">
                <?php if ($hasLicense): ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Offense</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Date &amp; Time</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Location</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-right">Amount</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0">
                        <?php if (empty($violations)): ?>
                        <tr>
                            <td colspan="5" class="px-6 py-6 text-center text-on-surface-variant font-medium">No traffic violations on record. Great job!</td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($violations as $violation): ?>
                        <?php
                            $statusClass = in_array($violation->status, ['Paid'], true)
                                ? 'bg-surface-container-highest text-[#006b54]'
                                : (in_array($violation->status, ['Unpaid'], true)
                                    ? 'bg-error-container text-on-error-container'
                                    : 'bg-tertiary-fixed text-on-tertiary-fixed-variant');
                            $violationDate = $violation->incidentDate ?? $violation->createdAt;
                        ?>
                        <tr class="hover:bg-blue-50/30 transition-colors border-b border-surface">
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg bg-surface-container-highest flex items-center justify-center text-on-surface">
                                        <span class="material-symbols-outlined text-lg" data-icon="receipt_long">receipt_long</span>
                                    </div>
                                    <span class="font-semibold text-on-surface"><?= e($violation->violationType) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-6 text-on-surface-variant">
                                <?= $violationDate ? e($violationDate->format('M d, Y')) : '—' ?>
                            </td>
                            <td class="px-6 py-6">
                                <div class="flex items-center gap-1 text-on-surface-variant">
                                    <span class="material-symbols-outlined text-sm" data-icon="location_on">location_on</span>
                                    <span><?= e($violation->location) ?></span>
                                </div>
                            </td>
                            <td class="px-6 py-6 font-headline font-bold text-right text-on-surface">
                                $<?= e(number_format((float) ($violation->fineAmount ?? 0), 2)) ?>
                            </td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-4 py-1.5 rounded-full <?= e($statusClass) ?> text-xs font-bold"><?= e($violation->status) ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php else: ?>
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-surface-container-low">
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Report ID</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Description</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Date</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant">Location</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-center">Status</th>
                            <th class="px-6 py-4 text-xs font-bold uppercase tracking-wider text-on-surface-variant text-right">Evidence</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y-0">
                        <?php if (empty($citizenReports)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-10 text-center text-on-surface-variant font-medium">
                                You have not submitted any reports yet.
                                <a class="text-primary font-bold hover:underline" href="<?= url('app_citizen_reports') ?>">File your first report</a>
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($citizenReports as $report): ?>
                        <?php
                            $desc = (string) $report->description;
                            $shortDesc = strlen($desc) > 40 ? substr($desc, 0, 40) . '…' : $desc;
                        ?>
                        <tr class="hover:bg-blue-50/30 transition-colors border-b border-surface">
                            <td class="px-6 py-6 font-mono text-sm text-on-surface-variant">#CR-<?= e((string) $report->id) ?></td>
                            <td class="px-6 py-6 font-semibold text-on-surface"><?= e($shortDesc) ?></td>
                            <td class="px-6 py-6 text-on-surface-variant"><?= $report->createdAt ? e($report->createdAt->format('M d, Y')) : '—' ?></td>
                            <td class="px-6 py-6 text-on-surface-variant"><?= e($report->location) ?></td>
                            <td class="px-6 py-6 text-center">
                                <span class="px-4 py-1.5 rounded-full bg-tertiary-fixed text-on-tertiary-fixed-variant text-xs font-bold"><?= e($report->status) ?></span>
                            </td>
                            <td class="px-6 py-6 text-right">
                                <a class="text-primary font-bold text-sm hover:opacity-70" href="<?= url('app_evidence_report', ['id' => $report->id]) ?>" target="_blank" rel="noopener">View</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>

<?php if ($hasLicense && $outstandingTotal > 0): ?>
<div class="fixed bottom-24 left-1/2 -translate-x-1/2 z-40 w-full max-w-md px-6 md:hidden">
    <button class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-white font-headline font-bold rounded-xl shadow-xl flex items-center justify-center gap-2 active:scale-95 transition-transform" type="button">
        <span class="material-symbols-outlined" data-icon="payments">payments</span>
        Pay All Fines ($<?= e(number_format((float) $outstandingTotal, 2)) ?>)
    </button>
</div>
<div class="hidden md:block fixed bottom-8 right-8 z-40">
    <button class="px-8 py-4 bg-gradient-to-r from-primary to-primary-container text-white font-headline font-bold rounded-xl shadow-[0_12px_24px_rgba(0,97,164,0.3)] flex items-center justify-center gap-3 hover:scale-105 hover:shadow-[0_16px_32px_rgba(0,97,164,0.4)] active:scale-95 transition-all" type="button">
        <span class="material-symbols-outlined" data-icon="payments">payments</span>
        Settle Violations Now
    </button>
</div>
<?php elseif (!$hasLicense): ?>
<div class="fixed bottom-24 left-1/2 -translate-x-1/2 z-40 w-full max-w-md px-6 md:hidden">
    <a class="w-full py-4 bg-gradient-to-r from-primary to-primary-container text-white font-headline font-bold rounded-xl shadow-xl flex items-center justify-center gap-2 active:scale-95 transition-transform" href="<?= url('app_citizen_reports') ?>">
        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
        File a New Report
    </a>
</div>
<div class="hidden md:block fixed bottom-8 right-8 z-40">
    <a class="px-8 py-4 bg-gradient-to-r from-primary to-primary-container text-white font-headline font-bold rounded-xl shadow-[0_12px_24px_rgba(0,97,164,0.3)] flex items-center justify-center gap-3 hover:scale-105 active:scale-95 transition-all" href="<?= url('app_citizen_reports') ?>">
        <span class="material-symbols-outlined" data-icon="campaign">campaign</span>
        File a New Report
    </a>
</div>
<?php endif; ?>

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
