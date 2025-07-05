<?php include '../includes/header.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Details</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="assets/css/guest_details.css">
</head>
<body>

<div class="hero">
  <img src="assets/images/booking/v157_303.png" alt="Banner Image">
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

      <a href="pay_now.php" class="pay-btn">Pay now!</a>
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
