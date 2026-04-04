/* ========================================
   MAIN.JS — SocialNet Cyberpunk
======================================== */

// ----------------------------------------
// CHAR COUNTER
// ----------------------------------------
function initCharCounter(textareaId, counterId, max = 500) {
  const textarea = document.getElementById(textareaId);
  const counter = document.getElementById(counterId);
  if (!textarea || !counter) return;

  function update() {
    const len = textarea.value.length;
    counter.textContent = len + " / " + max;
    counter.className = "char-counter";
    if (len > max * 0.9) counter.classList.add("danger");
    else if (len > max * 0.75) counter.classList.add("warn");
  }

  textarea.addEventListener("input", update);
  update();
}

// ----------------------------------------
// IMAGE PREVIEW
// ----------------------------------------
function initImagePreview(inputId, previewId) {
  const input = document.getElementById(inputId);
  const preview = document.getElementById(previewId);
  if (!input || !preview) return;

  input.addEventListener("change", () => {
    preview.innerHTML = "";
    const file = input.files[0];
    if (!file) return;

    const wrap = document.createElement("div");
    wrap.className = "image-preview-wrap";

    const img = document.createElement("img");
    img.src = URL.createObjectURL(file);

    const remove = document.createElement("button");
    remove.className = "image-preview-remove";
    remove.innerHTML =
      '<span class="material-icons" style="font-size:0.9rem">close</span>';
    remove.addEventListener("click", () => {
      preview.innerHTML = "";
      input.value = "";
    });

    wrap.appendChild(img);
    wrap.appendChild(remove);
    preview.appendChild(wrap);
  });
}

// ----------------------------------------
// LIKE BUTTON (AJAX)
// ----------------------------------------
function initLikeButtons() {
  document.querySelectorAll(".like-btn").forEach((btn) => {
    btn.addEventListener("click", async () => {
      const postId = btn.dataset.postId;
      const icon = btn.querySelector(".material-icons");
      const count = btn.querySelector(".like-count");

      try {
        const res = await fetch("/posts/like", {
          method: "POST",
          headers: { "Content-Type": "application/x-www-form-urlencoded" },
          body: `post_id=${postId}`,
        });
        const data = await res.json();

        if (data.success) {
          // Animation
          btn.classList.add("pop");
          setTimeout(() => btn.classList.remove("pop"), 400);

          if (data.liked) {
            btn.classList.add("liked");
            icon.textContent = "favorite";
          } else {
            btn.classList.remove("liked");
            icon.textContent = "favorite_border";
          }

          count.textContent = data.total;
        }
      } catch (err) {
        console.error("Like error:", err);
      }
    });
  });
}

// ----------------------------------------
// CONFIRM DELETE
// ----------------------------------------
function initDeleteConfirm() {
  document.querySelectorAll(".form-delete").forEach((form) => {
    form.addEventListener("submit", (e) => {
      if (!confirm("Supprimer définitivement ?")) {
        e.preventDefault();
      }
    });
  });
}

// ----------------------------------------
// NAVBAR ACTIVE LINK
// ----------------------------------------
function initNavActive() {
  const path = window.location.pathname;
  document.querySelectorAll(".navbar-links a").forEach((a) => {
    if (a.getAttribute("href") === path) {
      a.style.color = "var(--green)";
      a.style.borderColor = "var(--border)";
      a.style.background = "var(--bg-glass)";
    }
  });
}

// ----------------------------------------
// FLASH MESSAGE AUTO-HIDE
// ----------------------------------------
function initFlashMessages() {
  document.querySelectorAll(".alert").forEach((alert) => {
    setTimeout(() => {
      alert.style.transition = "opacity 0.5s ease, transform 0.5s ease";
      alert.style.opacity = "0";
      alert.style.transform = "translateY(-10px)";
      setTimeout(() => alert.remove(), 500);
    }, 4000);
  });
}

// ----------------------------------------
// INIT ALL
// ----------------------------------------
document.addEventListener("DOMContentLoaded", () => {
  initLikeButtons();
  initDeleteConfirm();
  initNavActive();
  initFlashMessages();
  initCharCounter("content", "contentCounter", 500);
  initCharCounter("commentContent", "commentCounter", 500);
  initImagePreview("image", "imagePreview");
});
