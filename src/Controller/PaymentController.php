<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\NotificationRepository;
use App\Repository\UserRepository;
use App\Repository\ViolationRepository;

final class PaymentController
{
    public function __construct(
        private readonly UserRepository $users = new UserRepository(),
        private readonly ViolationRepository $violations = new ViolationRepository(),
        private readonly NotificationRepository $notifications = new NotificationRepository(),
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
        
        $violationId = isset($_GET['violation_id']) ? (int) $_GET['violation_id'] : null;
        $type = isset($_GET['type']) ? (string) $_GET['type'] : null;

        $violationsToPay = [];
        $paymentType = 'single';

        if ($type === 'all') {
            $violationsToPay = $this->violations->findPendingByDriver((int) $user->id);
            $paymentType = 'all';
        } elseif ($violationId !== null) {
            $violation = $this->violations->findById($violationId);
            if ($violation && (int) $violation->driverId === (int) $user->id && in_array($violation->status, ['Pending', 'Unpaid'], true)) {
                $violationsToPay[] = $violation;
            }
        }

        if (empty($violationsToPay)) {
            flash('error', 'No outstanding violations found to pay.');
            redirect(url('app_citizen'));
        }

        $totalAmount = 0;
        foreach ($violationsToPay as $v) {
            $totalAmount += $v->fineAmount;
        }

        render('citizen/payment', [
            'pageTitle' => 'Settle Traffic Fines | Dash Cam',
            'user' => $user,
            'violations' => $violationsToPay,
            'totalAmount' => $totalAmount,
            'paymentType' => $paymentType,
        ]);
    }

    public function process(): void
    {
        $user = $this->requireUser();

        // Retrieve POST inputs
        $violationId = request_post('violation_id') !== null ? (int) request_post('violation_id') : null;
        $type = request_post('type') !== null ? (string) request_post('type') : null;
        
        $cardName = trim((string) request_post('card_name', ''));
        $cardNumber = preg_replace('/\s+/', '', (string) request_post('card_number', ''));
        $expiryDate = trim((string) request_post('expiry_date', ''));
        $cvv = trim((string) request_post('cvv', ''));

        // Basic credit card validation
        if ($cardName === '' || $cardNumber === '' || $expiryDate === '' || $cvv === '') {
            json_response(['success' => false, 'error' => 'All card details are required.'], 400);
        }

        if (!preg_match('/^[0-9]{13,19}$/', $cardNumber)) {
            json_response(['success' => false, 'error' => 'Invalid card number format.'], 400);
        }

        if (!preg_match('/^(0[1-9]|1[0-2])\/[0-9]{2}$/', $expiryDate)) {
            json_response(['success' => false, 'error' => 'Expiry date must be in MM/YY format.'], 400);
        }

        if (!preg_match('/^[0-9]{3,4}$/', $cvv)) {
            json_response(['success' => false, 'error' => 'CVV must be 3 or 4 digits.'], 400);
        }

        // Validate Expiry is in the future
        [$expMonth, $expYear] = explode('/', $expiryDate);
        $currentYear = (int) date('y');
        $currentMonth = (int) date('n');
        $expYearInt = (int) $expYear;
        $expMonthInt = (int) $expMonth;

        if ($expYearInt < $currentYear || ($expYearInt === $currentYear && $expMonthInt < $currentMonth)) {
            json_response(['success' => false, 'error' => 'The card has expired.'], 400);
        }

        $violationsToPay = [];

        if ($type === 'all') {
            $violationsToPay = $this->violations->findPendingByDriver((int) $user->id);
        } elseif ($violationId !== null) {
            $violation = $this->violations->findById($violationId);
            if ($violation && (int) $violation->driverId === (int) $user->id && in_array($violation->status, ['Pending', 'Unpaid'], true)) {
                $violationsToPay[] = $violation;
            }
        }

        if (empty($violationsToPay)) {
            json_response(['success' => false, 'error' => 'No outstanding violations found to pay.'], 400);
        }

        $totalAmount = 0;
        foreach ($violationsToPay as $v) {
            $totalAmount += $v->fineAmount;
        }

        // Update database records
        $now = (new \DateTime())->format('Y-m-d H:i:s');
        if ($type === 'all') {
            $this->violations->updateStatusByDriver((int) $user->id, 'Paid');
        } else {
            foreach ($violationsToPay as $v) {
                $this->violations->updateStatus((int) $v->id, 'Paid');
            }
        }

        // Create notification
        $totalFormatted = number_format((float) $totalAmount, 2);
        if ($type === 'all') {
            $msg = "A bulk payment of \${$totalFormatted} has been successfully processed. All your outstanding traffic fines have been cleared. Thank you for your payment!";
            $this->notifications->create([
                'user_id' => $user->id,
                'title' => 'Outstanding Fines Settled',
                'message' => $msg,
                'created_at' => $now,
            ]);
        } else {
            $violationType = $violationsToPay[0]->violationType;
            $msg = "Your payment of \${$totalFormatted} for fine #{$violationId} ({$violationType}) has been successfully processed. The violation has been marked as Paid.";
            $this->notifications->create([
                'user_id' => $user->id,
                'title' => 'Traffic Fine Paid',
                'message' => $msg,
                'created_at' => $now,
            ]);
        }

        json_response([
            'success' => true,
            'receipt_id' => 'REC-' . strtoupper(bin2hex(random_bytes(4))),
            'amount' => $totalAmount,
            'date' => $now,
            'message' => 'Payment successful'
        ]);
    }
}
