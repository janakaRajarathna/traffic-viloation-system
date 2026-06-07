<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;
use App\Repository\UserRepository;
use App\Repository\ViolationRepository;

final class PoliceController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
    ) {
    }

    public function index(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        $userRole = strtolower((string) ($_SESSION['user_role'] ?? ''));

        if (!$userId) {
            redirect(url('app_login'));
        }

        $user = $this->users->findById((int) $userId);
        if (!$user) {
            redirect(url('app_login'));
        }

        if ($userRole !== 'police') {
            redirect(url('app_citizen'));
        }

        $totalViolations = $this->violations->countAll();
        $pendingViolations = $this->violations->countPending();
        $paidViolations = $this->violations->countPaid();
        $closureRate = $totalViolations > 0
            ? round(($paidViolations / $totalViolations) * 100, 1)
            : 0.0;

        $now = new \DateTimeImmutable();
        $startOfMonth = $now->modify('first day of this month')->setTime(0, 0, 0);
        $startOfLastMonth = $startOfMonth->modify('-1 month');
        $violationsThisMonth = $this->violations->countSince($startOfMonth);
        $violationsLastMonth = $this->violations->countBetween($startOfLastMonth, $startOfMonth);

        if ($violationsLastMonth > 0) {
            $trendPercent = round((($violationsThisMonth - $violationsLastMonth) / $violationsLastMonth) * 100);
            $monthlyTrend = ($trendPercent >= 0 ? '+' : '') . $trendPercent . '% vs last month';
        } elseif ($violationsThisMonth > 0) {
            $monthlyTrend = '+' . $violationsThisMonth . ' this month';
        } else {
            $monthlyTrend = 'No change';
        }

        render('police/index', [
            'pageTitle' => 'Dash Cam | Enforcement Portal',
            'user' => $user,
            'totalViolations' => $totalViolations,
            'pendingViolations' => $pendingViolations,
            'pendingCitizenReports' => $this->reports->countPendingReview(),
            'totalCitizenReports' => $this->reports->countAll(),
            'closureRate' => $closureRate,
            'monthlyTrend' => $monthlyTrend,
            'recentViolations' => $this->violations->findRecent(10),
            'recentCitizenReports' => $this->reports->findRecent(5),
            'topLocation' => $this->violations->getMostCommonLocation(),
        ]);
    }
}
