<?php
session_start();
$scriptPath = $_SERVER['SCRIPT_NAME'];
$isIndex = (basename($scriptPath) === 'index.php');
$headerClass = $isIndex ? 'transparent' : 'white-bg';
$basePath = $isIndex ? 'assets' : '../assets';
$currentPage = basename($scriptPath);

include '../includes/header.php';

// Mock booking data
$bookingCode = $_GET['code'] ?? null;

$booking = [
    'code' => 'A1BC23',
    'status' => 'Confirmed',
    'booked_on' => '3 Jul 2025',
    'experience' => 'Fort Santiago (Intramuros)',
    'date' => '18 Jul 2025',
    'start_time' => '9:00',
    'end_time' => '11:00',
    'guests' => ['Chloe Carbonell', 'Miryl De Leon', 'Alliah Montes', 'Olen Tamayo']
];

// Validate booking code
if (!$bookingCode || $bookingCode !== $booking['code']) {
    echo "<p style='padding: 30px;'>Invalid booking code.</p>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage</title>
    <!-- ✅ Include both shared and page-specific CSS -->
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/manage.css">
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/view_manage.css">
</head>
<body>

<section class="content">
    <!-- Booking Summary -->
    <div class="summary-box">
        <div><span class="label"><?= $booking['status'] ?></span></div>
        <div>Booked on<br><?= $booking['booked_on'] ?></div>
        <div class="code">Book Reference No.<br><?= $booking['code'] ?></div>
    </div>

    <!-- Experience Details -->
    <div class="card">
        <h3>Experience Details</h3>
        <p><span class="label">Experience:</span> 
           <a href="#" class="experience-link"><?= $booking['experience'] ?></a></p>
        <p><span class="label">Date:</span> <?= $booking['date'] ?></p>
        <p><span class="label">Time:</span> <?= $booking['start_time'] ?> - <?= $booking['end_time'] ?></p>
        <p><span class="label">Guest Number:</span> <?= count($booking['guests']) ?></p>
    </div>

    <!-- Guest Details -->
    <div class="card">
        <h3>Guest Details</h3>
        <?php foreach ($booking['guests'] as $guest): ?>
            <p><span class="guest-name"><?= $guest ?></span><br>
               <span class="guest-experience"><?= $booking['experience'] ?></span></p>
        <?php endforeach; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>
