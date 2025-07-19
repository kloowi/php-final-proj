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
    <style>
        body { background: #f4f6f8; font-family: Arial, sans-serif; }
        .login-container { display: flex; align-items: center; justify-content: center; height: 100vh; }
        .login-box { background: #fff; padding: 48px 40px; border-radius: 16px; box-shadow: 0 2px 12px rgba(74,163,255,0.10); width: 400px; }
        .login-box h1 { margin-bottom: 28px; color: #0080ff; font-size: 2.2em; font-weight: 800; letter-spacing: -1px; text-align: center; }
        .form-group { margin-bottom: 22px; }
        .form-group label { display: block; margin-bottom: 8px; font-weight: 700; color: #222; font-size: 1.1em; }
        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #bcdcff;
            border-radius: 8px;
            background: #f8fbff;
            font-size: 1.1em;
            transition: border 0.2s;
        }
        .form-control:focus {
            border: 1.5px solid #4aa3ff;
            outline: none;
            background: #f4faff;
        }
        .btn {
            width: 100%;
            padding: 12px 0;
            background: #4aa3ff;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 1.1em;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s;
            margin-top: 10px;
        }
        .btn:hover, .btn:focus { background: #0080ff; }
        .alert { margin-bottom: 18px; border-radius: 6px; padding: 12px 18px; font-size: 1em; }
        .alert-danger { background: #f8d7da; color: #721c24; }
    </style>
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