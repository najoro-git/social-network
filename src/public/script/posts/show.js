// Compteur commentaire
const textarea = document.getElementById("commentContent");
const counter = document.getElementById("commentCounter");

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
