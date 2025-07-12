<?php include '../includes/header.php'; ?>
<link rel="stylesheet" href="../assets/css/confirmation.css">

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
            <span class="order-number">#<?php echo isset($_GET['order_id']) ? htmlspecialchars($_GET['order_id']) : 'a1bc23'; ?></span>
        </div>
        
        <!-- Booking Details -->
        <div class="booking-details">
            <div class="detail-row">
                <span class="detail-label">Booked Date</span>
                <span class="detail-value"><?php echo isset($_GET['date']) ? htmlspecialchars($_GET['date']) : '18 Jul 2025'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time</span>
                <span class="detail-value"><?php echo isset($_GET['time']) ? htmlspecialchars($_GET['time']) : '9:00 - 11:00'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Experience</span>
                <span class="detail-value"><?php echo isset($_GET['experience']) ? htmlspecialchars($_GET['experience']) : 'Fort Santiago (Intramuros)'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Payment Method</span>
                <span class="detail-value"><?php echo isset($_GET['payment']) ? htmlspecialchars($_GET['payment']) : 'GCash'; ?></span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Total</span>
                <span class="detail-value">₱<?php echo isset($_GET['amount']) ? htmlspecialchars($_GET['amount']) : '1,234'; ?></span>
            </div>
        </div>
        <!-- Done Button -->
        <div class="action-buttons">
            <a href="../index.php" class="btn btn-primary">Done</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?> 