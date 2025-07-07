<?php
// Test admin panel functionality
require_once '../includes/admin_functions.php';

echo "<h1>Admin Panel Test</h1>";

// Test database connection
if ($pdo) {
    echo "<p style='color: green;'>✓ Database connection successful</p>";
} else {
    echo "<p style='color: red;'>✗ Database connection failed</p>";
}

// Test admin authentication
$test_admin = authenticateAdmin('admin', 'admin123');
if ($test_admin) {
    echo "<p style='color: green;'>✓ Admin authentication working</p>";
} else {
    echo "<p style='color: red;'>✗ Admin authentication failed</p>";
}

// Test dashboard stats
$stats = getDashboardStats();
echo "<h2>Dashboard Stats:</h2>";
echo "<ul>";
foreach ($stats as $key => $value) {
    echo "<li><strong>$key:</strong> $value</li>";
}
echo "</ul>";

// Test announcements
$announcements = getAnnouncementsAdmin(5, 0);
echo "<h2>Announcements:</h2>";
if (!empty($announcements)) {
    echo "<ul>";
    foreach ($announcements as $announcement) {
        echo "<li>{$announcement['title']} - {$announcement['announcement_date']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No announcements found</p>";
}

// Test appointments
$appointments = getAppointmentsAdmin(5, 0);
echo "<h2>Appointments:</h2>";
if (!empty($appointments)) {
    echo "<ul>";
    foreach ($appointments as $appointment) {
        echo "<li>{$appointment['patient_name']} - {$appointment['service_name']} - {$appointment['status']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No appointments found</p>";
}

// Test services
$services = getServicesAdmin();
echo "<h2>Services:</h2>";
if (!empty($services)) {
    echo "<ul>";
    foreach ($services as $service) {
        echo "<li>{$service['title']} - \${$service['price']}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No services found</p>";
}

echo "<h2>Admin Panel Links:</h2>";
echo "<ul>";
echo "<li><a href='login.php'>Login Page</a></li>";
echo "<li><a href='dashboard.php'>Dashboard</a></li>";
echo "<li><a href='announcements.php'>Announcements</a></li>";
echo "<li><a href='appointments.php'>Appointments</a></li>";
echo "<li><a href='services.php'>Services</a></li>";
echo "<li><a href='schedules.php'>Schedules</a></li>";
echo "</ul>";
?> 