<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page with return URL
    $redirect_url = urlencode($_SERVER['REQUEST_URI']);
    header("Location: login.php?redirect=" . $redirect_url);
    exit;
}

// Get experience details from URL parameters
$experience_id = isset($_GET['experience_id']) ? (int)$_GET['experience_id'] : 0;
$title = isset($_GET['title']) ? $_GET['title'] : '';
$price = isset($_GET['price']) ? (float)$_GET['price'] : 0;

// Validate that we have the required parameters
if (!$experience_id || !$title || !$price) {
    header('Location: explore-testlocal.php');
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

<form action="process_booking.php" method="POST">
  <div class="container">
    <div class="calendar-box">
      <h3>Choose a Date</h3>
      <div id="calendar"></div>
      <input type="hidden" id="selected-date" name="selected_date">
    </div>

    <div class="time-box">
      <h3>Starting Time</h3>
      <select name="selected_time" required>
        <option value="">Select Time</option>
        <?php
          for ($hour = 0; $hour < 24; $hour++) {
              printf('<option value="%02d:00">%02d:00</option>', $hour, $hour);
          }
        ?>
      </select>

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

      <a href="pay_now.php?experience_id=<?php echo $experience_id; ?>&title=<?php echo urlencode($title); ?>&price=<?php echo $price; ?>" class="pay-btn" onclick="return submitFormData()">Pay now!</a>
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

  function submitFormData() {
    const selectedDate = document.getElementById("selected-date").value;
    const selectedTime = document.querySelector('select[name="selected_time"]').value;
    const guestCount = document.getElementById("guestCountInput").value;
    
    if (!selectedDate || !selectedTime) {
      alert("Please select a date and time before proceeding.");
      return false;
    }
    
    // Build the URL with form data
    const baseUrl = "pay_now.php?experience_id=<?php echo $experience_id; ?>&title=<?php echo urlencode($title); ?>&price=<?php echo $price; ?>";
    const formData = `&selected_date=${encodeURIComponent(selectedDate)}&selected_time=${encodeURIComponent(selectedTime)}&guest_count=${encodeURIComponent(guestCount)}`;
    
    // Redirect to pay_now.php with all the data
    window.location.href = baseUrl + formData;
    return false;
  }
</script>

</body>
</html>
