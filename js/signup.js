
$(document).ready(function () {
  $("#signupForm").on("submit", function (e) {
    e.preventDefault();
    const user = {
      username: $("#username").val(),
      email: $("#email").val(),
      dob: $("#dob").val(),
      phone: $("#phone").val(),
      password: $("#password").val()
    };
    $.ajax({
      url: "php/signup.php",
      type: "POST",
      contentType: "application/json",
      data: JSON.stringify(user),
      success: function (res) {
        if (res.status === "exists") alert("Account already exists!");
        else if (res.status === "success") {
          alert("Account created!");
          window.location.href = "login.html";
        } else alert("Signup failed!");
      }
    });
  });
});
