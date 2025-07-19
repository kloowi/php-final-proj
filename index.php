<?php include 'includes/header-index.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Details - <?php echo htmlspecialchars($experience_title); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
  <link rel="stylesheet" href="assets/css/index.css">
  <script src="assets/js/index.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<div class="hero">
    <div class="slideshow-container">
        <div class="slide active" style="background-image: url('assets/images/index/slideshow/Slideshow1.jpg')"></div>
        <div class="slide" style="background-image: url('assets/images/index/slideshow/Slideshow2.jpg')"></div>
        <div class="slide" style="background-image: url('assets/images/index/slideshow/Slideshow3.jpg')"></div>
        <div class="slide" style="background-image: url('assets/images/index/slideshow/Slideshow4.jpg')"></div>
        <div class="slide" style="background-image: url('assets/images/index/slideshow/Slideshow5.jpg')"></div>
    </div>
    
    <div class="hero-content">
        <h1>Your gateway to discovering Manila like a local.</h1>
        <div class="search-box-wrapper">
          <form class="search-box" method="get" action="pages/explore.php">
            <div class="search-input-container">
              <input type="text" name="search" placeholder="What do you want to do today?" value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
              <button type="button" class="clear-search" id="clearSearch" style="display: none;">
                <i class="fas fa-times"></i>
              </button>
            </div>
            <button type="submit">Search</button>
          </form>
        </div>
    </div>
    
    <div class="slideshow-nav">
        <div class="nav-circle active" data-slide="0"></div>
        <div class="nav-circle" data-slide="1"></div>
        <div class="nav-circle" data-slide="2"></div>
    </div>
    
</div>
<div class="intro-section">
    <div class="intro-text">
        <h2>Join us for an adventure!</h2>
        <p>Explore Manila's hidden gems, guided by locals. From food crawls to heritage walks — your story starts here.</p>
        <div class="features-icons-row">
          <div class="feature-icon-label">
            <span class="material-icons">map</span>
            <span>Smart Itinerary</span>
          </div>
          <div class="feature-icon-label">
            <span class="material-icons">flash_on</span>
            <span>Fast Book</span>
          </div>
          <div class="feature-icon-label">
            <span class="material-icons">local_offer</span>
            <span>Best Deals</span>
          </div>
          <div class="feature-icon-label">
            <span class="material-icons">grade</span>
            <span>Top Picks</span>
          </div>
          <div class="feature-icon-label">
            <span class="material-icons">support_agent</span>
            <span>24/7 Support</span>
          </div>
        </div>
        <a href="pages/explore.php" class="explore-btn">Explore</a>
    </div>
    <div class="intro-image">
        <img src="assets/images/index/main-index.png" alt="Adventure">
    </div>
</div>
<hr style="margin: 20px auto; width: 80%; border: none; border-top: 1px solid #e0e0e0;">
<div class="popular-section">
    <h3>Most Popular Experiences</h3>

    <div class="experience">
        <img src="<?php echo $basePath; ?>/images/index/intramuros.jpg" alt="Intramuros">
        <div class="experience-details">
            <h4>Intramuros Heritage Walk</h4>
            <p>Walk through History in the stone fortress of Fort Santiago. Explore cobbled paths, lush gardens, and preserved Spanish-era architecture.</p>
        </div>
    </div>

    <div class="experience">
        <img src="<?php echo $basePath; ?>/images/index/quiapo.png" alt="Quiapo Walk">
        <div class="experience-details">
            <h4>Quiapo Devotion and Market Walk</h4>
            <p>Visit the famous Quiapo Church and witness strong local devotion. Discover herbal remedies, religious icons, and traditional healing.</p>
        </div>
    </div>

    <div class="experience">
        <img src="<?php echo $basePath; ?>/images/index/binondo.png" alt="Binondo Crawl">
        <div class="experience-details">
            <h4>Binondo Food Crawl</h4>
            <p>Join a guided food crawl through Binondo's historical alleys and temples. Taste authentic dumplings, lumpia, siopao, and fusion dishes.</p>
        </div>
    </div>
</div>

<hr style="margin: 20px auto; width: 80%; border: none; border-top: 1px solid #e0e0e0;">

<div class="reviews-section">
    <h3>What Our Travelers Are Saying</h3>
    
    <?php
    // Include database connection
    include_once 'includes/db_connect.php';
    
    if ($pdo) {
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        echo "<pre>Tables: " . print_r($tables, true) . "</pre>";
        $stmt2 = $pdo->query("SELECT * FROM Reviews");
        $reviews = $stmt2->fetchAll();
        echo "<pre>Reviews: " . print_r($reviews, true) . "</pre>";
    } else {
        echo "No DB connection!";
    }
    ?>
</div>

<?php include 'includes/footer-index.php'; ?>
