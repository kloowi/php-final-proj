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
  <title>StepIntoManila</title>
  <link rel="stylesheet" href="../assets/css/guest_details.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>
<body>
<?php include '../includes/header.php'; ?>

<div class="hero">
  <img src="../assets/images/booking/v157_303.png" alt="Banner Image">
  <div class="banner-title">Booking Details</div>
</div>

<form action="pay_now.php" method="POST" id="guestDetailsForm">
  <div class="container">
    <div class="booking-flex-row" style="display: flex; flex-direction: row; justify-content: center; align-items: flex-start; width: 100%; margin-bottom: 18px; gap: 32px;">
      <div class="calendar-time-card" style="background: #fff; border-radius: 12px; box-shadow: 0 8px 20px rgba(0,0,0,0.08); padding: 32px 28px 32px 28px; display: flex; flex-direction: row; gap: 38px; align-items: flex-start;">
        <div class="calendar-box" style="box-shadow: none; padding: 0; margin: 0; background: transparent;">
          <h3>Choose a Date</h3>
          <div id="calendar"></div>
          <input type="hidden" id="selected-date" name="selected_date">
        </div>
        <div class="time-box" style="box-shadow: none; padding: 0; margin: 0; background: transparent;">
          <h3>Choose a Time</h3>
          <div class="time-options" id="timeOptions" style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 22px; margin-top: 8px; margin-bottom: 8px;">
            <?php for ($hour = 8; $hour <= 19; $hour++): ?>
              <button type="button" class="time-btn" data-time="<?= sprintf('%02d:00', $hour) ?>">
                <?= sprintf('%02d:00', $hour) ?>
              </button>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="selected_time" id="selectedTimeInput" required>
        </div>
      </div>
      <div class="guest-box" id="guestBox" style="max-width: 370px; min-width: 320px; height: 100%; display: flex; flex-direction: column; justify-content: flex-start;">
        <div class="guest-header-row" style="display: flex; align-items: center; gap: 16px; margin-bottom: 10px;">
          <h3 style="margin: 0;">Guests</h3>
          <div class="guest-controls">
            <button type="button" onclick="updateGuests(-1)">−</button>
            <span id="guestCount">1</span>
            <button type="button" onclick="updateGuests(1)">+</button>
          </div>
        </div>
        <input type="hidden" name="guest_count" id="guestCountInput" value="1">
        <div id="guest-names" style="flex: 1 1 auto; min-height: 0; max-height: 100%; overflow-y: auto; width: 100%;">
          <input type="text" name="guest_name[]" placeholder="Guest 1 Name" required>
        </div>
      </div>
    </div>
    <input type="hidden" name="experience_id" value="<?php echo $experience_id; ?>">
    <input type="hidden" name="title" value="<?php echo htmlspecialchars($title); ?>">
    <input type="hidden" name="price" value="<?php echo $price; ?>">
    <div class="pay-btn-row">
      <button type="submit" class="pay-btn">Proceed to Payment</button>
    </div>
  </div>
</form>

<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
  flatpickr("#calendar", {
    inline: true,
    minDate: "today",
    dateFormat: "Y-m-d",
    onChange: function(selectedDates, dateStr, instance) {
      const prevDate = instance._lastSelectedDateStr;
      if (prevDate === dateStr) {
        // Unselect if clicking the same date
        instance.clear();
        document.getElementById("selected-date").value = '';
      } else {
        document.getElementById("selected-date").value = dateStr;
      }
      instance._lastSelectedDateStr = dateStr;
    }
  });

  let guestCount = 1;
  function updateGuests(change) {
    const guestContainer = document.getElementById("guest-names");
    // Collect current values
    const currentValues = Array.from(guestContainer.querySelectorAll('input[name="guest_name[]"]')).map(input => input.value);
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
      if (currentValues[i]) input.value = currentValues[i];
      guestContainer.appendChild(input);
    }
  }

  // Time button selection logic
  document.addEventListener('DOMContentLoaded', function() {
    const timeOptions = document.getElementById('timeOptions');
    const timeInput = document.getElementById('selectedTimeInput');
    if (timeOptions && timeInput) {
      timeOptions.addEventListener('click', function(e) {
        if (e.target.classList.contains('time-btn')) {
          if (e.target.classList.contains('active')) {
            // If already active, unselect it
            e.target.classList.remove('active');
            timeInput.value = '';
          } else {
            // Remove active from all
            timeOptions.querySelectorAll('.time-btn').forEach(btn => btn.classList.remove('active'));
            // Set active
            e.target.classList.add('active');
            // Set value
            timeInput.value = e.target.getAttribute('data-time');
          }
        }
      });
    }
    // Prevent form submit if no time selected
    document.getElementById('guestDetailsForm').addEventListener('submit', function(e) {
      if (!timeInput.value) {
        alert('Please select a time.');
        e.preventDefault();
      }
    });
  });

  // Match guest box height to calendar-time-card
  function matchGuestBoxHeight() {
    var card = document.querySelector('.calendar-time-card');
    var guestBox = document.getElementById('guestBox');
    if (card && guestBox) {
      guestBox.style.height = card.offsetHeight + 'px';
    }
  }
  window.addEventListener('DOMContentLoaded', matchGuestBoxHeight);
  window.addEventListener('resize', matchGuestBoxHeight);
</script>

</body>
</html>
