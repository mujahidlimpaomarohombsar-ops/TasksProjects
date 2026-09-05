const API_URL = "https://jsonplaceholder.typicode.com/users";

const loadUsersBtn = document.getElementById("loadUsersBtn");
const statusElement = document.getElementById("status");
const usersContainer = document.getElementById("usersContainer");

function setStatus(message, type = "") {
  statusElement.textContent = message;
  statusElement.className = type ? `status status--${type}` : "status";
}

function isValidUser(user) {
  return (
    user &&
    typeof user.name === "string" &&
    typeof user.username === "string" &&
    typeof user.email === "string" &&
    typeof user.phone === "string" &&
    user.company &&
       typeof user.company.name === "string"
  );
}

function renderUsers(users) {
  usersContainer.replaceChildren();

  users.forEach((user) => {
    const card = document.createElement("article");
    card.className = "user-card";

    const name = document.createElement("h2");
    name.textContent = user.name;

    const username = document.createElement("p");
    username.className = "user-card__username";
    username.textContent = `@${user.username}`;

    const details = document.createElement("dl");

    const fields = [
      ["Email", user.email],
      ["Phone", user.phone],
      ["Company", user.company.name],
    ];

    fields.forEach(([label, value]) => {
      const wrapper = document.createElement("div");
      wrapper.className = "field";

      const term = document.createElement("dt");
      term.textContent = label;

      const description = document.createElement("dd");
      description.textContent = value;

      wrapper.append(term, description);
      details.appendChild(wrapper);
    });

    card.append(name, username, details);
    usersContainer.appendChild(card);
  });
}

async function loadUsers() {
  loadUsersBtn.disabled = true;
  usersContainer.replaceChildren();
  setStatus("Loading...", "loading");

  try {
    const response = await fetch(API_URL);

    // check response
    if (!response.ok) {
      throw new Error(`Request failed with HTTP status ${response.status}.`);
    }

    const data = await response.json();

    // Validate that the API returned the expected structure.
    if (!Array.isArray(data) || data.length === 0 || !data.every(isValidUser)) {
      throw new Error("The API returned data in an unexpected format.");
    }

    renderUsers(data);
    setStatus(`${data.length} users loaded successfully.`, "success");
  } catch (error) {
    console.error("Unable to load users:", error);
    setStatus(
      `Unable to load users. ${error.message || "Please try again later."}`,
      "error"
    );

    const empty = document.createElement("p");
    empty.className = "empty";
    empty.textContent = "No user data is available right now.";
    usersContainer.appendChild(empty);
  } finally {
    loadUsersBtn.disabled = false;
  }
}

loadUsersBtn.addEventListener("click", loadUsers);
