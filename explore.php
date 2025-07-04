<?php include 'includes/header_white.php'; ?>
<style>
    body {
        margin: 0;
        font-family: Arial, sans-serif;
        background: #fff;
    }

    .explore-container {
        max-width: 1200px;
        margin: 40px auto;
        padding: 0 20px;
    }

    h2 {
        color: #007bff;
        font-size: 24px;
        margin-bottom: 20px;
    }

    .category-title {
        font-weight: bold;
        color: #007bff;
        margin: 40px 0 15px;
        font-size: 22px;
    }

    .experience-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
    }

    .experience-card {
        background: #f9f9f9;
        border-radius: 15px;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        overflow: hidden;
        transition: transform 0.2s ease;
    }

    .experience-card:hover {
        transform: translateY(-5px);
    }

    .experience-image {
        background: #ccc url('assets/images/placeholder.jpg') no-repeat center center;
        background-size: cover;
        height: 180px;
    }

    .experience-content {
        padding: 15px;
    }

    .experience-title {
        font-weight: bold;
        font-size: 16px;
        margin: 0;
        color: #333;
    }

    .experience-subtext {
        font-size: 13px;
        color: #666;
        margin-top: 5px;
    }

    @media (max-width: 600px) {
        .experience-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="explore-container">
    <h2>Explore Manila</h2>

    <!-- Historical & Cultural -->
    <div class="category-title">Historical & Cultural Sites</div>
    <div class="experience-grid">
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Intramuros Heritage Walk</p><p class="experience-subtext">Manila, Fort Santiago ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">San Agustin Church</p><p class="experience-subtext">Manila, Intramuros ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Manila Cathedral</p><p class="experience-subtext">Intramuros ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Casa Manila</p><p class="experience-subtext">Intramuros ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Escolta Street</p><p class="experience-subtext">Binondo, Manila ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Paco Park</p><p class="experience-subtext">San Marcelino ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Intramuros Heritage Walk</p><p class="experience-subtext">Manila, Fort Santiago ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Intramuros Heritage Walk</p><p class="experience-subtext">Manila, Fort Santiago ★4.5</p></div></div>
    </div>

    <!-- Food & Market -->
    <div class="category-title">Food & Market Experiences</div>
    <div class="experience-grid">
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Binondo Chinatown</p><p class="experience-subtext">Ongpin Street ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Divisoria Market</p><p class="experience-subtext">Shopping Hub ★4.5</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Quiapo Market</p><p class="experience-subtext">Manila, Philippines ★4.5</p></div></div>
    </div>

    <!-- Nature & Scenic Spots -->
    <div class="category-title">Nature & Scenic Spots</div>
    <div class="experience-grid">
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Rizal Park</p><p class="experience-subtext">Luneta ★4.6</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Baywalk</p><p class="experience-subtext">Manila Bay Area ★4.4</p></div></div>
        <div class="experience-card"><div class="experience-image"></div><div class="experience-content"><p class="experience-title">Arroceros Forest Park</p><p class="experience-subtext">Ermita ★4.3</p></div></div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>
