<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\CitizenReportRepository;
use App\Repository\UserRepository;
use App\Repository\VehicleRepository;
use App\Repository\ViolationRepository;

final class CitizenController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
        private readonly CitizenReportRepository $reports = new CitizenReportRepository(),
        private readonly VehicleRepository $vehicles = new VehicleRepository(),
    ) {
    }

    private function requireUser(): object
    {
        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            redirect(url('app_login'));
        }

        $user = $this->users->findById((int) $userId);
        if (!$user) {
            redirect(url('app_login'));
        }

        return $user;
    }

    public function index(): void
    {
        $user = $this->requireUser();
        $hasLicense = $user->licenceNo !== null;
        $violations = [];
        $outstandingTotal = 0;
        $pendingViolationCount = 0;

        if ($hasLicense) {
            $violations = $this->violations->findRecentByDriver((int) $user->id);
            $outstandingTotal = $this->violations->sumOutstandingByDriver((int) $user->id);
            $pendingViolationCount = $this->violations->countPendingByDriver((int) $user->id);
        }

        $citizenReports = $this->reports->findRecentByUser((int) $user->id, 10);
        $reportsCount = $this->reports->countByUser((int) $user->id);

        render('citizen/index', [
            'pageTitle' => 'Dash Cam | Citizen Dashboard',
            'user' => $user,
            'hasLicense' => $hasLicense,
            'violations' => $violations,
            'citizenReports' => $citizenReports,
            'outstandingTotal' => $outstandingTotal,
            'pendingViolationCount' => $pendingViolationCount,
            'reportsCount' => $reportsCount,
        ]);
    }

    public function profile(): void
    {
        $user = $this->requireUser();
        $user->vehicles = $this->vehicles->findByOwnerId((int) $user->id);

        render('citizen/profile', [
            'pageTitle' => 'Profile | Civic Flow',
            'user' => $user,
        ]);
    }

    public function settings(): void
    {
        $user = $this->requireUser();

        if (request_method() === 'POST') {
            $action = (string) request_post('action', '');

            if ($action === 'update_profile') {
                $fullName = trim((string) request_post('full_name', ''));
                $telNo = (string) request_post('tel_no', '');
                $licenceNo = request_post('licence_no');
                
                $removeProfilePic = request_post('remove_profile_pic') === '1';
                $profilePicFile = request_file('profile_pic');

                if ($fullName === '' || $telNo === '') {
                    flash('error', 'Full Name and Telephone Number are required.');
                    redirect(url('app_citizen_settings'));
                }

                // Telephone validation: exactly 10 digits
                if (!preg_match('/^[0-9]{10}$/', $telNo)) {
                    flash('error', 'Phone number must contain exactly 10 digits.');
                    redirect(url('app_citizen_settings'));
                }

                // License number validation: optional, but if provided, must start with capital B and contain 7 digits
                $licenceNoStr = ($licenceNo !== null && trim((string) $licenceNo) !== '') ? trim((string) $licenceNo) : null;
                if ($licenceNoStr !== null && !preg_match('/^B[0-9]{7}$/', $licenceNoStr)) {
                    flash('error', 'License number must start with a capital B followed by exactly 7 digits (e.g. B1234567).');
                    redirect(url('app_citizen_settings'));
                }

                $telNoInt = (int) preg_replace('/[^0-9]/', '', $telNo);
                $profilePicPath = null;
                $updateProfilePic = false;

                if ($removeProfilePic) {
                    if ($user->profilePic) {
                        $oldFile = APP_ROOT . 'public' . $user->profilePic;
                        if (is_file($oldFile)) {
                            @unlink($oldFile);
                        }
                    }
                    $profilePicPath = null;
                    $updateProfilePic = true;
                } elseif ($profilePicFile !== null) {
                    if (($profilePicFile['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
                        $errMap = [
                            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the PHP upload limit (' . format_bytes(parse_ini_size(ini_get('upload_max_filesize')), 0) . ') configured in php.ini. Please upload a smaller image or contact the administrator to increase the limit.',
                            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
                            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
                            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
                            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
                            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
                            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
                        ];
                        $errMsg = $errMap[$profilePicFile['error']] ?? 'Unknown upload error.';
                        flash('error', 'Upload failed: ' . $errMsg);
                        redirect(url('app_citizen_settings'));
                    }

                    $extension = strtolower(pathinfo((string) $profilePicFile['name'], PATHINFO_EXTENSION));
                    $allowedExtensions = ['png', 'jpg', 'jpeg'];
                    $maxBytes = get_max_upload_size();

                    if (!in_array($extension, $allowedExtensions, true)) {
                        flash('error', 'Invalid image type. Only PNG, JPG, and JPEG are allowed.');
                        redirect(url('app_citizen_settings'));
                    }

                    if ((int) $profilePicFile['size'] > $maxBytes) {
                        flash('error', 'Image is too large. Maximum allowed size is ' . format_bytes($maxBytes) . '.');
                        redirect(url('app_citizen_settings'));
                    }

                    $uploadDir = APP_ROOT . 'public/uploads/profile';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0775, true);
                    }

                    $newFilename = 'profile-' . (int) $user->id . '-' . bin2hex(random_bytes(8)) . '.' . $extension;
                    $targetPath = $uploadDir . DIRECTORY_SEPARATOR . $newFilename;

                    if (move_uploaded_file((string) $profilePicFile['tmp_name'], $targetPath)) {
                        if ($user->profilePic) {
                            $oldFile = APP_ROOT . 'public' . $user->profilePic;
                            if (is_file($oldFile)) {
                                @unlink($oldFile);
                            }
                        }
                        $profilePicPath = '/uploads/profile/' . $newFilename;
                        $updateProfilePic = true;
                    } else {
                        $lastErr = error_get_last();
                        $detail = $lastErr ? ' Error details: ' . $lastErr['message'] : '';
                        flash('error', 'Failed to upload profile picture.' . $detail);
                        redirect(url('app_citizen_settings'));
                    }
                }

                $this->users->updateProfile((int) $user->id, $fullName, $licenceNoStr, $telNoInt, $profilePicPath, $updateProfilePic);
                flash('success', 'Personal details updated successfully.');
                redirect(url('app_citizen_settings'));
            }

            if ($action === 'update_password') {
                $currentPassword = (string) request_post('current_password', '');
                $newPassword = (string) request_post('new_password', '');
                $confirmPassword = (string) request_post('confirm_password', '');

                if ($currentPassword === '' || $newPassword === '' || $confirmPassword === '') {
                    flash('error', 'All password fields are required.');
                } elseif (!password_verify($currentPassword, $user->password) && $currentPassword !== $user->password) {
                    flash('error', 'Current password is incorrect.');
                } elseif ($newPassword !== $confirmPassword) {
                    flash('error', 'New password and confirm password do not match.');
                } else {
                    $this->users->updatePassword((int) $user->id, password_hash($newPassword, PASSWORD_DEFAULT));
                    flash('success', 'Password updated successfully.');
                }
                redirect(url('app_citizen_settings'));
            }
        }

        render('citizen/settings', [
            'pageTitle' => 'Settings | Civic Flow',
            'user' => $user,
        ]);
    }

    public function notifications(): void
    {
        $user = $this->requireUser();

        $notificationRepo = new \App\Repository\NotificationRepository();
        // Mark all as read when visiting
        $notificationRepo->markAllAsReadByUser((int) $user->id);

        $notifications = $notificationRepo->findByUser((int) $user->id, 50);

        render('citizen/notifications', [
            'pageTitle' => 'My Notifications | Civic Flow',
            'user' => $user,
            'notifications' => $notifications,
        ]);
    }
}
