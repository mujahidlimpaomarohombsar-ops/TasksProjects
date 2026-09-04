document.addEventListener("DOMContentLoaded", () => {
  // ==========================================
  // 1. Navigation & Dynamic Year Logic
  // ==========================================
  const menuBtn = document.getElementById("menuBtn");
  const nav = document.querySelector(".nav");
  const year = document.getElementById("year");

  // Set the current year in the footer
  if (year) {
    year.textContent = new Date().getFullYear();
  }

  // Toggle mobile navigation menu
  if (menuBtn && nav) {
    menuBtn.addEventListener("click", () => {
      const isOpen = nav.classList.toggle("open");
      menuBtn.setAttribute("aria-expanded", String(isOpen));
    });
  }

  // ==========================================
  // 2. User Directory Fetch Logic
  // ==========================================
  const loadBtn = document.getElementById("loadBtn");
  const statusDiv = document.getElementById("status");
  const userList = document.getElementById("userList");

  if (loadBtn && statusDiv && userList) {
    loadBtn.addEventListener("click", fetchUsers);
  }

  async function fetchUsers() {
    // Reset UI & show loading indicator
    userList.innerHTML = "";
    statusDiv.className = "loading";
    statusDiv.textContent = "Loading...";

    try {
      const response = await fetch("https://jsonplaceholder.typicode.com/users");

      if (!response.ok) {
        throw new Error(`Server returned HTTP ${response.status}`);
      }

      const users = await response.json();

      // Clear loading status message
      statusDiv.textContent = "";

      // Render each user card dynamically
      users.forEach((user) => {
        const card = document.createElement("div");
        card.className = "user-card";
        card.innerHTML = `
          <h3>${user.name} (@${user.username})</h3>
          <p><strong>Email:</strong> ${user.email}</p>
          <p><strong>Phone:</strong> ${user.phone}</p>
          <p><strong>Company:</strong> ${user.company.name}</p>
        `;
        userList.appendChild(card);
      });
    } catch (error) {
      statusDiv.className = "error";
      statusDiv.textContent = `Failed to load users: ${error.message}`;
    }
  }
});