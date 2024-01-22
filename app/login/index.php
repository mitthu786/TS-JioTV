<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);

include "functions.php";

function handleLogin()
{
  $msg = '';
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $otp_data = send_jio_otp($user);

    if ($otp_data["status"] == "error") {
      $msg = $otp_data["message"];
      header("Location: index.php?OtpError&msg=" . urlencode($msg));
      exit();
    } else {
      header("Location: otpVerify.php?user=" . $user);
      exit();
    }
  }

  renderLoginForm($msg);
}

function renderLoginForm($msg)
{
?>

  <!DOCTYPE html>
  <html lang="en">

  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
    <link rel="stylesheet" href="../assets/css/tslogin.css">
    <title>JioTV Login</title>
  </head>

  <body>
    <div>
      <img src="https://i.ibb.co/BcjC6R8/jiotv.png" alt="JioTV Logo" width="100px" height="100px" style="margin-left: auto; margin-right: auto; display: block" />
      <h1>JioTV Login</h1>
      <hr />
      <div class="alert" style="display: none">
        <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
      </div>
      <tab-container>
        <label class="toggleLabel">OTP Login</label>
        <tab-content>
          <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
            <div class="formcontainer">
              <div class="container">
                <input id="username" name="username" type="text" minlength="10" maxlength="10" placeholder="Mobile No. without +91" required value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>" />
              </div>
              <button type="submit" style="display: block; width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; font-size: 14px;">Login</button>
              <p style="text-align: center; font-size: small; opacity: 0.5">
                JioTV [ SNEH-TV ]
              </p>
            </div>
          </form>
        </tab-content>
      </tab-container>
    </div>
  </body>

  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const params = new URLSearchParams(window.location.search);
      const alertEl = document.querySelector(".alert");

      if (params.has("success")) {
        showAlert(alertEl, "Success!", "You have been logged in", "success", "#4CAF50");
        const currentProtocol = window.location.protocol;
        const currentHost = window.location.host;
        const currentPathname = window.location.pathname.replace("app/login/index.php", "index.php");
        setTimeout(function() {
          const newURL = currentProtocol + "//" + currentHost + currentPathname;
          window.location.replace(newURL);
        }, 500);
      } else if (params.has("error") || params.has("OtpError")) {
        const errorMsg = params.get("msg");
        showAlert(alertEl, "Error!", errorMsg, "error", "#f44336");
      }
    });

    function showAlert(alertEl, title, message, type, color) {
      alertEl.innerHTML = `
      <span class="closebtn" onclick="closeAlert(this.parentElement);">&times;</span>
      <strong>${title}</strong> ${message}
    `;
      alertEl.classList.add(type);
      alertEl.style.backgroundColor = color;
      alertEl.style.display = "block";
    }

    function closeAlert(alertContainer) {
      alertContainer.style.display = "none";
    }
  </script>

  </html>

<?php
}

handleLogin();
?>