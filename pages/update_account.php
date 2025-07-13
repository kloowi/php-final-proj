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
    
    // Handle name update
    if (isset($_POST['action']) && $_POST['action'] === 'update_name' && isset($_POST['full_name'])) {
        $full_name = trim($_POST['full_name']);
        
        if (empty($full_name)) {
            $response['message'] = 'Name cannot be empty';
        } else {
            $stmt = $pdo->prepare("UPDATE Users SET full_name = ? WHERE user_id = ?");
            $stmt->execute([$full_name, $user_id]);
            
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Name updated successfully';
            } else {
                $response['message'] = 'No changes made';
            }
        }
    }
    // Handle email update
    elseif (isset($_POST['action']) && $_POST['action'] === 'update_email' && isset($_POST['email'])) {
        $email = trim($_POST['email']);
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $response['message'] = 'Please enter a valid email address';
        } else {
            // Check if email already exists for another user
            $stmt = $pdo->prepare("SELECT user_id FROM Users WHERE email = ? AND user_id != ?");
            $stmt->execute([$email, $user_id]);
            
            if ($stmt->fetch()) {
                $response['message'] = 'Email already exists';
            } else {
                $stmt = $pdo->prepare("UPDATE Users SET email = ? WHERE user_id = ?");
                $stmt->execute([$email, $user_id]);
                
                if ($stmt->rowCount() > 0) {
                    $response['success'] = true;
                    $response['message'] = 'Email updated successfully';
                } else {
                    $response['message'] = 'No changes made';
                }
            }
        }
    }
    // Handle password update
    elseif (isset($_POST['action']) && $_POST['action'] === 'update_password' && isset($_POST['password'])) {
        $password = $_POST['password'];
        
        if (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters long';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE Users SET password_hash = ? WHERE user_id = ?");
            $stmt->execute([$password_hash, $user_id]);
            
            if ($stmt->rowCount() > 0) {
                $response['success'] = true;
                $response['message'] = 'Password updated successfully';
            } else {
                $response['message'] = 'No changes made';
            }
        }
    }
    // Handle gender update
    elseif (isset($_POST['action']) && $_POST['action'] === 'update_gender' && isset($_POST['gender'])) {
        $gender = trim($_POST['gender']);
        
        // Allow empty gender (nullable)
        $stmt = $pdo->prepare("UPDATE Users SET gender = ? WHERE user_id = ?");
        $stmt->execute([$gender ?: null, $user_id]);
        
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Gender updated successfully';
        } else {
            $response['message'] = 'No changes made';
        }
    }
    // Handle birthday update
    elseif (isset($_POST['action']) && $_POST['action'] === 'update_birthday' && isset($_POST['birthday'])) {
        $birthday = trim($_POST['birthday']);
        
        // Allow empty birthday (nullable)
        if (empty($birthday)) {
            $stmt = $pdo->prepare("UPDATE Users SET birthday = NULL WHERE user_id = ?");
            $stmt->execute([$user_id]);
        } else {
            // Validate date format
            $date = DateTime::createFromFormat('Y-m-d', $birthday);
            if (!$date || $date->format('Y-m-d') !== $birthday) {
                $response['message'] = 'Please enter a valid date (YYYY-MM-DD)';
            } else {
                $stmt = $pdo->prepare("UPDATE Users SET birthday = ? WHERE user_id = ?");
                $stmt->execute([$birthday, $user_id]);
            }
        }
        
        if ($stmt->rowCount() > 0) {
            $response['success'] = true;
            $response['message'] = 'Birthday updated successfully';
        } else {
            $response['message'] = 'No changes made';
        }
    }
    else {
        $response['message'] = 'Invalid action';
    }
    
} catch (PDOException $e) {
    $response['message'] = 'Database error occurred';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?> 