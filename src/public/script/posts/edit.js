const textarea = document.getElementById("content");
const counter = document.getElementById("counter");

function updateCounter() {
  const len = textarea.value.length;
  counter.textContent = len + " / 500";
  counter.classList.toggle("warn", len > 450);
}

textarea.addEventListener("input", updateCounter);
updateCounter();
