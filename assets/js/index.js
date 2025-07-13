document.addEventListener("DOMContentLoaded", () => {
  console.log("Index JS loaded!");
  
  // Slideshow functionality
  const slides = document.querySelectorAll('.slide');
  const navCircles = document.querySelectorAll('.nav-circle');
  const prevBtn = document.querySelector('.prev-btn');
  const nextBtn = document.querySelector('.next-btn');
  let currentSlide = 0;
  let slideInterval;

  const showSlide = (index) => {
    // Remove active class from all slides and circles
    slides.forEach(slide => slide.classList.remove('active'));
    navCircles.forEach(circle => circle.classList.remove('active'));
    
    // Add active class to current slide and circle
    slides[index].classList.add('active');
    navCircles[index % 3].classList.add('active'); // Use modulo to map 5 slides to 3 circles
    
    currentSlide = index;
  };

  const nextSlide = () => {
    const nextIndex = (currentSlide + 1) % slides.length;
    showSlide(nextIndex);
  };

  const prevSlide = () => {
    const prevIndex = (currentSlide - 1 + slides.length) % slides.length;
    showSlide(prevIndex);
  };

  const startSlideshow = () => {
    slideInterval = setInterval(nextSlide, 5000); // Change slide every 5 seconds
  };

  const stopSlideshow = () => {
    clearInterval(slideInterval);
  };

  // Add click event listeners to navigation circles
  navCircles.forEach((circle, index) => {
    circle.addEventListener('click', () => {
      // Map circle index to slide index (0->0, 1->2, 2->4 for 5 slides)
      const slideIndex = index * 2;
      showSlide(slideIndex);
      stopSlideshow();
      startSlideshow(); // Restart the timer
    });
  });

  // Add click event listeners to arrow buttons
  if (prevBtn) {
    prevBtn.addEventListener('click', () => {
      prevSlide();
      stopSlideshow();
      startSlideshow(); // Restart the timer
    });
  }

  if (nextBtn) {
    nextBtn.addEventListener('click', () => {
      nextSlide();
      stopSlideshow();
      startSlideshow(); // Restart the timer
    });
  }

  // Keyboard navigation
  document.addEventListener('keydown', (e) => {
    if (e.key === 'ArrowLeft') {
      prevSlide();
      stopSlideshow();
      startSlideshow();
    } else if (e.key === 'ArrowRight') {
      nextSlide();
      stopSlideshow();
      startSlideshow();
    }
  });

  // Start the slideshow
  if (slides.length > 0) {
    startSlideshow();
    
    // Pause slideshow on hover
    const hero = document.querySelector('.hero');
    if (hero) {
      hero.addEventListener('mouseenter', stopSlideshow);
      hero.addEventListener('mouseleave', startSlideshow);
    }
  }
}); 