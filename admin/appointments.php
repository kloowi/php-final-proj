<?php
require_once 'auth.php';
requireAdminLogin();

$admin = getCurrentAdmin();

// Handle status updates
if ($_POST && isset($_POST['action']) && $_POST['action'] === 'update_status') {
    $appointment_id = (int)$_POST['appointment_id'];
    $new_status = $_POST['status'];

    $result = updateAppointmentStatus($appointment_id, $new_status);
    setFlashMessage($result['success'] ? 'success' : 'danger', $result['message']);

    header('Location: appointments.php');
    exit;
}

// Get filter parameters
$status_filter = $_GET['status'] ?? '';
$appointments = getAppointmentsAdmin(50, 0, $status_filter ?: null);

// Handle logout
if (isset($_GET['logout'])) {
    adminLogout();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Appointments - Admin Panel</title>
    <link rel="stylesheet" href="admin.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>
    <div class="admin-layout">
        <!-- Sidebar -->
        <div class="admin-sidebar">
            <div class="sidebar-header">
                <h3>SIM Admin</h3>
                <small>Management Panel</small>
            </div>
            <ul class="sidebar-nav">
                <?php foreach (getAdminNavigation() as $nav_item): ?>
                    <li>
                        <a href="<?php echo $nav_item['url']; ?>"
                            class="<?php echo basename($_SERVER['PHP_SELF']) === $nav_item['url'] ? 'active' : ''; ?>">
                            <i class="<?php echo $nav_item['icon']; ?>"></i>
                            <?php echo $nav_item['title']; ?>
                        </a>
                    </li>
                <?php endforeach; ?>
                <li>
                    <a href="?logout=1" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="admin-content">
            <div class="admin-header">
                <h1><i class="fas fa-calendar-check"></i> Manage Appointments</h1>
                <div class="admin-user">
                    <span>Welcome, <?php echo htmlspecialchars($admin['full_name']); ?></span>
                </div>
            </div>

            <?php displayFlashMessage(); ?>

            <!-- Filter Bar -->
            <div class="form-container">
                <h2><i class="fas fa-filter"></i> Filter Appointments</h2>
                <form method="GET" action="">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="status">Filter by Status</label>
                            <select id="status" name="status" class="form-control">
                                <option value="">All Statuses</option>
                                <option value="pending" <?php echo $status_filter === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                <option value="confirmed" <?php echo $status_filter === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                <option value="completed" <?php echo $status_filter === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                <option value="cancelled" <?php echo $status_filter === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                            </select>
                        </div>

                        <div class="form-group" style="display: flex; align-items: end;">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Filter
                            </button>
                            <a href="appointments.php" class="btn btn-secondary" style="margin-left: 10px;">
                                <i class="fas fa-times"></i> Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Appointments List -->
            <div class="table-container">
                <div class="table-header">
                    <h2><i class="fas fa-list"></i>
                        <?php echo $status_filter ? ucfirst($status_filter) . ' ' : 'All '; ?>Appointments
                    </h2>
                    <div style="color: #666; font-size: 14px;">
                        Showing: <?php echo count($appointments); ?> appointments
                    </div>
                </div>

                <?php if (!empty($appointments)): ?>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Customer Information</th>
                                <th>Experience</th>
                                <th>Schedule</th>
                                <th>Status</th>
                                <th>Booked</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($appointments as $appointment): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($appointment['patient_name']); ?></strong>
                                        <?php if ($appointment['patient_email']): ?>
                                            <div style="font-size: 12px; color: #666;">
                                                <i class="fas fa-envelope"></i> <?php echo htmlspecialchars($appointment['patient_email']); ?>
                                            </div>
                                        <?php endif; ?>
                                        <div style="font-size: 12px; color: #666;">
                                            <i class="fas fa-users"></i> <?php echo $appointment['number_of_guests']; ?> guests
                                        </div>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($appointment['service_name']); ?></strong>
                                    </td>
                                    <td>
                                        <div><strong><?php echo date('M j, Y', strtotime($appointment['booking_date'])); ?></strong></div>
                                        <?php if ($appointment['appointment_time']): ?>
                                            <div style="font-size: 12px; color: #666;">
                                                <i class="fas fa-clock"></i>
                                                <?php echo date('g:i A', strtotime($appointment['appointment_time'])); ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo $appointment['status']; ?>">
                                            <?php echo ucfirst($appointment['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="font-size: 12px;">
                                            <?php echo date('M j, Y', strtotime($appointment['created_at'])); ?>
                                        </div>
                                        <div style="font-size: 11px; color: #666;">
                                            <?php echo date('g:i A', strtotime($appointment['created_at'])); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <form method="POST" style="display: inline-block;">
                                            <input type="hidden" name="action" value="update_status">
                                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['booking_id']; ?>">
                                            <select name="status" class="form-control" style="width: auto; display: inline-block; font-size: 11px; padding: 4px;" onchange="this.form.submit()">
                                                <option value="pending" <?php echo $appointment['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                                <option value="confirmed" <?php echo $appointment['status'] === 'confirmed' ? 'selected' : ''; ?>>Confirmed</option>
                                                <option value="completed" <?php echo $appointment['status'] === 'completed' ? 'selected' : ''; ?>>Completed</option>
                                                <option value="cancelled" <?php echo $appointment['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="padding: 40px; text-align: center; color: #666;">
                        <i class="fas fa-calendar-times" style="font-size: 64px; margin-bottom: 20px; opacity: 0.3;"></i>
                        <h3>No Appointments Found</h3>
                        <p><?php echo $status_filter ? "No {$status_filter} appointments found." : "No appointments have been made yet."; ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        // Confirm status changes
        document.addEventListener('change', function(e) {
            if (e.target.name === 'status') {
                const appointmentRow = e.target.closest('tr');
                const customerName = appointmentRow.querySelector('td strong').textContent;
                const newStatus = e.target.value;

                if (!confirm(`Change status to "${newStatus}" for ${customerName}?`)) {
                    e.preventDefault();
                    return false;
                }
            }
        });
    </script>
</body>

</html> 