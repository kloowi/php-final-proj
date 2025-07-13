<?php
session_start();
require_once '../includes/db_config.php';

function generateBookingCode() {
    $letters = '';
    for ($i = 0; $i < 3; $i++) {
        $letters .= chr(rand(65, 90)); // A-Z
    }
    $numbers = str_pad(strval(rand(0, 999)), 3, '0', STR_PAD_LEFT);
    return $letters . $numbers;
}

// Get form data
$experience_id = $_POST['experience_id'] ?? '';
$title = $_POST['title'] ?? '';
$price = $_POST['price'] ?? '';
$selected_date = $_POST['selected_date'] ?? '';
$selected_time = $_POST['selected_time'] ?? '';
$guest_count = $_POST['guest_count'] ?? '';
$payment_method = $_POST['payment_method'] ?? '';
$user_id = $_SESSION['user_id'] ?? null;

// Determine booking status
$today = date('Y-m-d');
$status = ($selected_date >= $today) ? 'confirmed' : 'completed';

// Generate unique booking code
$booking_code = generateBookingCode();

// Insert booking into Bookings table
$booking_id = null;
if ($pdo) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Bookings (booking_code, user_id, experience_id, booking_date, selected_time, number_of_guests, status) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->execute([
            $booking_code,
            $user_id,
            $experience_id,
            $selected_date,
            $selected_time,
            $guest_count,
            $status
        ]);
        $booking_id = $pdo->lastInsertId();

        // Insert guest names into Booking_Guests
        if (!empty($_POST['guest_name']) && is_array($_POST['guest_name'])) {
            $guest_stmt = $pdo->prepare('INSERT INTO Booking_Guests (booking_id, guest_name) VALUES (?, ?)');
            foreach ($_POST['guest_name'] as $guest_name) {
                if (trim($guest_name) !== '') {
                    $guest_stmt->execute([$booking_id, $guest_name]);
                }
            }
        }
    } catch (PDOException $e) {
        error_log('Booking insert failed: ' . $e->getMessage());
    }
}

// Insert payment into Payment table
$total = $price * $guest_count;
if ($pdo && $booking_id) {
    try {
        $stmt = $pdo->prepare('INSERT INTO Payment (booking_id, amount, payment_date, payment_method, status) VALUES (?, ?, NOW(), ?, ?)');
        $stmt->execute([
            $booking_id,
            $total,
            $payment_method,
            'completed'
        ]);
    } catch (PDOException $e) {
        error_log('Payment insert failed: ' . $e->getMessage());
    }
}

// Redirect to confirmation page with all details
header('Location: confirmation.php?order_id=' . urlencode($booking_id)
    . '&booking_code=' . urlencode($booking_code)
    . '&experience_id=' . urlencode($experience_id)
    . '&title=' . urlencode($title)
    . '&price=' . urlencode($price)
    . '&selected_date=' . urlencode($selected_date)
    . '&selected_time=' . urlencode($selected_time)
    . '&guest_count=' . urlencode($guest_count)
    . '&payment_method=' . urlencode($payment_method)
    . '&total=' . urlencode($total)
);
exit; 