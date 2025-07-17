<?php
include '../includes/header.php';
require_once '../includes/db_config.php';

$booking_code = $_GET['booking_code'] ?? '';
$booking_id = $_GET['booking_id'] ?? '';

$booking = null;
$payment = null;
$guests = [];

if ($pdo && $booking_code && $booking_id) {
    // Fetch booking info
    $stmt = $pdo->prepare('SELECT b.*, e.title AS experience_title FROM Bookings b JOIN Experiences e ON b.experience_id = e.experience_id WHERE b.booking_code = ? AND b.booking_id = ?');
    $stmt->execute([$booking_code, $booking_id]);
    $booking = $stmt->fetch();

    // Fetch payment info
    $stmt = $pdo->prepare('SELECT * FROM Payment WHERE booking_id = ? ORDER BY payment_id DESC LIMIT 1');
    $stmt->execute([$booking_id]);
    $payment = $stmt->fetch();

    // Fetch guest names
    $stmt = $pdo->prepare('SELECT guest_name FROM Booking_Guests WHERE booking_id = ?');
    $stmt->execute([$booking_id]);
    $guests = $stmt->fetchAll(PDO::FETCH_COLUMN);
}
?>
<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/confirmation.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>

<div class="confirmation-container">
    <div class="confirmation-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <img src="../assets/images/confirmation/checkmark.png" alt="Success" class="checkmark-icon">
        </div>
        <!-- Main Title -->
        <h1 class="main-title">Booking Completed</h1>
        <!-- Order Number -->
        <div class="order-number-container">
            <span class="order-label">Book ID</span>
            <span class="order-number">#<?= htmlspecialchars($booking['booking_code'] ?? '-') ?></span>
        </div>
        <!-- Booking Details -->
        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Booked Date</span>
                <span class="detail-value"><?= isset($booking['booking_date']) ? htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value"><?= htmlspecialchars($booking['selected_time'] ?? '-') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Experience</span>
                <span class="detail-value"><?= htmlspecialchars($booking['experience_title'] ?? '-') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guests</span>
                <span class="detail-value"><?= htmlspecialchars($booking['number_of_guests'] ?? '-') ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value"><?= $payment ? htmlspecialchars(ucfirst($payment['payment_method'])) : '-' ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total</span>
                <span class="detail-value">₱<?= $payment ? htmlspecialchars(number_format($payment['amount'], 2)) : '-' ?></span>
            </div>
        </div>
        <!-- Done Button -->
        <div class="action-buttons">
            <a href="../index.php" class="btn btn-primary">Done</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>