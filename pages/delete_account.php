<?php
session_start();
require_once '../includes/db_connect.php';

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
    
    // Delete the user from the database
    $stmt = $pdo->prepare("DELETE FROM Users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    
    if ($stmt->rowCount() > 0) {
        // Clear session data
        $_SESSION = array();
        session_destroy();
        
        $response['success'] = true;
        $response['message'] = 'Account deleted successfully';
        $response['redirect'] = '../index.php';
    } else {
        $response['message'] = 'User not found or already deleted';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Database error occurred while deleting account';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 