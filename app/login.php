<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $u = $_POST['username'];

  if (strpos($u, "@") !== false) {
    $user = $u;
  } else {
    $user = "+91" . $u;
  }

  $pass = $_POST['password'];
}

$headers = array(
  "x-api-key: l7xx75e822925f184370b2e25170c5d5820a",
  "Content-Type: application/json"
);

$username = $user;
$password = $pass;

$payload = array(
  'identifier' => "$username",
  'password' => "$password",
  'rememberUser' => 'T',
  'upgradeAuth' => 'Y',
  'returnSessionDetails' => 'T',
  'deviceInfo' => array(
    'consumptionDeviceName' => 'Jio',
    'info' => array(
      'type' => 'android',
      'platform' => array(
        'name' => 'vbox86p',
        'version' => '8.0.0'
      ),
      'androidId' => '6fcadeb7b4b10d77'
    )
  )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.jio.com/v3/dip/user/unpw/verify');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
$result = curl_exec($ch);
curl_close($ch);

$j = json_decode($result, true);

$k = $j["ssoToken"];
if ($k != "") {
  file_put_contents("assets/data/creds.json", $result);
  header("Location: login.php?success");
} else {
  $msg = "WRONG USER-ID OR PASS ! PLEASE TRY AGAIN";
}

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
    <img src="https://jiotv.com/src/resources/images/JioTV_logo.png" alt="JioTV Logo" width="100px" height="100px" style="margin-left: auto; margin-right: auto; display: block" />
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
        <form action="<?php $_PHP_SELF ?>" method="POST">
          <div class="formcontainer">
            <div class="container">
              <input id="username" name="username" type="text" placeholder="Mobile Number / Username" required />
              <input id="password" name="password" type="password" placeholder="Password" required />
              <input type="hidden" name="web" value="true" />
              <input type="hidden" name="type" value="password" />
            </div>
            <button type="submit" style="display: block; width: 100%; padding: 10px; background-color: #007BFF; color: white; border: none; border-radius: 5px; font-size: 14px;">Sign In</button>
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
              <p style="text-align: center; color: #ffffff; font-size: medium; opacity: 0.8;">
                1. Click <a href="http://jiologin.unaux.com/otp.php" style="text-decoration: none; color: #007BFF;">Login BY OTP</a>.<br>
                2. Enter Your Jio Mobile Number without +91.<br>
                3. Then Enter your OTP received on you given number.<br>
                4. After successfully login you get Download button.<br>
                5. Then create a file "creds.json" under app->data folder.<br>
                6. Now Click on Download button<br>
                7. Then Copy and paste its content in the "creds.json" file.<br>
                8. Now you can use JioTV.
              </p>
            </div>
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
  document.body.onload = () => {
    var params = new URLSearchParams(window.location.search);
    alertEl = document.querySelector(".alert");
    if (params.has("success")) {
      alertEl.style.display = "block";
      alertEl.style.backgroundColor = "#4CAF50";
      alertEl.innerHTML +=
        "<strong>Success!</strong> You have been logged in";
      const currentProtocol = window.location.protocol;
      const currentHost = window.location.host;
      const currentPathname = window.location.pathname.replace("app/login.php", "index.php");
      setTimeout(function() {
        const newURL = currentProtocol + "//" + currentHost + currentPathname;
        window.location.replace(newURL);
      }, 1000);
    } else {
      alertEl.style.display = "block";
      alertEl.style.backgroundColor = "#f44336";
      alertEl.innerHTML += "<strong>Error!</strong> Wrong username or password. Please try again.";
    }
  };
</script>

</html>