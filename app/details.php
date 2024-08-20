<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
$data = hex2bin(explode('_', $_SERVER['REQUEST_URI'])[1]);
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

$file_path = 'assets/data/credskey.jtv';
$file_exists = file_exists($file_path);
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
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/css/details.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
  <script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
  <script type="text/javascript" src="https://content.jwplatform.com/libraries/IDzF9Zmk.js"></script>
</head>

<body>
  <header>
    <div id="jtvh1">
      <img src="https://i.ibb.co/BcjC6R8/jiotv.png" alt="JIOTV+">
    </div>
    <div id="userButtons">
      <button class="Btns" id="homeButton">Home</button>
      <button class="Btns" id="refreshButton">Refresh</button>
      <button class="Btns" id="logoutButton">Logout</button>
    </div>
  </header>
  </br>
  <div style="text-align: center;">
    <img src="<?php echo $image; ?>" alt="Logo" width="200px" style="margin-left: auto; margin-right: auto; display: block" />
    <?php
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = bin2hex($cp_link_live);
    echo <<<GFG
    <h2 id="jtvh1"> $name </h2>
    <a href="$jio_path/play_$cp_link_live" class="btn btn-danger">LIVE</a>
    GFG;
    ?>
    <hr width="50%" color="red">
  </div></br>
  <?php if ($c == "y") {
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = bin2hex($cp_link_live);
    echo <<<GFG
        <div class="card-container">
    GFG;
    for ($i = 0; $i >= -7; $i--) {
      $cp_link = $cid . '=?=' . $id . '=?=' . $i;
      $cp_link = bin2hex($cp_link);

      $currentDate = new DateTime('now', new DateTimeZone('Asia/Kolkata'));
      $prevDate = clone $currentDate;
      $prevDate->modify("$i day");

      $day = $prevDate->format('d');
      $month = $prevDate->format('F');
      $year = $prevDate->format('Y');
      $weekName = $prevDate->format('l');

      echo <<<GFG
      <div class="card">
        <div id="DateCard">
          <div id="day">$day</div>
          <div class="layer2">
            <span id="day_name">$weekName</span>
            <span id="month_name">$month</span>
            <span id="year">$year</span>          
          </div>
        </div>
        <a href="$jio_path/catchup/cp_$cp_link" class="btn btn-primary">WATCH</a>
      </div>
      GFG;
    }
    echo <<<GFG
  </div>
  GFG;
  }
  if ($c == "n") {
    $cp_link_live = $cid . '=?=' . $id;
    $cp_link_live = bin2hex($cp_link_live);
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

  <?php if (!$file_exists) : ?>
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="LoginModal" aria-hidden="true">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="LoginModal">🔐 TS-JioTV : Login Portal</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            Log in to enjoy seamless, uninterrupted access to all our live TV channels and premium content.
          </div>
          <div class="modal-footer">
            <a href="login" class="btn btn-primary">Login</a>
            <button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
  <?php endif; ?>

  <script src="assets/js/details.js"></script>
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js"></script>

  <?php if (!$file_exists) : ?>
    <script>
      $(document).ready(function() {
        $('#myModal').modal('show');
      });
    </script>
  <?php endif; ?>
</body>

</html>