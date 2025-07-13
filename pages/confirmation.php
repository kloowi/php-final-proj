<?php include '../includes/header.php'; ?>
<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/confirmation.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>

<?php
// Get booking details from GET parameters
$booking_code = $_GET['booking_code'] ?? '';
$experience_id = $_GET['experience_id'] ?? '';
$title = $_GET['title'] ?? '';
$price = $_GET['price'] ?? '';
$selected_date = $_GET['selected_date'] ?? '';
$selected_time = $_GET['selected_time'] ?? '';
$guest_count = $_GET['guest_count'] ?? '';
$payment_method = $_GET['payment_method'] ?? '';
$total = $_GET['total'] ?? '';
$order_id = $_GET['order_id'] ?? '';

// Fallbacks for display
if (!$booking_code && $order_id) $booking_code = $order_id;
if (!$booking_code) $booking_code = 'A1BC23';
if (!$selected_date) $selected_date = '18 Jul 2025';
if (!$selected_time) $selected_time = '9:00 - 11:00';
if (!$title) $title = 'Fort Santiago (Intramuros)';
if (!$payment_method) $payment_method = 'GCash';
if (!$total && $price && $guest_count) $total = number_format($price * $guest_count, 2);
if (!$total) $total = '1,234';
?>

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
            <span class="order-number">#<?php echo htmlspecialchars($booking_code); ?></span>
        </div>
        
        <!-- Booking Details -->
        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Booked Date</span>
                <span class="detail-value"><?php echo htmlspecialchars($selected_date); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value"><?php echo htmlspecialchars($selected_time); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Experience</span>
                <span class="detail-value"><?php echo htmlspecialchars($title); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Guests</span>
                <span class="detail-value"><?php echo htmlspecialchars($guest_count); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value"><?php echo htmlspecialchars($payment_method); ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total</span>
                <span class="detail-value">₱<?php echo htmlspecialchars($total); ?></span>
            </div>
        </div>
        <!-- Done Button -->
        <div class="action-buttons">
            <a href="../index.php" class="btn btn-primary">Done</a>
        </div>
    </div>
</div>