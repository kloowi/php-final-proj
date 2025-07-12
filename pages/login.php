<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is already logged in
if (isset($_SESSION['user_id'])) {
    // If there's a redirect URL, go there, otherwise go to home
    $redirect = $_GET['redirect'] ?? '../index.php';
    header('Location: ' . $redirect);
    exit;
}

$error_message = '';
$success_message = '';
$redirect_url = $_GET['redirect'] ?? '../index.php';

// Handle Login
if (isset($_POST['login'])) {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $redirect_url = $_POST['redirect_url'] ?? '../index.php';
    
    if (empty($username) || empty($email) || empty($password)) {
        $error_message = 'Please fill in all fields.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT user_id, username, email, password_hash, full_name FROM Users WHERE username = ? AND email = ?");
            $stmt->execute([$username, $email]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['user_id'];
                $_SESSION['user_data'] = $user;
                $success_message = 'Login successful!';
                // Redirect to view account page after login
                header('Location: view_account.php');
                exit;
            } else {
                $error_message = 'Invalid username, email, or password.';
            }
        } catch (PDOException $e) {
            $error_message = 'Database error. Please try again.';
        }
    }
}

// Handle Sign Up
if (isset($_POST['signup'])) {
    $full_name = trim($_POST['full_name'] ?? '');
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $redirect_url = $_POST['redirect_url'] ?? '../index.php';
    
    if (empty($full_name) || empty($username) || empty($email) || empty($password) || empty($confirm_password)) {
        $error_message = 'Please fill in all fields.';
    } elseif ($password !== $confirm_password) {
        $error_message = 'Passwords do not match.';
    } elseif (strlen($password) < 6) {
        $error_message = 'Password must be at least 6 characters long.';
    } else {
        try {
            // Check if username or email already exists
            $stmt = $pdo->prepare("SELECT username FROM Users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            $existing_user = $stmt->fetch();
            
            if ($existing_user) {
                $error_message = 'Username or email already exists.';
            } else {
                // Hash password and insert new user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO Users (username, email, password_hash, full_name) VALUES (?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password_hash, $full_name]);
                
                $success_message = 'Account created successfully! You can now login.';
                // After successful signup, redirect to view account page
                header('Location: view_account.php');
                exit;
            }
        } catch (PDOException $e) {
            $error_message = 'Database error. Please try again.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login/Sign Up Form</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Link to external CSS file -->
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="background-container">
        <div class="login-card p-8 rounded-2xl shadow-2xl w-11/12 max-w-md mx-auto sm:w-3/4 md:w-2/3 lg:w-1/2 xl:w-2/5">

            <!-- Error/Success Messages -->
            <?php if ($error_message): ?>
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?php echo htmlspecialchars($error_message); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success_message): ?>
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?php echo htmlspecialchars($success_message); ?>
                </div>
            <?php endif; ?>

            <!-- Login Form -->
            <div id="loginForm">
                <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-left">Login</h2>

                <form method="POST" action="">
                    <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url); ?>">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="loginUsername" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="relative">
                            <input type="email" id="loginEmail" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="abc@email.com" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="relative">
                            <input type="password" id="loginPassword" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your password" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <!-- Eye icon for toggling password visibility -->
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password" data-target="loginPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </div>
                    </div>

                    <div class="flex justify-between items-center mb-6 text-sm">
                        <div class="flex items-center">
                            <div class="toggle-switch mr-2">
                                <input type="checkbox" id="rememberMe">
                                <label for="rememberMe"></label>
                            </div>
                            <label for="rememberMe" class="text-gray-600">Remember Me</label>
                        </div>
                        <a href="#" class="text-blue-600 hover:underline">Forgot Password?</a>
                    </div>

                    <button type="submit" name="login" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 mb-3 font-medium flex items-center justify-center">
                        LOGIN
                        <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>
                
                <button id="showSignUp" class="w-full bg-blue-100 text-blue-700 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-200 mb-6 font-medium flex items-center justify-center">
                    SIGN UP
                    <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>

                <div class="flex items-center mb-6">
                    <hr class="flex-grow border-gray-300">
                    <span class="mx-4 text-gray-500 text-sm">OR</span>
                    <hr class="flex-grow border-gray-300">
                </div>

                <button class="w-full bg-white border border-gray-300 text-gray-700 py-2 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-200 mb-3">
                    <img src="google logo.png" alt="Google Logo" class="w-5 h-5 mr-2">
                    Login with Google
                </button>
                <button class="w-full bg-white border border-gray-300 text-gray-700 py-2 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-200 mb-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/2021_Facebook_icon.svg/1024px-2021_Facebook_icon.svg.png" alt="Facebook Logo" class="w-5 h-5 mr-2">
                    Login with Facebook
                </button>

                <div class="text-center text-sm text-gray-600">
                    Already have an account? <a href="#" id="showLoginFromLogin" class="text-blue-600 hover:underline font-medium">Signin</a>
                </div>
            </div>

            <!-- Sign Up Form (Initially Hidden) -->
            <div id="signUpForm" class="hidden">
                <h2 class="text-3xl font-semibold text-gray-800 mb-6 text-left">Sign Up</h2>

                <form method="POST" action="">
                    <input type="hidden" name="redirect_url" value="<?php echo htmlspecialchars($redirect_url); ?>">
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="fullName" name="full_name" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Full name" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="relative">
                            <input type="text" id="signUpUsername" name="username" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Username" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="relative">
                            <input type="email" id="signUpEmail" name="email" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="abc@email.com" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
                        </div>
                    </div>
                    <div class="mb-4">
                        <div class="relative">
                            <input type="password" id="signUpPassword" name="password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Your password" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <!-- Eye icon for toggling password visibility -->
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password" data-target="signUpPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </div>
                    </div>
                    <div class="mb-6">
                        <div class="relative">
                            <input type="password" id="confirmPassword" name="confirm_password" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Confirm password" required>
                            <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg>
                            <!-- Eye icon for toggling password visibility -->
                            <svg class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 cursor-pointer toggle-password" data-target="confirmPassword" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line></svg>
                        </div>
                    </div>

                    <button type="submit" name="signup" class="w-full bg-blue-500 text-white py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200 mb-3 font-medium flex items-center justify-center">
                        SIGN UP
                        <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                    </button>
                </form>

                <button id="showLoginFromSignup" class="w-full bg-blue-100 text-blue-700 py-2 rounded-lg hover:bg-blue-200 transition-colors duration-200 mb-6 font-medium flex items-center justify-center">
                    BACK TO LOGIN
                    <svg class="ml-2" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
                </button>

                <div class="flex items-center mb-6">
                    <hr class="flex-grow border-gray-300">
                    <span class="mx-4 text-gray-500 text-sm">OR</span>
                    <hr class="flex-grow border-gray-300">
                </div>

                <button class="w-full bg-white border border-gray-300 text-gray-700 py-2 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-200 mb-3">
                    <img src="google logo.png" alt="Google Logo" class="w-5 h-5 mr-2">
                    Login with Google
                </button>
                <button class="w-full bg-white border border-gray-300 text-gray-700 py-2 rounded-lg flex items-center justify-center hover:bg-gray-50 transition-colors duration-200 mb-6">
                    <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/b/b8/2021_Facebook_icon.svg/1024px-2021_Facebook_icon.svg.png" alt="Facebook Logo" class="w-5 h-5 mr-2">
                    Login with Facebook
                </button>

                <div class="text-center text-sm text-gray-600">
                    Already have an account? <a href="#" id="showLoginFromSignup" class="text-blue-600 hover:underline font-medium">Signin</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('loginForm');
            const signUpForm = document.getElementById('signUpForm');
            const showSignUpButton = document.getElementById('showSignUp');
            const showLoginFromLoginLink = document.getElementById('showLoginFromLogin');
            const showLoginFromSignupLink = document.getElementById('showLoginFromSignup');
            const signUpLoginButton = document.getElementById('signUpLoginButton');

            // Function to show login form and hide sign up form
            function showLoginForm() {
                loginForm.classList.remove('hidden');
                signUpForm.classList.add('hidden');
            }

            // Function to show sign up form and hide login form
            function showSignUpForm() {
                loginForm.classList.add('hidden');
                signUpForm.classList.remove('hidden');
            }

            // Event listeners for switching forms
            if (showSignUpButton) {
                showSignUpButton.addEventListener('click', showSignUpForm);
            }
            if (showLoginFromLoginLink) {
                showLoginFromLoginLink.addEventListener('click', showLoginForm);
            }
            if (showLoginFromSignupLink) {
                showLoginFromSignupLink.addEventListener('click', showLoginForm);
            }
            if (signUpLoginButton) {
                signUpLoginButton.addEventListener('click', showLoginForm);
            }

            // Password visibility toggle functionality
            document.querySelectorAll('.toggle-password').forEach(icon => {
                icon.addEventListener('click', function() {
                    const targetId = this.dataset.target;
                    const passwordInput = document.getElementById(targetId);
                    if (passwordInput.type === 'password') {
                        passwordInput.type = 'text';
                        // Change icon to an "open eye" (no slash)
                        this.innerHTML = '<path d="M2 12s3-7 10-7 10 7 10 7-3 7-10 7-10-7-10-7z"></path><circle cx="12" cy="12" r="3"></circle>';
                    } else {
                        passwordInput.type = 'password';
                        // Change icon to a "slashed eye"
                        this.innerHTML = '<path d="M17.94 17.94A10.07 10.07 0 0 1 12 20c-7 0-11-8-11-8a18.45 18.45 0 0 1 5.06-5.94M9.9 4.24A9.12 9.12 0 0 1 12 4c7 0 11 8 11 8a18.5 18.5 0 0 1-2.16 3.19m-6.72-1.07a3 3 0 1 1-4.24-4.24"></path><line x1="1" y1="1" x2="23" y2="23"></line>';
                    }
                });
            });
        });
    </script>
</body>
</html>
