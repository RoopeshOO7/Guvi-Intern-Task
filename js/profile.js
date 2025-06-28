$(document).ready(function () {
  const email = localStorage.getItem("email");

  if (!email) {
    alert("You are not logged in");
    window.location.href = "dashboard.html";
    return;
  }

  $.ajax({
    type: "POST",
    url: "php/fetch_profile.php",
    data: { email: email },
    success: function (response) {
      try {
        const res = JSON.parse(response);
        if (res.status === "success") {
          $("#username").text(res.data.username);
          $("#email").text(res.data.email);
          $("#dob").text(res.data.dob);
          $("#phone").text(res.data.phone);
        } else {
          alert("Could not load profile data");
          window.location.href = "dashboard.html";
        }
      } catch (e) {
        console.error("Invalid JSON from fetch_profile.php:", response);
        alert("Server error while loading profile.");
        window.location.href = "dashboard.html";
      }
    },
    error: function () {
      alert("Error loading profile.");
      window.location.href = "dashboard.html";
    }
  });

  $("#logoutBtn").click(function () {
    localStorage.removeItem("email");
    $.ajax({
      type: "POST",
      url: "php/logout.php",
      success: function () {
        window.location.href = "dashboard.html";
      }
    });
  });
});
