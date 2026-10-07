document.addEventListener("DOMContentLoaded", () => {
  /* ---------- Feedback: top loading bar + button loading state ---------- */
  const showProgress = () => {
    if (document.getElementById("progress")) return;
    const bar = document.createElement("div");
    bar.id = "progress"; bar.className = "progress"; bar.setAttribute("role", "progressbar");
    bar.setAttribute("aria-label", "Loading"); bar.innerHTML = "<span></span>";
    document.body.appendChild(bar);
  };
  const setLoading = btn => {
    if (!btn || !btn.dataset.loading) return;
    btn.dataset.label = btn.textContent.trim();
    btn.classList.add("is-loading");
    btn.textContent = btn.dataset.loading;
    btn.disabled = true;
  };
  document.querySelectorAll("form").forEach(form => {
    form.addEventListener("submit", event => {
      if (event.defaultPrevented) return;
      showProgress();
      const btn = form.querySelector("[data-loading]");
      // Disable after the browser has read the form so nothing is lost.
      setTimeout(() => setLoading(btn), 0);
    });
  });
  window.addEventListener("pageshow", event => {
    if (!event.persisted) return;
    document.getElementById("progress")?.remove();
    document.querySelectorAll(".is-loading").forEach(b => {
      b.classList.remove("is-loading"); b.disabled = false; b.textContent = b.dataset.label;
    });
  });

  /* ---------- Show / hide password ---------- */
  document.querySelectorAll("[data-toggle]").forEach(btn => {
    btn.addEventListener("click", () => {
      const input = document.getElementById(btn.dataset.toggle);
      const show = input.type === "password";
      input.type = show ? "text" : "password";
      btn.setAttribute("aria-pressed", String(show));
      btn.setAttribute("aria-label", show ? "Hide password" : "Show password");
    });
  });

  /* ---------- Modals ---------- */
  const editModal = document.getElementById("editModal");
  const deleteModal = document.getElementById("deleteModal");
  const closeAll = () => { [editModal, deleteModal].forEach(m => { if (m) m.hidden = true; }); pendingForm = null; };
  let pendingForm = null;

  document.querySelectorAll(".edit-btn").forEach(button => {
    button.addEventListener("click", () => {
      document.getElementById("editId").value = button.dataset.id;
      document.getElementById("editTitle").value = button.dataset.title;
      document.getElementById("editDescription").value = button.dataset.description;
      document.getElementById("editDue").value = button.dataset.due;
      document.getElementById("editPriority").value = button.dataset.priority;
      editModal.hidden = false;
      document.getElementById("editTitle").focus();
    });
  });

  // Confirming destructive behavior: warn before deleting
  document.querySelectorAll(".delete-btn").forEach(button => {
    button.addEventListener("click", () => {
      pendingForm = button.closest("form");
      document.getElementById("deleteName").textContent = "“" + pendingForm.dataset.title + "”";
      deleteModal.hidden = false;
      document.getElementById("deleteCancel").focus(); // safe default
    });
  });
  const confirmBtn = document.getElementById("deleteConfirm");
  if (confirmBtn) {
    confirmBtn.addEventListener("click", () => {
      if (!pendingForm) return;
      showProgress(); setLoading(confirmBtn);
      pendingForm.submit();
    });
    document.getElementById("deleteCancel").addEventListener("click", closeAll);
  }

  document.querySelectorAll(".modal-close, .cancel-edit").forEach(b => b.addEventListener("click", closeAll));
  [editModal, deleteModal].forEach(m => {
    if (m) m.addEventListener("click", event => { if (event.target === m) closeAll(); });
  });
  document.addEventListener("keydown", event => { if (event.key === "Escape") closeAll(); });

  /* ---------- Task filter (All / Pending / Completed) ---------- */
  const filterBtns = document.querySelectorAll(".filter-btn");
  const cards = document.querySelectorAll(".task-card");
  const noMatch = document.getElementById("noMatch");
  filterBtns.forEach(btn => {
    btn.addEventListener("click", () => {
      filterBtns.forEach(b => b.classList.toggle("active", b === btn));
      let visible = 0;
      cards.forEach(card => {
        const show = btn.dataset.filter === "all" || card.dataset.state === btn.dataset.filter;
        card.hidden = !show;
        if (show) visible++;
      });
      if (noMatch) noMatch.hidden = visible !== 0;
    });
  });
});
