<?php
session_start();

// Get experience details from URL parameters
$experience_id = isset($_GET['experience_id']) ? (int)$_GET['experience_id'] : 0;
$title = isset($_GET['title']) ? $_GET['title'] : '';
$price = isset($_GET['price']) ? (float)$_GET['price'] : 0;

// Validate experience exists in database
require_once '../includes/db_config.php';
if ($pdo && $experience_id > 0) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM Experiences WHERE experience_id = ?');
        $stmt->execute([$experience_id]);
        $experience = $stmt->fetch();
        
        if (!$experience) {
            // Experience not found, redirect to explore
            header('Location: explore.php');
            exit;
        }
        
        // Use database values for security
        $title = $experience['title'];
        $price = $experience['price'];
    } catch (PDOException $e) {
        error_log("Database error in guest_details.php: " . $e->getMessage());
    }
}

// Store booking details in session for later use
$_SESSION['booking_details'] = [
    'experience_id' => $experience_id,
    'title' => $title,
    'price' => $price
];

// Validate that we have the required parameters
if (!$experience_id || !$title || !$price) {
    header('Location: explore.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Details</title>
  <link rel="stylesheet" href="../assets/css/guest_details.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="hero">
  <img src="../assets/images/booking/v157_303.png" alt="Banner Image">
  <div class="banner-title">Guest Details</div>
</div>

<form action="pay_now.php" method="POST" id="guestDetailsForm">
  <div class="container">
    <div class="booking-flex-row">
      <div class="datetime-box">
        <div class="calendar-box">
          <h3>Choose a Date</h3>
          <div id="calendar"></div>
          <input type="hidden" id="selected-date" name="selected_date">
          <h3>Choose a Time</h3>
          <select name="selected_time" required>
            <option value="">Select Time</option>
            <?php
              for ($hour = 0; $hour < 24; $hour++) {
                  printf('<option value="%02d:00">%02d:00</option>', $hour, $hour);
              }
            ?>
          </select>
        </div>
      </div>
      <div class="guest-box">
        <h3>Guests</h3>
        <div class="guest-controls">
          <button type="button" onclick="updateGuests(-1)">−</button>
          <span id="guestCount">1</span>
          <button type="button" onclick="updateGuests(1)">+</button>
        </div>
        <input type="hidden" name="guest_count" id="guestCountInput" value="1">
        <div id="guest-names">
          <input type="text" name="guest_name[]" placeholder="Guest 1 Name" required>
        </div>
      </div>
    </div>
    <input type="hidden" name="experience_id" value="<?php echo $experience_id; ?>">
    <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">
    <div class="pay-btn-row">
      <button type="submit" class="pay-btn">Pay now!</button>
    </div>
  </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#calendar", {
    inline: true,
    minDate: "today",
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr) {
      document.getElementById("selected-date").value = dateStr;
    }
  });

  let guestCount = 1;
  function updateGuests(change) {
    const guestContainer = document.getElementById("guest-names");
    guestCount += change;
    if (guestCount < 1) guestCount = 1;

    document.getElementById("guestCount").textContent = guestCount;
    document.getElementById("guestCountInput").value = guestCount;

    guestContainer.innerHTML = "";
    for (let i = 0; i < guestCount; i++) {
      const input = document.createElement("input");
      input.type = "text";
      input.name = "guest_name[]";
      input.placeholder = `Guest ${i + 1} Name`;
      input.required = true;
      input.classList.add("guest-input");
      guestContainer.appendChild(input);
    }
  }
</script>

</body>
</html>
