<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use App\Repository\ViolationRepository;

final class AdminController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
        private readonly VehicleRepository $vehicles = new VehicleRepository(),
    ) {
    }

    public function index(): void
    {
        render('admin/index', [
            'pageTitle' => 'Admin Dashboard | Civic Flow',
            'stats' => [
                'totalUsers' => $this->users->countAll(),
                'totalViolations' => $this->violations->countAll(),
                'totalReports' => $this->reports->countAll(),
                'totalVehicles' => $this->vehicles->countAll(),
                'unpaidDues' => $this->violations->sumOutstandingByAll(),
            ],
            'recentUsers' => $this->users->findRecent(5),
            'recentViolations' => $this->violations->findRecent(5),
            'recentReports' => $this->reports->findRecent(5),
        ]);
    }
}
