$(document).ready(function () {
  $("#signup").click(function (e) {
    e.preventDefault();
    $.ajax({
      url: "../php/register.php",
      crossDomain: true,
      type: "post",
      data: $("#register").serialize(),
      success: function (data) {
        console.log(data);
      },
    });
  });
});
