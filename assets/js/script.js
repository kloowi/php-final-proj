document.addEventListener("DOMContentLoaded", () => {
  console.log("JS loaded!");
  const header = document.getElementById("main-header");
  const logo = document.getElementById("site-logo");

  const blueLogo = "/APPDEV/php-final-proj/assets/images/logo/blue-logo.png";
  const whiteLogo = "/APPDEV/php-final-proj/assets/images/logo/white-logo.png";

  let logoClicked = false;

  const toggleHeaderBackground = () => {
    if (window.scrollY > 50) {
      header.classList.remove("transparent");
      header.classList.add("white-bg");
      if (!logoClicked && logo) logo.src = blueLogo;
    } else {
      header.classList.remove("white-bg");
      header.classList.add("transparent");
      if (!logoClicked && logo) logo.src = whiteLogo;
    }
  };

  // Scroll-based toggle
  toggleHeaderBackground();
  window.addEventListener("scroll", () => {
    console.log("Window scrolled");
    logoClicked = false;
    toggleHeaderBackground();
  });

  // Hover effect on entire header
  header.addEventListener("mouseenter", () => {
    console.log("Header mouseenter");
    header.classList.add("white-bg");
    header.classList.remove("transparent");
    if (!logoClicked && logo) logo.src = blueLogo;
  });

  header.addEventListener("mouseleave", () => {
    console.log("Header mouseleave");
    logoClicked = false;
    if (window.scrollY < 50) {
      header.classList.remove("white-bg");
      header.classList.add("transparent");
      if (logo) logo.src = whiteLogo;
    }
  });

  header.addEventListener("click", () => {
    console.log("Header clicked");
    if (logo) logo.src = blueLogo;
    logoClicked = true;
  });
});
