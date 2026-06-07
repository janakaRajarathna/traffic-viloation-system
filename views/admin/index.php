<?php $bodyClass = 'bg-background font-body text-on-surface antialiased min-h-screen'; ?>
<style>
.glass-panel {
    background: rgba(255, 255, 255, 0.7);
    backdrop-filter: blur(20px);
    -webkit-backdrop-filter: blur(20px);
}
.stat-card {
    transition: transform 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
}
.stat-card:hover {
    transform: translateY(-5px);
}
</style>

<header class="fixed top-0 w-full z-50 bg-primary/95 text-white flex justify-between items-center px-8 h-18 shadow-lg backdrop-blur-md">
    <div class="flex items-center gap-4">
        <span class="material-symbols-outlined text-3xl" data-icon="admin_panel_settings">admin_panel_settings</span>
        <div>
            <h1 class="text-xl font-headline font-extrabold tracking-tight">Civic Flow Admin</h1>
            <p class="text-[10px] uppercase font-black tracking-widest opacity-80">Management Console</p>
        </div>
    </div>
    <div class="flex items-center gap-6">
        <div class="hidden md:flex items-center gap-2 px-4 py-2 bg-white/10 rounded-full text-sm font-bold">
            <span class="w-2 h-2 rounded-full bg-green-400 animate-pulse"></span>
            System Live
        </div>
        <a href="<?= url('app_logout') ?>" class="w-10 h-10 flex items-center justify-center bg-white/10 hover:bg-white/20 rounded-full transition-colors">
            <span class="material-symbols-outlined" data-icon="logout">logout</span>
        </a>
    </div>
</header>

