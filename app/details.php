<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
$data = $_REQUEST["data"];
$data = base64_decode($data);
$data = explode('=?=', $data);
$cid = $data[0];
$id = $data[1];
$name = strtoupper(str_replace('_', ' ', $cid));
$image = 'https://jiotv.catchup.cdn.jio.com/dare_images/images/' . $cid . '.png';
$c = $data[2];

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$ip_port = $_SERVER['SERVER_PORT'];
if ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" || 'localhost') {
  $host_jio = $_SERVER['HTTP_HOST'];
} else {
  $host_jio = $local_ip;
}

$jio_path = $protocol . $host_jio  . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));
$jio_path = substr($jio_path, 0, -1);


?>
<html>

<head>
  <title><?php echo $name; ?> | JioTV +</title>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="description" content="ENJOY FREE LIVE TV">
  <meta name="keywords" content="LIVETV, SPORTS, MOVIES, MUSIC">
  <meta name="author" content="TSNEH">
  <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
  <script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
  <script type='text/javascript' src='https://content.jwplatform.com/libraries/IDzF9Zmk.js'></script>

  <style>
    @import url("https://cdn.jsdelivr.net/npm/@fontsource/holtwood-one-sc@4.5.1/index.min.css");
    @import url("https://fonts.googleapis.com/css?family=Montserrat:300,400,700,800");

    #jtvh1 {
      text-align: center;
      font-size: 25px;
      margin: 15px;
      padding-bottom: 10px;
      font-family: "Holtwood One SC", serif;
      background-color: rgb(255, 255, 255);
      background-image: rgb(255, 255, 255);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
    }

    #jtvh1 img {
      width: 80px;
    }

    header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 10px 20px;
      color: #fff;
    }

    @media (max-width: 768px) {
      header {
        flex-direction: row;
        align-items: center;
        text-align: center;
      }

      #jtvh1 {
        margin-bottom: 10px;
      }

      #jtvh1 img {
        width: 45px;
      }

      #userButtons {
        flex-direction: row;
        justify-content: center;
      }

      #userButtons button {
        font-size: 12px;
      }
    }

    /* LOGIN BUTTON CSS */
    #userButtons {
      font-size: 14px;
      font-family: fantasy;
      text-align-last: center;
    }

    #homeButton {
      border-radius: 10px;
      border: 2px solid #f0f0f0;
    }

    #homeButton:hover {
      background-color: #ffc107;
      border: 3px solid #ffc107;
    }

    #refreshButton {
      border-radius: 10px;
      border: 2px solid #f0f0f0;
    }

    #refreshButton:hover {
      background-color: #00bdc7;
      border: 3px solid #00bdc7;
    }

    #logoutButton {
      border-radius: 10px;
      border: 2px solid #f0f0f0;
    }

    #logoutButton:hover {
      background-color: #ff5252;
      border: 3px solid #ff5252;
    }

    body {
      background-color: #1d232a;
      /* color: #fff; */
      font-family: sans-serif;
      font-size: 16px;
      margin: 0;
      padding: 0;
    }

    /* Cards */
    .card-container {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      align-items: center;
      gap: 20px;
    }

    .red_card {
      width: 250px;
      height: 250px;
      background-color: #ff000000;
      border-radius: 10px;
      padding: 20px;
      box-sizing: border-box;
      text-align: center;
    }

    .card {
      width: 250px;
      height: 250px;
      background-color: #f2f2f20d;
      border-radius: 10px;
      padding: 20px;
      box-sizing: border-box;
      text-align: center;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .card {
        width: 150px;
        height: 150px;
      }
    }
  </style>
</head>

<body>
  <header>
    <div id="jtvh1">
      <img src="https://i.ibb.co/BcjC6R8/jiotv.png" alt="JIOTV+">
    </div>
    <div id="userButtons">
      <button id="homeButton">Home</button>
      <button id="refreshButton">Refresh</button>
      <button id="logoutButton">Logout</button>
    </div>
  </header>
  </br>
  <div style="text-align: center;">
    <img src="<?php echo $image; ?>" alt="Logo" width="100px" style="margin-left: auto; margin-right: auto; display: block" />
    <?php
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = base64_encode($cp_link_live);
    echo <<<GFG
    <h2 id="jtvh1"> $name </h2>
    <a href="$jio_path/play.php?data=$cp_link_live" class="btn btn-danger">LIVE</a>
    GFG;
    ?>
    <hr width="50%" color="red">
  </div></br>
  <?php if ($c == "y") {
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = base64_encode($cp_link_live);
    echo <<<GFG
        <div class="card-container">
    GFG;
    for ($i = 0; $i >= -7; $i--) {
      $cp_link = $cid . '=?=' . $id . '=?=' . $i;
      $cp_link = base64_encode($cp_link);

      $currentDate = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
      $prevDate = clone $currentDate;
      $prevDate->modify("$i day");

      echo <<<GFG
      <div class="card">
        <img class="card-img-top" src="$image" alt="logo" width="20px">
        <a href="$jio_path/catchup/cp.php?data=$cp_link" class="btn btn-primary">{$prevDate->format('d-m-Y')}</a>
      </div>
      GFG;
    }
    echo <<<GFG
  </div>
  GFG;
  }
  if ($c == "n") {
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = base64_encode($cp_link_live);
    echo <<<GFG
  <div class="card-container">
    <div class="red_card">
      <h2 id="jtvh1"> OOPS !! CATCHUP NOT AVAILABLE </h2>
    </div>
  </div>
  GFG;
  }
  ?>
  </br>
  <script src="assets/js/details.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>
</body>

</html>