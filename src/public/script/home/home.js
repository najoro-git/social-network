// Compteur caractères
const textarea = document.getElementById("content");
const counter = document.getElementById("counter");
const imageInput = document.getElementById("image");
const imagePreview = document.getElementById("imagePreview");

if (textarea) {
  textarea.addEventListener("input", () => {
    const len = textarea.value.length;
    counter.textContent = len + " / 500";
    counter.classList.toggle("warn", len > 450);
  });
}

// Like AJAX
document.querySelectorAll(".like-btn").forEach((btn) => {
  btn.addEventListener("click", async () => {
    const postId = btn.dataset.postId;

    try {
      const res = await fetch("/posts/like", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `post_id=${postId}`,
      });

      const data = await res.json();

      if (data.success) {
        const heart = btn.querySelector(".heart");
        const count = btn.querySelector(".like-count");

        heart.textContent = data.liked ? "❤️" : "🤍";
        count.textContent = data.total;
        btn.title = data.liked ? "Retirer le like" : "Liker";

        if (data.liked) {
          btn.classList.add("liked");
          // Re-trigger animation
          heart.style.animation = "none";
          heart.offsetHeight;
          heart.style.animation = "";
        } else {
          btn.classList.remove("liked");
        }
      }
    } catch (err) {
      console.error("Erreur like:", err);
    }
  });
});

// Preview image
if (imageInput) {
  imageInput.addEventListener("change", () => {
    imagePreview.innerHTML = "";
    const file = imageInput.files[0];
    if (file) {
      const img = document.createElement("img");
      img.src = URL.createObjectURL(file);
      imagePreview.appendChild(img);
    }
  });
}
