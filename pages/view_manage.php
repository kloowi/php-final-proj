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
    $sql = "SELECT b.*, e.title AS experience_title, e.location, e.price, e.duration, u.full_name, u.email
            FROM Bookings b
            JOIN Experiences e ON b.experience_id = e.experience_id
            JOIN Users u ON b.user_id = u.user_id
            WHERE b.booking_code = ? AND b.user_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$bookingCode, $user_id]);
    $booking = $stmt->fetch();
}

if (!$booking) {
    echo "<p style='padding: 30px;'>Invalid booking code.</p>";
    exit;
}

// Fetch payment info
$payment = null;
if ($pdo) {
    $stmt = $pdo->prepare('SELECT * FROM Payment WHERE booking_id = ? ORDER BY payment_id DESC LIMIT 1');
    $stmt->execute([$booking['booking_id']]);
    $payment = $stmt->fetch();
}

// Fetch guest names
$guests = [];
if ($pdo) {
    $stmt = $pdo->prepare('SELECT guest_name FROM Booking_Guests WHERE booking_id = ?');
    $stmt->execute([$booking['booking_id']]);
    $guests = $stmt->fetchAll(PDO::FETCH_COLUMN);
}

include '../includes/header-index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/view_manage.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>
<body>
<section class="content">
  <div class="center-group">
    <div class="unified-booking-card">
      <div class="card-header-row">
        <div class="booking-details-label">Booking Details</div>
        <a href="manage.php" class="back-btn card-arrow-btn">
          <i class="fas fa-arrow-left"></i>
        </a>
      </div>
      <div class="booking-card-header">
        <div class="booking-card-title">
          <span class="experience-title"><?= htmlspecialchars($booking['experience_title']) ?></span>
        </div>
        <div class="booking-code">
          <?= htmlspecialchars($booking['booking_code']) ?>
        </div>
      </div>
      <div class="booking-card-body">
        <div class="booking-card-left">
          <div class="detail-row">
            <span class="detail-label">Date</span>
            <span class="detail-value"><b><?= htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))) ?></b></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Time</span>
            <span class="detail-value"><b><?= isset($booking['selected_time']) ? htmlspecialchars($booking['selected_time']) : (isset($booking['duration']) ? htmlspecialchars($booking['duration']) : 'N/A') ?></b></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Payment Method</span>
            <span class="detail-value"><b><?= $payment ? htmlspecialchars(ucfirst($payment['payment_method'])) : 'N/A' ?></b></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Payment Date</span>
            <span class="detail-value"><b><?= $payment ? htmlspecialchars(date('M d, Y', strtotime($payment['payment_date']))) : 'N/A' ?></b></span>
          </div>
        </div>
        <div class="booking-card-right">
          <div class="detail-row">
            <span class="detail-label">Account Name</span>
            <span class="detail-value"><b><?= htmlspecialchars(strtoupper($booking['full_name'])) ?></b><br><span class="email-value"><?= htmlspecialchars($booking['email']) ?></span></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Total Number of Guests</span>
            <span class="detail-value"><b><?= htmlspecialchars($booking['number_of_guests']) ?></b></span>
          </div>
          <div class="detail-row">
            <span class="detail-label">Guest Names</span>
            <span class="detail-value"><b>
              <?php if ($guests): ?>
                <?php foreach ($guests as $g): ?>
                  <?= htmlspecialchars($g) ?><br>
                <?php endforeach; ?>
              <?php else: ?>
                N/A
              <?php endif; ?>
            </b></span>
          </div>
        </div>
      </div>
      <div class="booking-card-footer">
        <div class="total-label">Total</div>
        <div class="total-value">₱<?= $payment ? htmlspecialchars(number_format($payment['amount'], 2)) : '0.00' ?></div>
      </div>
    </div>
  </div>
</section>
<?php include '../includes/footer.php'; ?>
</body>
</html>
