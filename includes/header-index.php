<?php
session_start();
  // Simplified and more reliable path detection
  $scriptPath = $_SERVER['SCRIPT_NAME'];
  $isIndex = (basename($scriptPath) === 'index.php');
  $headerClass = $isIndex ? 'transparent' : 'white-bg';
  
  // Determine base path based on current directory structure
  if ($isIndex) {
    // We're on the main index.php
    $basePath = 'assets';
  } else {
    // We're in a subdirectory (like pages/)
    $basePath = '../assets';
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Discover Manila</title>
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/style.css">
  <link rel="stylesheet" href="<?php echo $basePath; ?>/css/header.css">
  <script>
    const BASE_URL = "<?php echo dirname($_SERVER['SCRIPT_NAME']); ?>";
  </script>
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
        <li><a href="<?php echo $isIndex ? 'index.php' : '../index.php'; ?>" class="active">Home</a></li>
        <li><a href="<?php echo $isIndex ? './pages/explore.php' : 'explore.php'; ?>">Explore</a></li>
        <li><a href="<?php echo $isIndex ? './pages/manage.php' : 'manage.php'; ?>">Manage</a></li>
        <li><a href="<?php echo $isIndex ? './pages/about.php' : 'about.php'; ?>">About Us</a></li>
      </ul>
      <div class="login-link">
        <?php if (isset($_SESSION['user_id'])): ?>
          <a href="logout.php">Log Out</a>
        <?php else: ?>
          <a href="<?php echo $isIndex ? './pages/login.php' : 'login.php'; ?>">Log In</a>
        <?php endif; ?>
      </div>
    </nav>
  </header>

</body>
</html> 