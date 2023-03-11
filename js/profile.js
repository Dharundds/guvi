$(document).ready(function () {
  toggleState = true;
  function setField(state) {
    $("#username").prop("disabled", true);
    $("#email").prop("disabled", true);
    $("#fname").prop("disabled", state);
    $("#dob").prop("disabled", state);
    $("#age").prop("disabled", state);
    $("#phone").prop("disabled", state);
  }

  setField(toggleState);
  const edit_btn = document.getElementById("edit_btn");

  $.ajax({
    url: "../php/checkAuth.php",
    crossDomain: true,
    type: "post",
    data: JSON.stringify({ session_id: localStorage.getItem("session_id") }),
    success: function (res) {
      if (!res) window.location.pathname = "/login.html";
    },
  });
  function putValue(id, val) {
    document.getElementById(id).value = val;
  }

  $.ajax({
    url: "../php/profile.php",
    crossDomain: true,
    type: "get",
    dataType: "json",
    success: function (res) {
      res.map((v, key) => {
        Object.keys(v).forEach(function (val, index) {
          console.log(val);
          putValue(val, v[val]);
        });
      });
    },
    error: function (data) {
      console.log(data);
    },
  });

  $("#edit_btn").click(function (e) {
    e.preventDefault();
    if (edit_btn.value === "edit") {
      toggleState = !toggleState;
      setField(toggleState);
      edit_btn.innerText = "Save";
      edit_btn.value = "save";
    } else if (edit_btn.value === "save") {
      $.ajax({
        url: "../php/editProfile.php",
        crossDomain: true,
        type: "post",
        data: $("#edit").serialize(),
        success: function (data) {
          console.log(data);
          toggleState = !toggleState;
          setField(toggleState);
          edit_btn.innerText = "Edit";
          edit_btn.value = "edit";
        },
      });
    }
  });
  $("#logoutButton").click(function () {
    $.ajax({
      url: "../php/logout.php",
      crossDomain: true,
      type: "get",
      success: function (data) {
        console.log(data);
        localStorage.clear();
        window.location.pathname = "/login.html";
      },
    });
  });
});
