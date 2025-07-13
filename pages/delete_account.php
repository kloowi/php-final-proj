<?php
session_start();
require_once '../includes/db_config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$response = ['success' => false, 'message' => ''];

try {
    $user_id = $_SESSION['user_id'];
    
    // Start transaction to ensure data consistency
    $pdo->beginTransaction();
    
    // First, check if user has any active bookings
    $stmt = $pdo->prepare("SELECT COUNT(*) as booking_count FROM Bookings WHERE user_id = ? AND status != 'cancelled'");
    $stmt->execute([$user_id]);
    $bookingCount = $stmt->fetch()['booking_count'];
    
    if ($bookingCount > 0) {
        $response['message'] = 'Cannot delete account: You have active bookings. Please cancel all bookings first.';
        $pdo->rollBack();
    } else {
        // Delete related data in the correct order (due to foreign key constraints)
        
        // 1. Delete payment records
        $stmt = $pdo->prepare("DELETE p FROM Payment p 
                              INNER JOIN Bookings b ON p.booking_id = b.booking_id 
                              WHERE b.user_id = ?");
        $stmt->execute([$user_id]);
        
        // 2. Delete booking guests
        $stmt = $pdo->prepare("DELETE bg FROM Booking_Guests bg 
                              INNER JOIN Bookings b ON bg.booking_id = b.booking_id 
                              WHERE b.user_id = ?");
        $stmt->execute([$user_id]);
        
        // 3. Delete bookings
        $stmt = $pdo->prepare("DELETE FROM Bookings WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        // 4. Finally, delete the user
        $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        
        if ($stmt->rowCount() > 0) {
            // Commit the transaction
            $pdo->commit();
            
            // Clear session data
            $_SESSION = array();
            session_destroy();
            
            $response['success'] = true;
            $response['message'] = 'Account and all related data deleted successfully';
            $response['redirect'] = '../index.php';
        } else {
            $pdo->rollBack();
            $response['message'] = 'User not found or already deleted';
        }
    }
    
} catch (PDOException $e) {
    // Rollback transaction on error
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    
    error_log("Delete account error: " . $e->getMessage());
    $response['message'] = 'Database error occurred while deleting account. Please try again.';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 