<?php
session_start();
$scriptPath = $_SERVER['PHP_SELF'];
$isIndex = (basename($scriptPath) === 'index.php');
$headerClass = $isIndex ? 'transparent' : 'white-bg';
$basePath = '/assets'; // root-relative path (Hostinger safe)
$currentPage = basename($scriptPath);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Discover Manila</title>

  <!-- ✅ Load CSS -->
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/header.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/index.css">

  <!-- ✅ Script global variable -->
  <script>
    const BASE_URL = "<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>";
  </script>

  <!-- ✅ Load JS only on index page -->
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
        <li><a href="/index.php" class="<?php echo ($currentPage == 'index.php') ? 'active' : ''; ?>">Home</a></li>
        <li><a href="/pages/explore.php" class="<?php echo ($currentPage == 'explore.php' || $currentPage == 'view_experience.php') ? 'active' : ''; ?>">Explore</a></li>
        <li><a href="/pages/manage.php" class="<?php echo ($currentPage == 'manage.php') ? 'active' : ''; ?>">Manage</a></li>
        <li><a href="/pages/about.php" class="<?php echo ($currentPage == 'about.php') ? 'active' : ''; ?>">About Us</a></li>
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
