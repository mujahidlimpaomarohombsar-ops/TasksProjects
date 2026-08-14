const menuBtn = document.getElementById("menuBtn");
const nav = document.querySelector(".nav");

// Set the current year in the footer
const year = document.getElementById("year");
if (year) {
  year.textContent = new Date().getFullYear();
}

if (menuBtn && nav) {
  menuBtn.addEventListener("click", () => {
    const isOpen = nav.classList.toggle("open");
    menuBtn.setAttribute("aria-expanded", String(isOpen));
  });
}
