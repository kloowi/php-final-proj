<?php include 'includes/header.php'; ?>

<main>
  <section class="hero">
    <div class="overlay">
      <h1>Your gateway to discovering Manila like a local.</h1>
      <form class="search-bar">
        <input type="text" placeholder="Search Experiences">
        <button type="submit">Search Vacation</button>
      </form>
    </div>
  </section>

  <section class="adventure">
    <h2>Join us for an adventure!!</h2>
    <p>Explore Manila’s hidden gems, guided by locals. From food crawls to heritage walks — your story starts here.</p>
    <div class="features">
      <div class="feature-item">📸 Feature</div>
      <div class="feature-item">🚶 Feature</div>
      <div class="feature-item">📍 Feature</div>
      <div class="feature-item">🗺️ Feature</div>
    </div>
    <button class="explore-btn">Explore</button>
  </section>

  <section class="popular-experiences">
    <h2>Most Popular Experiences</h2>
    <div class="experience-list">
      <?php for ($i = 0; $i < 3; $i++): ?>
        <div class="experience-card">
          <img src="assets/images/experience.jpg" alt="Experience Image">
          <div class="experience-info">
            <h3>LOREM IPSUM</h3>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
          </div>
        </div>
      <?php endfor; ?>
    </div>
  </section>
</main>

<?php include 'includes/footer.php'; ?>
