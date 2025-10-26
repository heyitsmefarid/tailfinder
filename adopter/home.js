// Initialize Lucide Icons
lucide.createIcons();

// Set Username
document.getElementById("userName").textContent = "Fred Anthony";

// Notification Toggle
const notifBtn = document.getElementById("notifBtn");
const notifDropdown = document.getElementById("notifDropdown");

notifBtn.addEventListener("click", () => {
  notifDropdown.classList.toggle("hidden");

  const notifCount = document.getElementById("notifCount");
  notifCount.innerText = "0";
  notifCount.style.opacity = "0.5";
});
