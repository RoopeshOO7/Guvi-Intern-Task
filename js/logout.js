// js/logout.js
document.getElementById("logoutBtn").addEventListener("click", function () {
    localStorage.removeItem("email");
    window.location.href = "login.html";
});

