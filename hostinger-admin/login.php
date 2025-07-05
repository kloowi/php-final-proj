<?php
require_once './authentication/auth.php';
if (isAdminLoggedIn()) {
    header('Location: dashboard.php');
    exit;
}

$error_message = '';
if ($_POST) {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    if (empty($username) || empty($password)) {
        $error_message = 'Please enter both username and password.';
    } else {
        $admin = authenticateAdmin($username, $password);
        if ($admin) {
            $_SESSION['admin_token'] = bin2hex(random_bytes(32));
            $_SESSION['admin_data'] = $admin;
            header('Location: dashboard.php');
            exit;
        } else {
            $error_message = $error_message ?: 'Invalid username or password.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link rel="stylesheet" href="admin.css">
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <h1>Admin Login</h1>
            <?php displayFlashMessage(); ?>
            <?php if ($error_message): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>
            <form method="POST">
                <div class="form-group">
                    <label for="username">Username</label>
                    <input type="text" id="username" name="username" required autofocus>
                </div>
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn btn-primary">Login</button>
            </form>
        </div>
    </div>
</body>
</html> 