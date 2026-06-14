<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\ViolationRepository;

final class ViolationController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
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
            flash('error', 'Only police officers can record violations.');
            redirect(url('app_citizen'));
        }

        if (request_method() === 'POST') {
            $this->store();
            return;
        }

        $reportId = isset($_GET['report_id']) ? (int) $_GET['report_id'] : null;
        $report = null;
        if ($reportId !== null) {
            $reportRepo = new \App\Repository\CitizenReportRepository();
            $report = $reportRepo->findById($reportId);
        }

        render('violation/index', [
            'pageTitle' => 'Add Traffic Violation | Dash Cam',
            'violationTypes' => [
                'Speeding (20km+ over limit)',
                'Running Red Light',
                'Illegal Parking in Transit Zone',
                'Failure to Yield to Pedestrian',
                'Using Mobile Device while Driving',
            ],
            'user' => $user,
            'report' => $report,
        ]);
    }

    private function store(): void
    {
        $vehicleNumber = trim((string) request_post('vehicle_number', ''));
        $driverLicence = trim((string) request_post('driver_licence', ''));
        $violationType = trim((string) request_post('violation_type', ''));
        $fineAmountRaw = request_post('fine_amount');
        $location = trim((string) request_post('location', 'Not specified'));
        $reportId = request_post('report_id') !== null ? (int) request_post('report_id') : null;

        $redirectUrl = url('app_violations') . ($reportId !== null ? '?report_id=' . $reportId : '');

        if ($vehicleNumber === '' || $driverLicence === '' || $violationType === '' || $fineAmountRaw === null || $fineAmountRaw === '') {
            flash('error', 'Vehicle number, driver licence, violation type, and fine amount are required.');
            redirect($redirectUrl);
        }

        $fineAmount = (int) round((float) $fineAmountRaw);
        if ($fineAmount <= 0) {
            flash('error', 'Fine amount must be greater than zero.');
            redirect($redirectUrl);
        }

        $driver = $this->users->findByLicenceNo((string) $driverLicence);
        if (!$driver) {
            // Try matching numeric digits only as a fallback in case prefix was omitted or typed differently
            $licenceDigits = (int) preg_replace('/[^0-9]/', '', $driverLicence);
            $driver = $this->users->findByLicenceNo((string) $licenceDigits);
            if (!$driver) {
                // Try find by badge/license matching without cast
                $driver = $this->users->findByLicenceNo(trim($driverLicence));
            }
        }

        if (!$driver) {
            flash('error', 'No driver found with that licence number.');
            redirect($redirectUrl);
        }

        $now = (new \DateTime())->format('Y-m-d H:i:s');

        $this->violations->create([
            'vehicle_id' => $vehicleNumber,
            'driver_id' => (int) $driver->id,
            'violation_type' => $violationType,
            'fine_amount' => $fineAmount,
            'status' => 'Pending',
            'description' => $violationType,
            'location' => $location,
            'vehicle_number' => $vehicleNumber,
            'incident_date' => $now,
            'created_at' => $now,
        ]);

        $notificationRepo = new \App\Repository\NotificationRepository();
        
        // 1. Notify the driver who committed the violation
        $fineAmountFormatted = number_format((float) $fineAmount, 2);
        $driverMsg = "A traffic fine of \${$fineAmountFormatted} has been issued to you for '{$violationType}' at {$location}. Please settle it as soon as possible.";
        $notificationRepo->create([
            'user_id' => $driver->id,
            'title' => 'New Traffic Fine Issued',
            'message' => $driverMsg,
            'created_at' => $now,
        ]);

        // 2. If it was from a citizen report, update status and notify reporter
        if ($reportId !== null) {
            $reportRepo = new \App\Repository\CitizenReportRepository();
            $report = $reportRepo->findById($reportId);
            if ($report) {
                $reportRepo->updateStatus($reportId, 'Approved');
                
                if ($report->userId !== null) {
                    $reporterMsg = "Your report #CR-{$reportId} regarding '{$report->description}' at {$report->location} has been approved, and a citation has been issued to the violator. Thank you for your contribution to road safety!";
                    $notificationRepo->create([
                        'user_id' => $report->userId,
                        'title' => 'Citizen Report Approved',
                        'message' => $reporterMsg,
                        'created_at' => $now,
                    ]);
                }
            }
        }

        flash('success', 'Violation recorded successfully and notifications sent.');
        redirect(url('app_police'));
    }
}
