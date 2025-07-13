
<?php include '../includes/header.php'; 
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

// Accept POST from guest_details.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $experience_id   = isset($_POST['experience_id'])   ? (int)   $_POST['experience_id']   : 0;
    $title           = $_POST['title']                  ?? '';
    $price           = isset($_POST['price'])           ? (float) $_POST['price']          : 0;
    $selected_date   = $_POST['selected_date']          ?? '';
    $selected_time   = $_POST['selected_time']          ?? '';
    $guest_count     = isset($_POST['guest_count'])     ? (int)   $_POST['guest_count']     : 1;
    $guest_names     = $_POST['guest_name']             ?? [];
} else {
    $experience_id   = isset($_GET['experience_id'])    ? (int)   $_GET['experience_id']    : ($_SESSION['booking_details']['experience_id'] ?? 0);
    $title           = isset($_GET['title'])            ? $_GET['title']                    : ($_SESSION['booking_details']['title']         ?? '');
    $price           = isset($_GET['price'])            ? (float) $_GET['price']            : ($_SESSION['booking_details']['price']         ?? 0);
    $selected_date   = isset($_GET['selected_date'])    ? $_GET['selected_date']            : '';
    $selected_time   = isset($_GET['selected_time'])    ? $_GET['selected_time']            : '';
    $guest_count     = isset($_GET['guest_count'])      ? (int)   $_GET['guest_count']      : 1;
    $guest_names     = [];
}

if (isset($_SESSION['booking_details'])) {
    unset($_SESSION['booking_details']);
}

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
    } catch (PDOException $e) {}
}

$total_price = $price * $guest_count;

if ($experience && !empty($experience['image_url'])) {
    $image_path = $experience['image_url'];
    if (!preg_match('/^https?:\/\//', $image_path)) {
        $image_path = '../' . $image_path;
    }
} else {
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
      <form action="confirmation.php" method="POST" id="paynow-form">
        <!-- Hidden booking fields -->
        <input type="hidden" name="experience_id"   value="<?= $experience_id ?>">
        <input type="hidden" name="title"           value="<?= htmlspecialchars($title, ENT_QUOTES) ?>">
        <input type="hidden" name="price"           value="<?= $price ?>">
        <input type="hidden" name="selected_date"   value="<?= htmlspecialchars($selected_date, ENT_QUOTES) ?>">
        <input type="hidden" name="selected_time"   value="<?= htmlspecialchars($selected_time, ENT_QUOTES) ?>">
        <input type="hidden" name="guest_count"     value="<?= $guest_count ?>">
        <?php foreach ($guest_names as $gname): ?>
          <input type="hidden" name="guest_name[]" value="<?= htmlspecialchars($gname, ENT_QUOTES) ?>">
        <?php endforeach; ?>

        <!-- Payment method radios -->
        <div class="payment-methods">
          <label><input type="radio" name="payment_method" value="card"          checked> Card</label>
          <label><input type="radio" name="payment_method" value="qrph"> QR PH</label>
          <label><input type="radio" name="payment_method" value="banktransfer"> Bank Transfer</label>
        </div>

        <!-- Card fields -->
        <div class="payment-details" id="card-fields">
          <label>
            Name on Card
            <input type="text" name="card_name" placeholder="John Doe" required>
          </label>
          <label>
            Card Number
            <input type="text" name="card_number" placeholder="1234 5678 9101 1121" required>
          </label>
          <div class="inline-fields">
            <label>
              Expiry Date
              <input type="text" name="expiry" placeholder="MM/YY" required>
            </label>
            <label>
              CVV
              <input type="text" name="cvv" placeholder="123" required>
            </label>
          </div>
        </div>

        <div class="payment-details" id="qrph-fields" style="display:none; text-align:center;">
          <p>Scan this QR with any partner’s QR PH app:</p>
          <img
            src="../assets/images/booking/IMG_8045.jpg"
            alt="QR PH code"
            style="max-width: 150px; width: auto; height: auto; display: block; margin: 0 auto;"
          >
        </div>

        <!-- Bank Transfer fields -->
        <div class="payment-details" id="banktransfer-fields" style="display:none;">
          <label>
            Recipient Name
            <input type="text" name="bank_recipient" placeholder="John Doe" required>
          </label>
          <label>
            Account Number
            <input type="text" name="bank_account" placeholder="1234 5678 9101 1121" required>
          </label>
          <label>
            Bank Name
            <input type="text" name="bank_name" placeholder="ex. Bank of the Philippines" required>
          </label>
          <label>
            Branch
            <input type="text" name="bank_branch" placeholder="Main Branch" required>
          </label>
        </div>

        <!-- Buttons -->
        <div class="checkout-buttons">
          <a href="guest_details.php?experience_id=<?= $experience_id ?>&title=<?= urlencode($title) ?>&price=<?= $price ?>" class="cancel-btn">Cancel</a>
          <button 
              type="button" 
              class="confirm-btn"
              onclick="window.location.href='confirmation.php';"
            >
              Confirm
          </button>
        </div>
      </form>
    </div>

    <!-- Order Summary Box -->
    <div class="summary-box">
      <h3>Order Summary</h3>
      <img src="<?= htmlspecialchars($image_path, ENT_QUOTES) ?>" alt="<?= htmlspecialchars($title, ENT_QUOTES) ?>">
      <div class="summary-details">
        <strong><?= htmlspecialchars($title, ENT_QUOTES) ?></strong>
        <span class="price">PHP <?= number_format($total_price, 2) ?></span>
        <?php if ($selected_date): ?>
          <p><strong>Date:</strong> <?= htmlspecialchars($selected_date, ENT_QUOTES) ?></p>
        <?php endif; ?>
        <?php if ($selected_time): ?>
          <p><strong>Time:</strong> <?= htmlspecialchars($selected_time, ENT_QUOTES) ?></p>
        <?php endif; ?>
        <?php if ($guest_count > 1): ?>
          <p><strong>Guests:</strong> <?= $guest_count ?> people</p>
        <?php endif; ?>
      </div>
    </div>
  </div>

  <!-- Toggle script -->
  <script>
    const radios   = document.querySelectorAll('input[name="payment_method"]');
    const sections = {
      card:          document.getElementById('card-fields'),
      qrph:          document.getElementById('qrph-fields'),
      banktransfer:  document.getElementById('banktransfer-fields'),
    };

    function updatePaymentFields() {
      const sel = document.querySelector('input[name="payment_method"]:checked').value;
      Object.values(sections).forEach(div => div.style.display = 'none');
      sections[sel].style.display = 'block';
    }

    radios.forEach(r => r.addEventListener('change', updatePaymentFields));
    window.addEventListener('DOMContentLoaded', updatePaymentFields);
  </script>
</body>

<?php include '../includes/footer.php'; ?>
```
