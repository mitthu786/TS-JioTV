<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

include "functions.php";

function verifyUser($user, $pass)
{
  if (strpos($user, "@") !== false) {
    $user = $user;
  } else {
    $user = "+91" . $user;
  }

  $username = $user;
  $password = $pass;

  $apiKey = "l7xx75e822925f184370b2e25170c5d5820a";
  $headers = array(
    "x-api-key: $apiKey",
    "Content-Type: application/json"
  );

  $payload = array(
    'identifier' => $username,
    'password' => $password,
    'rememberUser' => 'T',
    'upgradeAuth' => 'Y',
    'returnSessionDetails' => 'T',
    'deviceInfo' => array(
      'consumptionDeviceName' => 'SM-G935FD',
      'info' => array(
        'type' => 'android',
        'platform' => array(
          'name' => 'SM-G935FD',
          'version' => '8.0.0'
        ),
        'androidId' => '3c6d6b5702fa09bd'
      )
    )
  );

  $options = array(
    'http' => array(
      'header' => implode("\r\n", $headers),
      'method' => 'POST',
      'content' => json_encode($payload),
    ),
  );

  $context = stream_context_create($options);
  $result = file_get_contents('https://api.jio.com/v3/dip/user/unpw/verify', false, $context);

  return json_decode($result, true);
}


function handleLogin()
{
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $username = filter_var($username, FILTER_SANITIZE_STRING);
    $password = filter_var($password, FILTER_SANITIZE_STRING);

    $response = verifyUser($username, $password);
    $ssoToken = $response["ssoToken"];

    if (!empty($ssoToken)) {
      $u_name = encrypt_data($username, "TS-JIOTV");
      file_put_contents("assets/data/credskey.jtv", $u_name);
      $j_data = encrypt_data(json_encode($response), $u_name);
      file_put_contents("assets/data/creds.jtv", $j_data);
      header("Location: login.php?success");
      exit();
    } else {
      header("Location: login.php?error");
      exit();
    }
  }
}

handleLogin();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png">
  <link rel="stylesheet" href="assets/css/tslogin.css">
  <title>JioTV Login</title>
</head>

<body>
  <div>
    <img src="assets/img/jiotv.png" alt="JioTV Logo" width="100px" height="100px" style="margin-left: auto; margin-right: auto; display: block" />
    <h1>JioTV Login</h1>
    <hr />
    <div class="alert" style="display: none">
      <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
    </div>
    <tab-container>
      <input type="radio" id="tabToggle01" name="tabs" value="1" checked />
      <label class="toggleLabel" for="tabToggle01" checked="checked">Password Login</label>
      <input type="radio" id="tabToggle02" name="tabs" value="2" />
      <label class="toggleLabel" for="tabToggle02">OTP Login</label>
      <tab-content>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="POST">
          <div class="formcontainer">
            <div class="container">
              <input id="username" name="username" type="text" placeholder="Mobile No. without +91 / Email" required />
              <input id="password" name="password" type="password" placeholder="Password" required />
              <input type="hidden" name="web" value="true" />
              <input type="hidden" name="type" value="password" />
            </div>
            <button type="submit" style="display: block; width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; font-size: 14px;">Sign In</button>
            <p style="text-align: center; font-size: small; opacity: 0.5;">
              Forgot Password? <a href="https://bit.ly/3P9msXn" style="text-decoration: none; color: #007BFF;">Reset Password</a>
            </p>
            <p style="text-align: center; font-size: small; opacity: 0.5">
              JioTV [ SNEH-TV ]
            </p>
          </div>
        </form>
      </tab-content>
      <tab-content>
        <form>
          <div class="formcontainer">
            <div class="container">
              <p style="text-align: left; color: #ffffff; font-size: medium; opacity: 0.8;">
                🚫 LOGIN DISABLED
              </p>
            </div>
            <p style="text-align: center; font-size: small; opacity: 0.5;">
              Forgot Password? <a href="https://bit.ly/3P9msXn" style="text-decoration: none; color: #007BFF;">Reset Password</a>
            </p>
            <p style="text-align: center; font-size: small; opacity: 0.5;">
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
      const currentPathname = window.location.pathname.replace("app/login.php", "index.php");
      setTimeout(function() {
        const newURL = currentProtocol + "//" + currentHost + currentPathname;
        window.location.replace(newURL);
      }, 500);
    } else if (params.has("error")) {
      showAlert(alertEl, "Error!", "Wrong username or password. Please try again.", "error", "#f44336");
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