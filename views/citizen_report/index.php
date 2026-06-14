<?php $bodyClass = 'bg-surface text-on-surface min-h-screen'; ?>
<style>
    body { font-family: 'Inter', sans-serif; }
    h1, h2, h3, .headline { font-family: 'Manrope', sans-serif; }
    .material-symbols-outlined {
        font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
    }
    .bg-primary-gradient {
        background: linear-gradient(135deg, #0061a4 0%, #2196f3 100%);
    }
</style>

<?php if (!empty($isPoliceView)): ?>
    <?php require APP_ROOT . 'views/partials/police_sidebar.php'; ?>
<?php else: ?>
    <?php require APP_ROOT . 'views/partials/citizen_sidebar.php'; ?>
<?php endif; ?>

<header class="fixed top-0 w-full md:w-[calc(100%-16rem)] md:right-0 z-40 bg-[#f6faff]/80 backdrop-blur-xl flex justify-between items-center px-6 h-16 border-b border-surface-container-highest transition-all">
    <div class="flex items-center gap-3">
        <button id="open-sidebar-btn" type="button" class="md:hidden w-10 h-10 flex items-center justify-center rounded-full bg-surface-container hover:bg-surface-container-high transition-colors text-on-surface">
            <span class="material-symbols-outlined">menu</span>
        </button>
        <span class="text-xl font-extrabold tracking-tight text-primary font-headline md:hidden">Dash Cam</span>
        <span class="text-xl font-extrabold tracking-tight text-on-surface hidden md:block"><?= !empty($isPoliceView) ? 'Citizen Reports Review' : 'Citizen Reports' ?></span>
    </div>
    <a href="<?= url('app_logout') ?>" class="p-2 text-on-surface-variant hover:bg-error-container/30 rounded-full transition-colors flex items-center" title="Logout">
        <span class="material-symbols-outlined">logout</span>
    </a>
</header>

<main class="md:ml-64 pt-24 pb-32 px-4 md:px-8 max-w-7xl mx-auto transition-all">
    <div class="mb-12 flex flex-col md:flex-row justify-between items-end gap-6">
        <div class="max-w-2xl">
            <h2 class="text-4xl font-extrabold text-on-surface mb-4 tracking-tight">Help Build a Safer Community</h2>
            <p class="text-lg text-on-surface-variant leading-relaxed">
                Transparency starts with you. Report local violations quickly and track their progress in real-time. Your contribution helps maintain order and safety for everyone.
            </p>
        </div>
        <div class="flex items-center gap-3 bg-surface-container-low px-6 py-4 rounded-xl">
            <div class="flex flex-col">
                <span class="text-3xl font-black text-primary"><?= e(number_format((float) $totalReports)) ?></span>
                <span class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Active Citizen Reports</span>
            </div>
            <div class="w-px h-10 bg-outline-variant/30 mx-2"></div>
            <span class="material-symbols-outlined text-primary text-4xl" style="font-variation-settings: 'FILL' 1;">verified</span>
        </div>
    </div>

    <div class="max-w-3xl">
        <section class="bg-surface-container-lowest rounded-xl p-8 shadow-[0_12px_32px_rgba(0,97,164,0.04)]">
            <div class="flex items-center gap-3 mb-8">
                <div class="bg-primary-fixed p-3 rounded-lg text-primary">
                    <span class="material-symbols-outlined">edit_note</span>
                </div>
                <h3 class="text-2xl font-bold">File a New Report</h3>
            </div>
            <?php require APP_ROOT . 'views/partials/flashes.php'; ?>

            <form class="space-y-6" method="post" enctype="multipart/form-data" action="<?= url('app_citizen_reports') ?>">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-on-surface-variant px-1">Date of Incident</label>
                        <input name="incident_date" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-on-surface focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all" type="date" required />
                    </div>
                    <div class="space-y-2">
                        <label class="text-sm font-bold text-on-surface-variant px-1">Location</label>
                        <div class="relative">
                            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">location_on</span>
                            <input name="location" class="w-full bg-surface-container-low border-none rounded-xl py-3 pl-10 pr-4 text-on-surface focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all" placeholder="Street name or neighborhood..." type="text" required />
                        </div>
                    </div>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-on-surface-variant px-1">Violation Description</label>
                    <textarea name="description" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-on-surface focus:ring-2 focus:ring-primary/20 focus:bg-white transition-all resize-none" placeholder="Please describe the violation in detail..." rows="4" required></textarea>
                </div>
                <div class="space-y-2">
                    <label class="text-sm font-bold text-on-surface-variant px-1">Upload Visual Evidence (Photo/Video)</label>
                    <label for="evidence-upload" class="border-2 border-dashed border-outline-variant/50 rounded-xl p-8 flex flex-col items-center justify-center bg-surface-container-low hover:bg-white hover:border-primary/50 transition-all cursor-pointer group">
                        <span class="material-symbols-outlined text-4xl text-outline group-hover:text-primary transition-colors mb-2">cloud_upload</span>
                        <p class="text-sm font-medium text-on-surface-variant">Drag files here or <span class="text-primary underline">browse</span></p>
                        <p class="text-xs text-outline mt-1 italic">Maximum file size: 25MB (PNG, JPG, MP4)</p>
                    </label>
                    <input id="evidence-upload" name="evidence" class="w-full bg-surface-container-low border-none rounded-xl py-3 px-4 text-on-surface file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:font-semibold file:bg-primary file:text-white hover:file:opacity-90" type="file" accept=".png,.jpg,.jpeg,.mp4" required />
                </div>
                <div class="flex items-center gap-3 bg-secondary-container/20 p-4 rounded-xl">
                    <span class="material-symbols-outlined text-on-secondary-container">info</span>
                    <p class="text-xs text-on-secondary-container font-medium leading-relaxed">
                        Reports are strictly confidential. For immediate hazards to life or property, please contact emergency services directly.
                    </p>
                </div>
                <button class="w-full bg-primary-gradient text-white py-4 rounded-xl font-extrabold text-lg shadow-xl shadow-primary/25 hover:opacity-90 active:scale-[0.98] transition-all" type="submit">
                    Submit Official Report
                </button>
            </form>
        </section>
    </div>

    <section class="mt-12 bg-surface-container-lowest rounded-xl shadow-[0_12px_32px_rgba(0,97,164,0.04)] overflow-hidden">
        <div class="p-8 border-b border-outline-variant/10 flex justify-between items-center">
            <div class="flex items-center gap-3">
                <div class="bg-surface-container-high p-3 rounded-lg text-on-surface">
                    <span class="material-symbols-outlined">folder_shared</span>
                </div>
                <div>
                    <h3 class="text-2xl font-bold"><?= !empty($isPoliceView) ? 'Recent Citizen Reports' : 'My Recent Reports' ?></h3>
                    <p class="text-sm text-on-surface-variant"><?= !empty($isPoliceView) ? 'Community submissions awaiting review' : 'Tracking your contributions to civic order' ?></p>
                </div>
            </div>
            <button class="text-sm font-bold text-primary flex items-center gap-1 hover:underline" type="button">
                View Full History <span class="material-symbols-outlined text-sm">arrow_forward</span>
            </button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-surface-container-low text-on-surface-variant text-xs uppercase tracking-widest font-bold">
                        <th class="px-8 py-4">Report ID</th>
                        <th class="px-8 py-4">Description</th>
                        <th class="px-8 py-4">Date Submitted</th>
                        <th class="px-8 py-4">Location</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-outline-variant/5">
                    <?php if (empty($recentReports)): ?>
                        <tr>
                            <td class="px-8 py-10 text-center text-sm text-on-surface-variant" colspan="6">
                                No reports submitted yet. File your first report using the form above.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($recentReports as $report): ?>
                            <?php
                                $desc = (string) $report->description;
                                $shortDesc = strlen($desc) > 50 ? substr($desc, 0, 50) . '…' : $desc;
                                $statusClass = $report->status === 'Approved'
                                    ? 'bg-green-100 text-green-700'
                                    : ($report->status === 'Rejected'
                                        ? 'bg-error-container text-on-error-container'
                                        : 'bg-tertiary-container/10 text-on-tertiary-container');
                                $dotClass = $report->status === 'Approved'
                                    ? 'bg-green-500'
                                    : ($report->status === 'Rejected'
                                        ? 'bg-error'
                                        : 'bg-tertiary-container');
                            ?>
                            <tr class="hover:bg-surface-container-low/30 transition-colors">
                                <td class="px-8 py-6 font-mono text-sm text-on-surface-variant">#CR-<?= e((string) $report->id) ?></td>
                                <td class="px-8 py-6 font-bold text-on-surface"><?= e($shortDesc) ?></td>
                                <td class="px-8 py-6 text-sm text-on-surface-variant"><?= $report->createdAt ? e($report->createdAt->format('M d, Y')) : '—' ?></td>
                                <td class="px-8 py-6 text-sm text-on-surface-variant"><?= e($report->location) ?></td>
                                <td class="px-8 py-6">
                                    <span class="<?= e($statusClass) ?> px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider flex items-center gap-1 w-fit">
                                        <span class="w-1.5 h-1.5 rounded-full <?= e($dotClass) ?>"></span>
                                        <?= e($report->status) ?>
                                    </span>
                                </td>
                                <td class="px-8 py-6 text-right">
                                    <div class="flex items-center justify-end gap-4">
                                        <a class="text-primary font-bold text-sm hover:opacity-70" href="<?= url('app_evidence_report', ['id' => $report->id]) ?>" target="_blank" rel="noopener">View Evidence</a>
                                        <?php if (!empty($isPoliceView)): ?>
                                            <?php if ($report->status === 'Pending Review'): ?>
                                                <a class="bg-primary text-white px-3.5 py-1.5 rounded-xl text-xs font-bold shadow-sm hover:opacity-90 active:scale-95 transition-all" href="<?= url('app_violations') ?>?report_id=<?= $report->id ?>">
                                                    Action
                                                </a>
                                            <?php else: ?>
                                                <span class="text-on-surface-variant/50 text-xs font-bold uppercase tracking-wider">Processed</span>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <div class="px-8 py-6 bg-surface-container-low/30 text-center">
            <p class="text-sm text-on-surface-variant">
                <?php if ($totalReports > 0): ?>
                    Showing <?= e((string) count($recentReports)) ?> of <?= e((string) $totalReports) ?> report<?= $totalReports != 1 ? 's' : '' ?>.
                <?php else: ?>
                    Your submitted reports will appear here.
                <?php endif; ?>
            </p>
        </div>
    </section>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const sidebar = document.getElementById("police-sidebar") || document.getElementById("citizen-sidebar");
        const overlay = document.getElementById("sidebar-overlay");
        const openBtn = document.getElementById("open-sidebar-btn");
        const closeBtn = document.getElementById("close-sidebar-btn");

        function openSidebar() {
            if (sidebar) sidebar.classList.remove("-translate-x-full");
            if (overlay) overlay.classList.remove("hidden");
        }

        function closeSidebar() {
            if (sidebar) sidebar.classList.add("-translate-x-full");
            if (overlay) overlay.classList.add("hidden");
        }

        if (openBtn) openBtn.addEventListener("click", openSidebar);
        if (closeBtn) closeBtn.addEventListener("click", closeSidebar);
        if (overlay) overlay.addEventListener("click", closeSidebar);
    });
</script>
