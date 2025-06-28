$(document).ready(function () {
  $("#loginForm").submit(function (e) {
    e.preventDefault();
    const email = $("#email").val();
    const password = $("#password").val();

    $.ajax({
      type: "POST",
      url: "php/login.php",
      data: { email, password },
      success: function (response) {
        const res = JSON.parse(response);
        if (res.status === "success") {
          localStorage.setItem("email", email); // ✅ Set session before alert
          alert("Login successful");
          setTimeout(() => {
            window.location.href = "profile.html"; // ✅ Delay redirect
          }, 100);
        } else {
          alert("Login failed. Check credentials.");
        }
      },
      error: function () {
        alert("Server error during login.");
      }
    });
  });
});
