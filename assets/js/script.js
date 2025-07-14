document.addEventListener("DOMContentLoaded", () => {
  console.log("JS loaded!");
  const header = document.getElementById("main-header");
  const logo = document.getElementById("site-logo");

  // Check if elements exist before proceeding
  if (!header || !logo) {
    console.log("Header or logo not found");
    return;
  }

const blueLogo = "/assets/images/logo/blue-logo.png";
const whiteLogo = "/assets/images/logo/white-logo.png";

  let logoClicked = false;
  let isScrolled = false;

  const toggleHeaderBackground = () => {
    if (window.scrollY > 50) {
      if (!header.classList.contains("white-bg")) {
        header.classList.remove("transparent");
        header.classList.add("white-bg");
        if (!logoClicked && logo) logo.src = blueLogo;
      }
      isScrolled = true;
    } else {
      if (!header.classList.contains("transparent")) {
        header.classList.remove("white-bg");
        header.classList.add("transparent");
        if (!logoClicked && logo) logo.src = whiteLogo;
      }
      isScrolled = false;
    }
  };

  // Scroll-based toggle
  toggleHeaderBackground();
  window.addEventListener("scroll", () => {
    logoClicked = false;
    toggleHeaderBackground();
  });

  // Hover effect on entire header
  header.addEventListener("mouseenter", () => { 
    if (!isScrolled) {
      header.classList.add("white-bg");
      header.classList.remove("transparent");
      if (!logoClicked && logo) logo.src = blueLogo;
    }
  });

  header.addEventListener("mouseleave", () => {
    logoClicked = false;
    if (!isScrolled) {
      header.classList.remove("white-bg");
      header.classList.add("transparent");
      if (logo) logo.src = whiteLogo;
    }
  });

  // Logo click handler
  logo.addEventListener("click", (e) => {
    e.stopPropagation();
    logo.src = blueLogo;
    logoClicked = true;
  });
});
