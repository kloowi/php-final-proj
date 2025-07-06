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
      <label><input type="radio" name="payment_method" value="card" checked> Card</label>
      <label><input type="radio" name="payment_method" value="bank"> Bank</label>
      <label><input type="radio" name="payment_method" value="transfer"> Transfer</label>

      <input type="text" name="card_number" placeholder="1234 5678 9101 1121" required>
      <div class="inline-fields">
        <input type="text" name="expiry" placeholder="MM/YY" required>
        <input type="text" name="cvv" placeholder="123" required>
      </div>

      <div class="checkout-buttons">
        <a href="../guest_details.php" class="cancel-btn">Cancel</a>
        <button type="submit" class="confirm-btn">Confirm</button>
      </div>
    </form>
  </div>

  <!-- Order Summary Box -->
  <div class="summary-box">
    <h3>Order Summary</h3>
    <img src="assets/images/experiences/exp_6867c00ab3bef3.06653871.jpg" alt="Experience Image">
    <div class="summary-details">
      <strong>Fort Santiago Ticket</strong>
      <span class="price">PHP 350.00</span>
      <p><strong>Select Date:</strong> 07 - 18 - 2025</p>
      <p><strong>Start With:</strong> 9:00 AM</p>
      <p><strong>End With:</strong> 10:00 AM</p>
    </div>
  </div>
</div>

</body>
<?php include '../includes/footer.php'; ?>
