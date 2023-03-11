$(document).ready(function () {
  $.ajax({
    url: "../php/checkAuth.php",
    crossDomain: true,
    type: "post",
    data: JSON.stringify({ session_id: localStorage.getItem("session_id") }),
    success: function (res) {
      // window.location.pathname = "/profile.html";
      if (res) window.location.pathname = "/profile.html";
      else localStorage.clear("session_id");
    },
    error: function (data) {
      console.log(data);
    },
  });

  $("button").click(function (e) {
    e.preventDefault();
    if (
      $("input[name=username]").val() !== "" &&
      $("input[name=password]").val() !== ""
    ) {
      $.ajax({
        url: "../php/login.php",
        crossDomain: true,
        type: "post",
        dataType: "json",
        data: $("form").serialize(),
        success: function (res) {
          localStorage.setItem("username", res.name);
          localStorage.setItem("session_id", res.session);
          window.location.pathname = "/profile.html";
          console.log(res);
        },
      });
    }
  });
});
