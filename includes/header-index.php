<?php
session_start();

// Detect if current page is index.php (in root)
$scriptPath = $_SERVER['SCRIPT_NAME'];
$isIndex = (basename($scriptPath) === 'index.php');

// Set dynamic values
$headerClass = $isIndex ? 'transparent' : 'white-bg';
$basePath = $isIndex ? 'assets' : '../assets';
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Discover Manila</title>

  <!-- ✅ Proper CSS paths -->
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/header.css">

  <!-- ✅ Script for index only -->
  <?php if ($isIndex): ?>
    <script src="<?php echo $basePath; ?>/js/script.js" defer></script>
  <?php endif; ?>
</head>
<body>
  <header class="site-header <?php echo $headerClass; ?>" id="main-header">
    <nav class="main-nav">
      <div class="logo">
        <img
          id="site-logo"
          src="<?php echo $basePath; ?>/images/logo/<?php echo $isIndex ? 'white-logo.png' : 'blue-logo.png'; ?>"
          alt="Logo"
          style="height: 40px;">
      </div>
      <ul class="nav-links">
        <li><a href="<?php echo $isIndex ? 'index.php' : '../index.php'; ?>"
               class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a></li>

        <li><a href="<?php echo $isIndex ? 'pages/explore.php' : 'explore.php'; ?>"
               class="<?php echo ($currentPage == 'explore.php' || $currentPage == 'view_experience.php') ? 'active' : ''; ?>">Explore</a></li>

        <li><a href="<?php echo $isIndex ? 'pages/manage.php' : 'manage.php'; ?>"
               class="<?php echo ($currentPage == 'manage.php' || $currentPage == 'view_manage,php') ? 'active' : ''; ?>">Manage</a></li>

        <li><a href="<?php echo $isIndex ? 'pages/about.php' : 'about.php'; ?>"
               class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
      </ul>
      <div class="login-link">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="/logout.php">Log Out</a>
        <?php else: ?>
          <a href="/pages/login.php">Log In</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>
