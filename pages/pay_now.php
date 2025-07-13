<?php 
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Store current URL in session for redirect after login
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

// Accept POST from guest_details.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experience_id = isset($_POST['experience_id']) ? (int)$_POST['experience_id'] : 0;
    $title = $_POST['title'] ?? '';
    $price = isset($_POST['price']) ? (float)$_POST['price'] : 0;
    $selected_date = $_POST['selected_date'] ?? '';
    $selected_time = $_POST['selected_time'] ?? '';
    $guest_count = isset($_POST['guest_count']) ? (int)$_POST['guest_count'] : 1;
    $guest_names = $_POST['guest_name'] ?? [];
} else {
    $experience_id = isset($_GET['experience_id']) ? (int)$_GET['experience_id'] : ($_SESSION['booking_details']['experience_id'] ?? 0);
    $title = isset($_GET['title']) ? $_GET['title'] : ($_SESSION['booking_details']['title'] ?? '');
    $price = isset($_GET['price']) ? (float)$_GET['price'] : ($_SESSION['booking_details']['price'] ?? 0);
    $selected_date = isset($_GET['selected_date']) ? $_GET['selected_date'] : '';
    $selected_time = isset($_GET['selected_time']) ? $_GET['selected_time'] : '';
    $guest_count = isset($_GET['guest_count']) ? (int)$_GET['guest_count'] : 1;
    $guest_names = [];
}

// Clear booking details from session as they're no longer needed
if (isset($_SESSION['booking_details'])) {
    unset($_SESSION['booking_details']);
}

// If we have experience_id, fetch the full experience details from database
$experience = null;
if ($experience_id > 0) {
    require_once '../includes/db_config.php';
    try {
        $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id = ?');
        $stmt->execute([$experience_id]);
        $experience = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($experience) {
            $title = $experience['title'];
            $price = $experience['price'];
        }
    } catch (PDOException $e) {
        // Handle database error silently
    }
}

// Calculate total price
$total_price = $price * $guest_count;

// Handle image path
$image_path = '';
if ($experience && !empty($experience['image_url'])) {
    $image_path = $experience['image_url'];
    if (!preg_match('/^https?:\/\//', $image_path)) {
        $image_path = '../' . $image_path;
    }
} else {
    // Fallback image
    $image_path = '../assets/images/experiences/exp_6867c00ab3bef3.06653871.jpg';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/payment.css">
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>
<body>

<div class="checkout-hero">
  <img src="../assets/images/booking/v157_303.png" alt="Banner Image">
  <div class="checkout-title">Checkout</div>
</div>

<div class="checkout-container">
  <!-- Payment Box -->
  <div class="payment-box">
    <h3>Payment</h3>
    <form action="process_payment.php" method="POST">
      <input type="hidden" name="experience_id" value="<?php echo $experience_id; ?>">
      <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
      <input type="hidden" name="price" value="<?php echo $price; ?>">
      <input type="hidden" name="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>">
      <input type="hidden" name="selected_time" value="<?php echo htmlspecialchars($selected_time); ?>">
      <input type="hidden" name="guest_count" value="<?php echo $guest_count; ?>">
      <?php if (!empty($guest_names)) {
        foreach ($guest_names as $gname) {
          echo '<input type="hidden" name="guest_name[]" value="' . htmlspecialchars($gname) . '">';
        }
      } ?>
      
      <label><input type="radio" name="payment_method" value="card" checked> Card</label>
      <label><input type="radio" name="payment_method" value="bank"> Bank</label>
      <label><input type="radio" name="payment_method" value="transfer"> Transfer</label>

      <input type="text" name="card_number" placeholder="1234 5678 9101 1121" required>
      <div class="inline-fields">
        <input type="text" name="expiry" placeholder="MM/YY" required>
        <input type="text" name="cvv" placeholder="123" required>
      </div>

      <div class="checkout-buttons">
        <a href="guest_details.php?experience_id=<?php echo $experience_id; ?>&title=<?php echo urlencode($title); ?>&price=<?php echo $price; ?>" class="cancel-btn">Cancel</a>
        <button type="submit" class="confirm-btn">Confirm</button>
      </div>
    </form>
  </div>

  <!-- Order Summary Box -->
  <div class="summary-box">
    <h3>Order Summary</h3>
    <img src="<?php echo htmlspecialchars($image_path); ?>" alt="<?php echo htmlspecialchars($title); ?>">
    <div class="summary-details">
      <strong><?php echo htmlspecialchars($title); ?></strong>
      <span class="price">PHP <?php echo number_format($total_price, 2); ?></span>
      <?php if ($selected_date): ?>
        <p><strong>Date:</strong> <?php echo htmlspecialchars($selected_date); ?></p>
      <?php endif; ?>
      <?php if ($selected_time): ?>
        <p><strong>Time:</strong> <?php echo htmlspecialchars($selected_time); ?></p>
      <?php endif; ?>
      <?php if ($guest_count > 1): ?>
        <p><strong>Guests:</strong> <?php echo $guest_count; ?> people</p>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
<?php include '../includes/footer.php'; ?>
