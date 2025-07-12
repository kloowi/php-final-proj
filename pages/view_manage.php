<?php
session_start();
$scriptPath = $_SERVER['SCRIPT_NAME'];
$isIndex = (basename($scriptPath) === 'index.php');
$headerClass = $isIndex ? 'transparent' : 'white-bg';
$basePath = $isIndex ? 'assets' : '../assets';
$currentPage = basename($scriptPath);

include '../includes/header-index.php';

// Mock multiple bookings
$bookings = [
    'A1BC23' => [
        'code' => 'A1BC23',
        'status' => 'Confirmed',
        'booked_on' => '3 Jul 2025',
        'experience' => 'Fort Santiago (Intramuros)',
        'date' => '18 Jul 2025',
        'start_time' => '9:00',
        'end_time' => '11:00',
        'guests' => ['Chloe Carbonell', 'Miryl De Leon', 'Alliah Montes', 'Olen Tamayo']
    ],
    'X9YZ88' => [
        'code' => 'X9YZ88',
        'status' => 'Completed',
        'booked_on' => '15 Jun 2025',
        'experience' => 'Rizal Park Tour',
        'date' => '25 Jun 2025',
        'start_time' => '2:00',
        'end_time' => '4:00',
        'guests' => ['Chloe Carbonell']
    ]
];

// Get booking code from query string
$bookingCode = $_GET['code'] ?? null;

// Fetch the booking details
$booking = $bookings[$bookingCode] ?? null;

if (!$booking) {
    echo "<p style='padding: 30px;'>Invalid booking code.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Booking</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/view_manage.css">
</head>
<body>

<section class="content">
    <!-- Booking Summary -->
    <div class="summary-box mt-18">
        <div><span class="label"><?= $booking['status'] ?></span></div>
        <div>Booked on<br><?= $booking['booked_on'] ?></div>
        <div class="code">Book Reference No.<br><?= $booking['code'] ?></div>
    </div>

    <!-- Experience Details -->
    <h3 class="section-title">Experience Details</h3>
    <div class="card mt-16">
        <div class="experience-row">
            <div class="experience-main">
                <?= $booking['experience'] ?>
            </div>
            <div class="experience-details">
                <div class="experience-detail"><span class="label">Date</span><br><?= $booking['date'] ?></div>
                <div class="experience-detail"><span class="label">Time</span><br><?= $booking['start_time'] ?> - <?= $booking['end_time'] ?></div>
                <div class="experience-detail"><span class="label">Guest Number</span><br><?= count($booking['guests']) ?></div>
            </div>
        </div>
    </div>

    <!-- Guest Details -->
    <h3 class="section-title">Guest Details</h3>
    <div class="card mt-16">
        <?php foreach ($booking['guests'] as $guest): ?>
            <div class="guest-block">
                <span class="guest-name"><?= $guest ?></span>
                <span class="guest-experience"><?= $booking['experience'] ?></span>
            </div>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>
