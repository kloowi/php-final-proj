<?php include 'includes/header-index.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Guest Details - <?php echo htmlspecialchars($experience_title); ?></title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
  <link rel="stylesheet" href="assets/css/index.css">
</head>
<body>
<div class="hero">
    <h1>Your gateway to discovering Manila like a local.</h1>
    <div class="search-box">
        <h3>Search Experiences</h3>
        <input type="text" placeholder="Search...">
        <button>Search Vacation</button>
    </div>
</div>

<div class="intro-section">
    <div class="intro-text">
        <h2>Join us for an adventure!</h2>
        <p>Explore Manila's hidden gems, guided by locals. From food crawls to heritage walks — your story starts here.</p>
        <!-- <div class="features">
            <div>
                <img src="assets/images/index/v10_27.png" alt="Features" width="40">
                <p>Feature</p>
            </div>
        </div> -->
        <a href="/pages/explore.php" class="explore-btn">Explore</a>
    </div>
    <div class="intro-image">
        <img src="<?php echo $basePath; ?>/images/index/v10_33.png" alt="Adventure">
    </div>
</div>

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

<?php include 'includes/footer.php'; ?>
