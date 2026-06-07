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
        ]);
    }

    private function store(): void
    {
        $vehicleNumber = trim((string) request_post('vehicle_number', ''));
        $driverLicence = trim((string) request_post('driver_licence', ''));
        $violationType = trim((string) request_post('violation_type', ''));
        $fineAmountRaw = request_post('fine_amount');
        $location = trim((string) request_post('location', 'Not specified'));

        if ($vehicleNumber === '' || $driverLicence === '' || $violationType === '' || $fineAmountRaw === null || $fineAmountRaw === '') {
            flash('error', 'Vehicle number, driver licence, violation type, and fine amount are required.');
            redirect(url('app_violations'));
        }

        $fineAmount = (int) round((float) $fineAmountRaw);
        if ($fineAmount <= 0) {
            flash('error', 'Fine amount must be greater than zero.');
            redirect(url('app_violations'));
        }

        $driver = $this->users->findByLicenceNo((int) preg_replace('/[^0-9]/', '', $driverLicence));
        if (!$driver) {
            flash('error', 'No driver found with that licence number.');
            redirect(url('app_violations'));
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

        flash('success', 'Violation recorded successfully.');
        redirect(url('app_police'));
    }
}
