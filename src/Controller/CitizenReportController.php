<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;

final class CitizenReportController
{
    public function __construct(
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
    ) {
    }

    public function index(): void
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            redirect(url('app_login'));
        }

        $userRole = strtolower((string) ($_SESSION['user_role'] ?? ''));

        if (request_method() === 'POST' && $userRole !== 'police') {
            $this->store((int) $userId);
            return;
        }

        if ($userRole === 'police') {
            $recentReports = $this->reports->findRecent(10);
            $totalReports = $this->reports->countAll();
        } else {
            $recentReports = $this->reports->findRecentByUser((int) $userId, 10);
            $totalReports = $this->reports->countByUser((int) $userId);
        }

        render('citizen_report/index', [
            'pageTitle' => 'Citizen Reporting Portal - Dash Cam',
            'recentReports' => $recentReports,
            'totalReports' => $totalReports,
            'isPoliceView' => $userRole === 'police',
        ]);
    }

    private function store(int $userId): void
    {
        $incidentDateRaw = (string) request_post('incident_date', '');
        $location = trim((string) request_post('location', ''));
        $description = trim((string) request_post('description', ''));
        $evidenceFile = request_file('evidence');

        if ($evidenceFile === null) {
            flash('error', 'Please select a file before submitting the report.');
            redirect(url('app_citizen_reports'));
        }

        if ($location === '' || $description === '' || $incidentDateRaw === '') {
            flash('error', 'Date, location and description are required.');
            redirect(url('app_citizen_reports'));
        }

        $extension = strtolower(pathinfo((string) $evidenceFile['name'], PATHINFO_EXTENSION));
        $allowedExtensions = ['png', 'jpg', 'jpeg', 'mp4'];
        $maxBytes = 25 * 1024 * 1024;

        if (!in_array($extension, $allowedExtensions, true)) {
            flash('error', 'Invalid file type. Only PNG, JPG, JPEG, and MP4 are allowed.');
            redirect(url('app_citizen_reports'));
        }

        if ((int) $evidenceFile['size'] > $maxBytes) {
            flash('error', 'File is too large. Maximum allowed size is 25MB.');
            redirect(url('app_citizen_reports'));
        }

        try {
            $incidentDate = new \DateTimeImmutable($incidentDateRaw);
        } catch (\Exception) {
            flash('error', 'Invalid incident date.');
            redirect(url('app_citizen_reports'));
        }

        $newFilename = 'evidence-' . bin2hex(random_bytes(8)) . '.' . $extension;
        $uploadDir = APP_ROOT . 'public/uploads/evidence';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newFilename;

        if (!move_uploaded_file((string) $evidenceFile['tmp_name'], $targetPath)) {
            flash('error', 'Evidence upload failed. Please try again.');
            redirect(url('app_citizen_reports'));
        }

        $this->reports->create([
            'incident_date' => $incidentDate->format('Y-m-d H:i:s'),
            'location' => $location,
            'description' => $description,
            'evidence_path' => '/uploads/evidence/' . $newFilename,
            'status' => 'Pending Review',
            'created_at' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
            'user_id' => $userId,
        ]);

        flash('success', 'Report submitted with evidence successfully.');
        redirect(url('app_citizen_reports'));
    }
}
