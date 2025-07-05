<?php include '../includes/header.php'; ?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
/* Wrapper for spacing below the header */
.page-wrapper {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    padding: 80px 20px 40px;
    background: #f7f7f7;
}

/* Main card */
.experience-card {
    background: #fff;
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
    padding: 30px;
    max-width: 1000px;
    width: 100%;
    font-family: Arial, sans-serif;
    color: #333;
    margin-top: 20px;
}

/* Image section */
.experience-card img {
    width: 100%;
    max-height: 400px;
    object-fit: cover;
    border-radius: 10px;
    margin-bottom: 20px;
}

/* Title and price */
.title-price {
    display: flex;
    align-items: center;
    justify-content: space-between;
    flex-wrap: wrap;
}

.title-price h1 {
    font-size: 28px;
    font-weight: bold;
    margin: 0;
}

.price-box-inline {
    font-size: 36px;
    color: #007bff;
    font-weight: bold;
}

.price-box-inline span {
    font-size: 14px;
    color: #777;
    font-weight: normal;
}

.subheading {
    font-size: 16px;
    color: #666;
    margin: 2px 0 30px;
}

/* Description and review layout */
.details-row {
    display: flex;
    gap: 30px;
    flex-wrap: wrap;
}

.col-70 {
    flex: 1 1 70%;
}

.col-30 {
    flex: 1 1 30%;
}

.card {
    background: #f9f9f9;
    padding: 20px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 20px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

/* Accordion toggle */
.accordion-toggle {
    background: #f1f1f1;
    padding: 14px 16px;
    border: none;
    width: 100%;
    text-align: left;
    font-weight: bold;
    border-radius: 6px;
    cursor: pointer;
    margin-top: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    transition: background 0.3s ease;
}

.accordion-toggle .arrow {
    transition: transform 0.3s ease;
}

.accordion-toggle.open .arrow {
    transform: rotate(90deg);
}

/* Accordion content */
.accordion-content {
    display: none;
    padding: 15px;
    background: #fff;
    margin-bottom: 10px;
    font-size: 14px;
    border-radius: 6px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.08);
}

/* Reviews */
.reviews {
    background: #eef4ff;
    padding: 20px;
    border-radius: 10px;
    font-size: 14px;
    margin-bottom: 20px;
}

.reviews a {
    color: #007bff;
    text-decoration: none;
}

/* Book Now button */
.book-btn {
    background: #007bff;
    color: white;
    padding: 12px 25px;
    border: none;
    border-radius: 10px;
    font-weight: bold;
    cursor: pointer;
    width: auto;
    display: block;
    margin: 0 auto;
    text-align: center;
}

@media (max-width: 768px) {
    .details-row {
        flex-direction: column;
    }

    .title-price {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
}
</style>

<div class="page-wrapper">
    <div class="experience-card">

        <!-- Header Image -->
        <img src="../assets/images/experiences/exp_6867c00ab3bef3.06653871.jpg" alt="Fort Santiago">

        <!-- Title and Price -->
        <div class="title-price">
            <h1>Fort Santiago (Intramuros)</h1>
            <div class="price-box-inline">₱350 <span>/guest</span></div>
        </div>
        <p class="subheading">Fort Santiago – Intramuros Heritage Walk<br>Step into the heart of Manila's colonial past.</p>

        <!-- Side-by-side Layout -->
        <div class="details-row">
            <div class="col-70">
                <div class="card">
                    <strong>Walk through history in the stone fortress of Fort Santiago.</strong>
                    <p>Once a powerful military base and now a national shrine, this tour brings you through cobbled paths, lush gardens, Rizal’s final footsteps, and preserved Spanish-era architecture.</p>
                    <p>Includes a guided walk, access to the Rizal Shrine Museum, and exploration of the scenic Pasig River walls.</p>
                </div>

                <!-- Accordion Section -->
                <button class="accordion-toggle">
                    What to Expect <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Expect a guided walking tour filled with historical insights, engaging stories, and photo-worthy views.</p>
                </div>

                <button class="accordion-toggle">
                    What's Included <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Admission, guided walk, museum access, and complimentary bottled water.</p>
                </div>

                <button class="accordion-toggle">
                    Before You Book <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Please wear comfortable walking shoes and bring sun protection. Tours run rain or shine.</p>
                </div>

                <button class="accordion-toggle">
                    Terms & Conditions <i class="fas fa-chevron-right arrow"></i>
                </button>
                <div class="accordion-content">
                    <p>Non-refundable. Rescheduling allowed 24 hours before start. Late arrivals may forfeit the slot.</p>
                </div>
            </div>

            <!-- Sidebar -->
            <div class="col-30">
                <div class="reviews">
                    <strong>Reviews ★★★★★</strong><br>
                    10 reviews<br><br>
                    “The guide made the history come alive. A must for first-timers!”<br>
                    <a href="#">Read all reviews</a>
                </div>

                <button class="book-btn">Book Now!</button>
            </div>
        </div>
    </div>
</div>

<!-- Accordion JS -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const buttons = document.querySelectorAll(".accordion-toggle");

    buttons.forEach(button => {
        button.addEventListener("click", () => {
            const content = button.nextElementSibling;
            const isOpen = content.style.display === "block";

            // Close all
            document.querySelectorAll(".accordion-content").forEach(c => c.style.display = "none");
            buttons.forEach(b => b.classList.remove("open"));

            if (!isOpen) {
                content.style.display = "block";
                button.classList.add("open");
            }
        });
    });
});
</script>

<?php include '../includes/footer.php'; ?>
