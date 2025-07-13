<?php
session_start();
require_once '../includes/db_config.php';

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    header('Location: login.php?redirect=manage.php');
    exit;
}

$filter = $_GET['filter'] ?? '';
$today = date('Y-m-d');
$bookings = [];

if ($pdo) {
    $sql = "SELECT b.booking_code, b.status, b.booking_date, b.number_of_guests, e.title AS experience
            FROM Bookings b
            JOIN Experiences e ON b.experience_id = e.experience_id
            WHERE b.user_id = ?
            ORDER BY b.booking_date DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    $bookings = $stmt->fetchAll();
}

$filteredBookings = array_filter($bookings, function ($booking) use ($filter, $today) {
    $bookingDate = $booking['booking_date'];
    if ($filter === 'upcoming') return $bookingDate >= $today;
    if ($filter === 'past') return $bookingDate < $today;
    return true;
});

include '../includes/header-index.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/manage.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>
<body>
<section class="hero-image">
    <img src="../assets/images/index/MANILA.jpg" alt="Manila Skyline">
    <div class="overlay-text">My Bookings</div>
</section>

<section class="content">
    <form class="filter-form" method="get">
        <label for="filter">Filter By:</label>
        <select id="filter" name="filter" onchange="this.form.submit()">
            <option value="" <?= $filter === '' ? 'selected' : '' ?>>Select Booking</option>
            <option value="upcoming" <?= $filter === 'upcoming' ? 'selected' : '' ?>>Upcoming Booking</option>
            <option value="past" <?= $filter === 'past' ? 'selected' : '' ?>>Past Booking</option>
        </select>
    </form>

    <div class="booking-card">
        <table>
            <thead>
            <tr>
                <th>Booking Details</th>
                <th>Experience</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($filteredBookings)): ?>
                <tr><td colspan="3">No bookings found for this filter.</td></tr>
            <?php else: ?>
                <?php foreach ($filteredBookings as $booking): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($booking['booking_code']) ?></strong><br>
                            <?= htmlspecialchars(ucfirst($booking['status'])) ?><br><br>
                            <br> <span class="booking-date"><?= htmlspecialchars(date('M d, Y', strtotime($booking['booking_date']))) ?></span><br>
                        </td>
                        <td><span class="experience-title"><?= htmlspecialchars($booking['experience']) ?></span></td>
                        <td>
                            <a href="view_manage.php?code=<?= urlencode($booking['booking_code']) ?>" class="manage-btn">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<?php include '../includes/footer.php'; ?>
</body>
</html>
