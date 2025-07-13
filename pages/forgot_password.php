<?php
session_start();
require_once '../includes/db_config.php';

$step = 1;
$email = '';
$error = '';
$success = '';

if (isset($_POST['find_email'])) {
    $email = trim($_POST['email'] ?? '');
    if (empty($email)) {
        $error = 'Please enter your email.';
    } else {
        $stmt = $pdo->prepare('SELECT user_id FROM Users WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        if ($user) {
            $step = 2;
        } else {
            $error = 'No account found with that email.';
        }
    }
}

if (isset($_POST['reset_password'])) {
    $email = trim($_POST['email'] ?? '');
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    if (empty($new_password) || empty($confirm_password)) {
        $error = 'Please fill in all fields.';
        $step = 2;
    } elseif ($new_password !== $confirm_password) {
        $error = 'Passwords do not match.';
        $step = 2;
    } elseif (strlen($new_password) < 6) {
        $error = 'Password must be at least 6 characters.';
        $step = 2;
    } else {
        $stmt = $pdo->prepare('UPDATE Users SET password_hash = ? WHERE email = ?');
        $stmt->execute([password_hash($new_password, PASSWORD_DEFAULT), $email]);
        $success = 'Password reset successful! You can now <a href="login.php" class="text-blue-600 underline">log in</a>.';
        $step = 3;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StepIntoManila</title>
    <link rel="stylesheet" href="../assets/css/login.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="icon" type="image/png" href="../assets/images/logo/blue-logo.png">
</head>
<body>
<div class="background-container min-h-screen flex items-center justify-center">
    <div class="login-card p-8 rounded-2xl shadow-2xl w-11/12 max-w-md mx-auto sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-2/5">
        <h2 class="text-2xl font-semibold text-gray-800 mb-6">Forgot Password</h2>
        <?php if ($error): ?>
            <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        <?php if ($success): ?>
            <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <?php if ($step === 1): ?>
            <form method="POST" action="">
                <div class="mb-4 relative">
                    <input type="email" name="email" id="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="abc@email.com" required value="<?= htmlspecialchars($email) ?>">
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                </div>
                <button type="submit" name="find_email" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium flex items-center justify-center">
                    NEXT <svg class="ml-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        <?php elseif ($step === 2): ?>
            <form method="POST" action="">
                <input type="hidden" name="email" value="<?= htmlspecialchars($email) ?>">
                <div class="mb-4 relative">
                    <input type="password" name="new_password" id="new_password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your password" required>
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password" data-target="new_password" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                </div>
                <div class="mb-4 relative">
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm password" required>
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                    <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password" data-target="confirm_password" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1-2.16-3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                </div>
                <button type="submit" name="reset_password" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 font-medium flex items-center justify-center">
                    RESET PASSWORD <svg class="ml-1" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>
            </form>
        <?php endif; ?>
        <div class="mt-6 text-center">
            <a href="login.php" class="text-blue-600 hover:underline">Back to Login</a>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Password toggle logic
        document.querySelectorAll('.toggle-password').forEach(icon => {
            icon.addEventListener('click', function() {
                const targetId = this.dataset.target;
                const passwordInput = document.getElementById(targetId);
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    this.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path><circle cx="12" cy="12" r="3"></circle>';
                } else {
                    passwordInput.type = 'password';
                    this.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1-2.16-3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                }
            });
        });
    });
</script>
</body>
</html> 