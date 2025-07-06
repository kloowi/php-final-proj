<?php 
// Get experience data from POST parameters
$experience_id = isset($_POST['experience_id']) ? (int)$_POST['experience_id'] : 0;
$experience_title = isset($_POST['experience_title']) ? $_POST['experience_title'] : 'Experience';
$experience_price = isset($_POST['experience_price']) ? (float)$_POST['experience_price'] : 0;

// Get guest details from POST
$selected_date = isset($_POST['selected_date']) ? $_POST['selected_date'] : '';
$selected_time = isset($_POST['selected_time']) ? $_POST['selected_time'] : '';
$guest_count = isset($_POST['guest_count']) ? (int)$_POST['guest_count'] : 1;
$guest_names = isset($_POST['guest_name']) ? $_POST['guest_name'] : [];

// Calculate total price
$total_price = $experience_price * $guest_count;

// Validate that we have the required data
if (!$experience_id || !$experience_title || !$experience_price || !$selected_date || !$selected_time) {
    header('Location: pages/explore-testlocal.php');
    exit;
}

// Format date for display
$formatted_date = date('m - d - Y', strtotime($selected_date));
$formatted_time = date('g:i A', strtotime($selected_time));
$end_time = date('g:i A', strtotime($selected_time . ' +1 hour'));
?>

<?php include 'includes/header.php'; ?>
<link rel="stylesheet" href="assets/css/payment.css">
<body>

<div class="checkout-hero">
  <img src="assets/images/booking/v157_303.png" alt="Banner Image">
  <div class="checkout-title">Checkout</div>
</div>

<div class="checkout-container">
  <!-- Payment Box -->
  <div class="payment-box">
    <h3>Payment</h3>
    <form action="process_payment.php" method="POST">
      <!-- Hidden fields to pass all booking data -->
      <input type="hidden" name="experience_id" value="<?php echo $experience_id; ?>">
      <input type="hidden" name="experience_title" value="<?php echo htmlspecialchars($experience_title); ?>">
      <input type="hidden" name="experience_price" value="<?php echo $experience_price; ?>">
      <input type="hidden" name="selected_date" value="<?php echo htmlspecialchars($selected_date); ?>">
      <input type="hidden" name="selected_time" value="<?php echo htmlspecialchars($selected_time); ?>">
      <input type="hidden" name="guest_count" value="<?php echo $guest_count; ?>">
      <?php foreach ($guest_names as $index => $name): ?>
        <input type="hidden" name="guest_name[]" value="<?php echo htmlspecialchars($name); ?>">
      <?php endforeach; ?>
      <label><input type="radio" name="payment_method" value="card" checked> Card</label>
      <label><input type="radio" name="payment_method" value="bank"> Bank</label>
      <label><input type="radio" name="payment_method" value="transfer"> Transfer</label>

      <input type="text" name="card_number" placeholder="1234 5678 9101 1121" required>
      <div class="inline-fields">
        <input type="text" name="expiry" placeholder="MM/YY" required>
        <input type="text" name="cvv" placeholder="123" required>
      </div>

      <div class="checkout-buttons">
        <a href="javascript:history.back()" class="cancel-btn">Back</a>
        <button type="submit" class="confirm-btn">Confirm Payment</button>
      </div>
    </form>
  </div>

  <!-- Order Summary Box -->
  <div class="summary-box">
    <h3>Order Summary</h3>
    <img src="assets/images/experiences/exp_6867c00ab3bef3.06653871.jpg" alt="<?php echo htmlspecialchars($experience_title); ?>">
    <div class="summary-details">
      <strong><?php echo htmlspecialchars($experience_title); ?></strong>
      <span class="price">PHP <?php echo number_format($total_price, 2); ?></span>
      <p><strong>Select Date:</strong> <?php echo $formatted_date; ?></p>
      <p><strong>Start With:</strong> <?php echo $formatted_time; ?></p>
      <p><strong>End With:</strong> <?php echo $end_time; ?></p>
      <p><strong>Guests:</strong> <?php echo $guest_count; ?> <?php echo $guest_count == 1 ? 'person' : 'people'; ?></p>
      <?php if (!empty($guest_names)): ?>
        <div class="guest-list">
          <strong>Guest Names:</strong>
          <?php foreach ($guest_names as $name): ?>
            <p class="guest-name">• <?php echo htmlspecialchars($name); ?></p>
          <?php endforeach; ?>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>

</body>
<?php include '../includes/footer.php'; ?>