<main class="pt-28 pb-32 px-4 md:px-12 max-w-[1600px] mx-auto space-y-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-surface-container flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-blue-100 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" data-icon="groups">groups</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Total Users</p>
                <p class="text-3xl font-headline font-black text-on-surface"><?= e((string) $stats['totalUsers']) ?></p>
            </div>
        </div>
        <div class="stat-card bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-surface-container flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-purple-100 text-purple-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" data-icon="directions_car">directions_car</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Vehicles</p>
                <p class="text-3xl font-headline font-black text-on-surface"><?= e((string) $stats['totalVehicles']) ?></p>
            </div>
        </div>
        <div class="stat-card bg-surface-container-lowest p-6 rounded-2xl shadow-sm border border-surface-container flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-orange-100 text-orange-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" data-icon="gavel">gavel</span>
            </div>
            <div>
                <p class="text-xs font-bold text-on-surface-variant uppercase tracking-widest">Violations</p>
                <p class="text-3xl font-headline font-black text-on-surface"><?= e((string) $stats['totalViolations']) ?></p>
            </div>
        </div>
        <div class="stat-card bg-primary text-white p-6 rounded-2xl shadow-xl shadow-primary/20 flex items-center gap-5">
            <div class="w-14 h-14 rounded-xl bg-white/20 text-white flex items-center justify-center">
                <span class="material-symbols-outlined text-3xl" data-icon="payments">payments</span>
            </div>
            <div>
                <p class="text-xs font-bold opacity-80 uppercase tracking-widest">Total Revenue</p>
                <p class="text-3xl font-headline font-black">$<?= e(number_format((float) $stats['unpaidDues'])) ?></p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-8">
        <div class="xl:col-span-1 space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="font-headline font-extrabold text-xl">Recent Registrations</h2>
                <a href="#" class="text-primary text-xs font-bold hover:underline">Manage All</a>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden border border-surface-container">
                <div class="divide-y divide-surface">
                    <?php if (empty($recentUsers)): ?>
                        <div class="p-8 text-center text-on-surface-variant text-sm font-medium">No recent users.</div>
                    <?php else: ?>
                        <?php foreach ($recentUsers as $recentUser): ?>
                            <div class="p-4 flex items-center gap-4 hover:bg-surface-container-low transition-colors">
                                <div class="w-10 h-10 rounded-full bg-surface-container-high flex items-center justify-center text-on-surface-variant font-bold text-xs">
                                    <?= e(mb_substr((string) $recentUser->fullName, 0, 1)) ?>
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-on-surface truncate"><?= e($recentUser->fullName) ?></p>
                                    <p class="text-[10px] text-on-surface-variant font-medium">NIC: <?= e((string) $recentUser->NIC) ?> • <?= e(ucfirst((string) $recentUser->role)) ?></p>
                                </div>
                                <span class="text-[10px] font-bold text-outline-variant"><?= $recentUser->createdAt ? e($recentUser->createdAt->format('M d')) : 'N/A' ?></span>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <div class="xl:col-span-2 space-y-4">
            <div class="flex items-center justify-between px-2">
                <h2 class="font-headline font-extrabold text-xl">System Violations</h2>
                <div class="flex gap-2">
                    <span class="px-3 py-1 bg-error-container text-on-error-container rounded-full text-[10px] font-black uppercase tracking-tighter">Live Feed</span>
                </div>
            </div>
            <div class="bg-surface-container-lowest rounded-2xl shadow-sm overflow-hidden border border-surface-container">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-surface-container-low border-b border-surface-container">
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Driver / Vehicle</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Offense</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant">Amount</th>
                                <th class="px-6 py-4 text-[10px] font-black uppercase tracking-widest text-on-surface-variant text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-surface">
                            <?php if (empty($recentViolations)): ?>
                                <tr><td colspan="4" class="p-8 text-center text-on-surface-variant text-sm font-medium">No violation records found.</td></tr>
                            <?php else: ?>
                                <?php foreach ($recentViolations as $violation): ?>
                                    <?php
                                        $statusClass = $violation->status === 'Paid'
                                            ? 'bg-green-100 text-green-700'
                                            : 'bg-red-100 text-red-700';
                                    ?>
                                    <tr class="hover:bg-surface-container-low transition-colors">
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-bold text-on-surface">User ID: <?= e((string) $violation->driverId) ?></p>
                                            <p class="text-[10px] font-medium text-on-surface-variant"><?= e($violation->vehicleNumber ?? 'Unknown') ?></p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p class="text-sm font-medium text-on-surface"><?= e($violation->violationType) ?></p>
                                            <p class="text-[10px] text-on-surface-variant"><?= e($violation->location) ?></p>
                                        </td>
                                        <td class="px-6 py-4 font-headline font-bold text-on-surface">
                                            $<?= e(number_format((float) $violation->fineAmount)) ?>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-3 py-1 rounded-full <?= e($statusClass) ?> text-[10px] font-black uppercase tracking-tighter"><?= e($violation->status) ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <section class="space-y-4">
        <div class="flex items-center justify-between px-2">
            <h2 class="font-headline font-extrabold text-xl">Citizen Submissions</h2>
            <a href="#" class="text-primary text-xs font-bold hover:underline">Review Queue</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php if (empty($recentReports)): ?>
                <div class="col-span-full p-12 text-center text-on-surface-variant font-medium glass-panel rounded-2xl">
                    No active citizen reports in the queue.
                </div>
            <?php else: ?>
                <?php foreach ($recentReports as $report): ?>
                    <div class="bg-surface-container-lowest p-6 rounded-2xl border border-surface-container shadow-sm hover:shadow-md transition-shadow group">
                        <div class="flex justify-between items-start mb-4">
                            <span class="px-2 py-1 bg-surface-container-high rounded text-[10px] font-bold text-on-surface-variant">#CR-<?= e((string) $report->id) ?></span>
                            <span class="text-[10px] font-black uppercase text-secondary"><?= e($report->status) ?></span>
                        </div>
                        <p class="text-sm font-medium text-on-surface mb-4 line-clamp-2">"<?= e($report->description) ?>"</p>
                        <div class="flex items-center justify-between mt-auto pt-4 border-t border-surface transition-colors">
                            <div class="flex items-center gap-2">
                                <span class="material-symbols-outlined text-sm text-outline-variant" data-icon="location_on">location_on</span>
                                <span class="text-[10px] font-bold text-on-surface-variant truncate max-w-[120px]"><?= e($report->location) ?></span>
                            </div>
                            <a href="<?= url('app_evidence_report', ['id' => $report->id]) ?>" class="text-primary group-hover:translate-x-1 transition-transform" target="_blank" rel="noopener">
                                <span class="material-symbols-outlined" data-icon="arrow_forward">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>
