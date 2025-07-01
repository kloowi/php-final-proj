document.addEventListener("DOMContentLoaded", () => {
  const header = document.getElementById("main-header");

  const toggleHeaderBackground = () => {
    if (window.scrollY > 50) {
      header.classList.remove("transparent");
      header.classList.add("white-bg");
    } else {
      header.classList.remove("white-bg");
      header.classList.add("transparent");
    }
  };

  // On load and scroll
  toggleHeaderBackground();
  window.addEventListener("scroll", toggleHeaderBackground);

  // Hover effect
  header.addEventListener("mouseenter", () => {
    header.classList.remove("transparent");
    header.classList.add("white-bg");
  });

  header.addEventListener("mouseleave", () => {
    if (window.scrollY < 50) {
      header.classList.remove("white-bg");
      header.classList.add("transparent");
    }
  });
});
