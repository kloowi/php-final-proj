<?php
session_start();
$scriptPath = $_SERVER['SCRIPT_NAME'];
$isIndex = (basename($scriptPath) === 'index.php');
$headerClass = $isIndex ? 'transparent' : 'white-bg';
$basePath = $isIndex ? 'assets' : '../assets';
$currentPage = basename($scriptPath);

include '../includes/header.php';

// Sample bookings
$bookings = [
    [
        'code' => 'A1BC23',
        'status' => 'Confirmed',
        'booked_on' => '3 Jul 2025',
        'experience' => 'Fort Santiago (Intramuros)',
        'date' => '18 Jul 2025',
        'start_time' => '9:00 AM',
        'end_time' => '11:00 AM',
        'guests' => ['Chloe Carbonell', 'Miryl De Leon', 'Alliah Montes', 'Olen Tamayo']
    ],
    [
        'code' => 'X9YZ88',
        'status' => 'Completed',
        'booked_on' => '15 Jun 2025',
        'experience' => 'Rizal Park Tour',
        'date' => '25 Jun 2025',
        'start_time' => '2:00 PM',
        'end_time' => '4:00 PM',
        'guests' => ['Chloe Carbonell']
    ]
];

// Filter logic
$filter = $_GET['filter'] ?? '';
$today = date('Y-m-d');
$filteredBookings = array_filter($bookings, function ($booking) use ($filter, $today) {
    $bookingDate = date('Y-m-d', strtotime($booking['date']));
    if ($filter === 'upcoming') {
        return $bookingDate >= $today;
    } elseif ($filter === 'past') {
        return $bookingDate < $today;
    }
    return true;
});
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings</title>
    <link rel="stylesheet" href="<?php echo $basePath; ?>/css/manage.css">
</head>
<body>
<section class="hero-image">
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
                <th>Date and Time</th>
                <th>Guests</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (empty($filteredBookings)): ?>
                <tr><td colspan="5">No bookings found for this filter.</td></tr>
            <?php else: ?>
                <?php foreach ($filteredBookings as $booking): ?>
                    <tr>
                        <td>
                            <strong><?= htmlspecialchars($booking['code']) ?></strong><br>
                            <?= htmlspecialchars($booking['status']) ?><br><br>
                            Booked on:<br> <?= htmlspecialchars($booking['booked_on']) ?>
                        </td>
                        <td><?= htmlspecialchars($booking['experience']) ?></td>
                        <td>
                            <?= htmlspecialchars($booking['date']) ?><br>
                            <?= htmlspecialchars($booking['start_time']) ?> – <?= htmlspecialchars($booking['end_time']) ?>
                        </td>
                        <td>
                            <ol>
                                <?php foreach ($booking['guests'] as $guest): ?>
                                    <li><?= htmlspecialchars($guest) ?></li>
                                <?php endforeach; ?>
                            </ol>
                        </td>
                        <td><button class="manage-btn">Manage</button></td>
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
