<?php include 'includes/header.php'; ?>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fff;
    }

    .hero {
        background: url('assets/images/view.jpg') no-repeat center center;
        background-size: cover;
        color: white;
        text-align: center;
        padding: 100px 20px 150px;
        position: relative;
    }

    .search-box {
    background: white;
    color: black;
    padding: 30px;
    max-width: 600px;
    margin: 0 auto;
    border-radius: 20px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.2);
    position: absolute;
    bottom: -80px;
    left: 50%;
    transform: translateX(-50%);
    text-align: center;
    }

  .search-box h3 {
    font-size: 20px;
    font-weight: bold;
    color: #007bff;
    margin-bottom: 20px;
  }

  .search-box form {
    display: flex;
    justify-content: center;
    gap: 10px;
    flex-wrap: wrap;
  }

  .search-box input[type="text"] {
    padding: 12px 20px;
    border: none;
    border-radius: 20px;
    background: #f4f4f4;
    font-size: 14px;
    width: 220px;
    box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);
  }

  .search-box button {
    background-color: #1E90FF;
    color: white;
    padding: 12px 30px;
    border: none;
    border-radius: 20px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    transition: background-color 0.3s ease;
  }

  .search-box button:hover {
    background-color: #0072e6;
  }

    .intro-section {
        display: flex;
        align-items: center;
        justify-content: space-around;
        padding: 100px 20px 40px;
    }

    .intro-text {
        max-width: 500px;
    }

    .intro-image img {
        max-width: 300px;
        border-radius: 15px;
    }

    .features {
        display: flex;
        justify-content: center;
        gap: 30px;
        margin-top: 30px;
    }

    .features div {
        text-align: center;
    }

    .explore-btn {
        margin-top: 20px;
        background: #007bff;
        color: white;
        padding: 10px 20px;
        border-radius: 10px;
        border: none;
        cursor: pointer;
    }

    .popular-section {
        padding: 40px 20px;
        max-width: 1000px;
        margin: 0 auto;
    }

    .popular-section h3 {
        color: #007bff;
        margin-bottom: 30px;
    }

    .experience {
        display: flex;
        align-items: flex-start;
        margin-bottom: 30px;
        background: #f9f9f9;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    }

    .experience img {
        width: 150px;
        height: auto;
        border-radius: 10px;
        margin-right: 20px;
    }

    .experience-details h4 {
        margin: 0;
        color: #333;
    }

    .experience-details p {
        margin-top: 5px;
        font-size: 14px;
        color: #555;
    }
</style>

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
        <div class="features">
            <div>
                <img src="assets/images/v10_27.png" alt="Features" width="40">
                <p>Feature</p>
            </div>
        </div>
        <button class="explore-btn">Explore</button>
    </div>
    <div class="intro-image">
        <img src="assets/images/v10_33.png" alt="Adventure">
    </div>
</div>

<div class="popular-section">
    <h3>Most Popular Experiences</h3>

    <div class="experience">
        <img src="assets/images/intramuros.jpg" alt="Intramuros">
        <div class="experience-details">
            <h4>Intramuros Heritage Walk</h4>
            <p>Walk through History in the stone fortress of Fort Santiago. Explore cobbled paths, lush gardens, and preserved Spanish-era architecture.</p>
        </div>
    </div>

    <div class="experience">
        <img src="assets/images/quiapo.png" alt="Quiapo Walk">
        <div class="experience-details">
            <h4>Quiapo Devotion and Market Walk</h4>
            <p>Visit the famous Quiapo Church and witness strong local devotion. Discover herbal remedies, religious icons, and traditional healing.</p>
        </div>
    </div>

    <div class="experience">
        <img src="assets/images/binondo.png" alt="Binondo Crawl">
        <div class="experience-details">
            <h4>Binondo Food Crawl</h4>
            <p>Join a guided food crawl through Binondo’s historical alleys and temples. Taste authentic dumplings, lumpia, siopao, and fusion dishes.</p>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
