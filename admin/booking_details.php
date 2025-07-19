<?php
require_once './authentication/auth.php';
requireAdminLogin();
$admin = getCurrentAdmin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Details - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="admin-layout">
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>Admin Panel</h3>
            </div>
            <ul class="sidebar-nav">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="experiences.php">Experiences</a></li>
                <li><a href="add_review.php">Manage Reviews</a></li>
                <li><a href="booking_details.php" class="active">Booking Details</a></li>
                <li><a href="/php-final-proj-main/admin/authentication/logout.php">Logout</a></li>
            </ul>
        </div>
        <div class="admin-content">
            <div class="admin-header">
                <h2 style="margin-top: 0; margin-bottom: 0;">Booking Details</h2>
            </div>
            <div style="background:#fff; border-radius:12px; box-shadow:0 2px 8px rgba(74,163,255,0.08); padding:32px; border:1px solid #e5e7eb;">
                <p>This is the Booking Details admin page. Here you can manage and view booking details for each experience. (Placeholder)</p>
            </div>
        </div>
    </div>
</body>
</html> 