<?php
session_start();
require_once '../includes/db_config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php');
    exit;
}

$bookingCode = $_GET['code'] ?? null;
$booking = null;

if ($pdo && $bookingCode) {
    $sql = "SELECT b.*, e.title AS experience_title, e.location, e.price
            FROM Bookings b
            JOIN Experiences e ON b.experience_id = e.experience_id
            WHERE b.booking_code = ? AND b.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bookingCode, $user_id]);
    $booking = $stmt->fetch();
}

if (!$booking) {
    echo "<p style='padding: 30px;'>Invalid booking code.</p>";
    exit;
}

include '../includes/header-index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Booking</title>
    <link rel="stylesheet" href="../assets/css/view_manage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<section class="hero-image">
    <img src="../assets/images/index/MANILA.jpg" alt="Manila Skyline">
    <div class="overlay-text">View Booking</div>
</section>
<section class="content">
    <a href="manage.php" class="back-btn"><i class="fas fa-arrow-left"></i> Back</a>
    <!-- Booking Summary -->
    <div class="summary-box mt-48">
        <div><span class="label"><?= htmlspecialchars(ucfirst($booking['status'])) ?></span></div>
        <div>Booked for<br><?= htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))) ?></div>
        <div class="code">Book Reference No.<br><span class="code-value"><?= htmlspecialchars($booking['booking_code']) ?></span></div>
    </div>

    <!-- Experience Details -->
    <h3 class="section-title">Experience Details</h3>
    <div class="card mt-16">
        <div class="experience-details">
            <div class="experience-main"><?= htmlspecialchars($booking['experience_title']) ?></div>
            <div class="experience-right">
                <div class="experience-col">
                    <span class="label">Date</span>
                    <span><?= htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))) ?></span>
                </div>
                <div class="experience-col">
                    <span class="label">Time</span>
                    <span><?= isset($booking['selected_time']) ? htmlspecialchars($booking['selected_time']) : 'N/A' ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Payment Details -->
    <h3 class="section-title">Payment Details</h3>
    <div class="card mt-16 payment-details-flex">
        <div class="payment-status-title">Paid</div>
        <div class="payment-details-right">
        <?php
        // Fetch payment info
        $payment = null;
        if ($pdo) {
            $stmt = $pdo->prepare('SELECT * FROM Payment WHERE booking_id = ? ORDER BY payment_id DESC LIMIT 1');
            $stmt->execute([$booking['booking_id']]);
            $payment = $stmt->fetch();
        }
        ?>
        <?php if ($payment): ?>
            <div class="payment-detail-col">
                <span class="payment-label">Amount Paid</span>
                <span class="payment-value">₱<?= htmlspecialchars(number_format($payment['amount'], 2)) ?></span>
            </div>
            <div class="payment-detail-col">
                <span class="payment-label">Payment Method</span>
                <span class="payment-value"><?= htmlspecialchars($payment['payment_method']) ?></span>
            </div>
            <div class="payment-detail-col">
                <span class="payment-label">Payment Date</span>
                <span class="payment-value"><?= htmlspecialchars(date('M d, Y H:i', strtotime($payment['payment_date']))) ?></span>
            </div>
            <div class="payment-detail-col">
                <span class="payment-label">Payment Status</span>
                <span class="payment-value"><?= htmlspecialchars(ucfirst($payment['status'])) ?></span>
            </div>
        <?php else: ?>
            <div>No payment record found.</div>
        <?php endif; ?>
        </div>
    </div>

    <!-- Guest Details -->
    <h3 class="section-title">Guest Details</h3>
    <div class="card mt-16 guest-details-flex guest-details-align guest-details-align-center">
        <div class="payment-status-title">Registered</div>
        <div class="guest-details-right">
            <div class="guest-detail-col guest-detail-col-align">
                <span class="guest-label guest-label-bold">Number of Guests</span>
                <span class="guest-value guest-value-block"><?= htmlspecialchars($booking['number_of_guests']) ?></span>
            </div>
            <div class="guest-detail-col guest-detail-col-align">
                <span class="guest-label guest-label-bold">Guest Names</span>
                <?php
                // Fetch guest names
                $guests = [];
                if ($pdo) {
                    $stmt = $pdo->prepare('SELECT guest_name FROM Booking_Guests WHERE booking_id = ?');
                    $stmt->execute([$booking['booking_id']]);
                    $guests = $stmt->fetchAll(PDO::FETCH_COLUMN);
                }
                ?>
                <?php if ($guests): ?>
                    <?php foreach ($guests as $guest): ?>
                        <span class="guest-value guest-value-block"><?= htmlspecialchars($guest) ?></span>
                    <?php endforeach; ?>
                <?php else: ?>
                    <span class="guest-value guest-value-block">No guest details found.</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>
