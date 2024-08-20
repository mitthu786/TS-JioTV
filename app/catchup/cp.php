<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$host_jio = ($_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : $local_ip;

if (strpos($host_jio, $_SERVER['SERVER_PORT']) === false) {
  $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

$jio_path = $protocol . $host_jio  . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$data = hex2bin(explode('_', $_SERVER['REQUEST_URI'])[1]);
$data = explode("=?=", $data);

$id = $data[1];
$cid = $data[0];
$cid = str_replace("_", " ", $cid);
$pg = $data[2];

$image = 'https://jiotv.catchup.cdn.jio.com/dare_images/images/' . $cid . '.png';
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>JioTV+ : <?php echo $cid; ?> Catchup</title>
  <meta name="description" content="LIVE JIOTV CATCHUP">
  <meta name="keywords" content="JIOTV, LIVETV, SPORTS, MOVIES, MUSIC">
  <meta name="author" content="TSNEH">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
  <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
  <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
  <link rel="stylesheet" href="assets/css/style.css">

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

  <div>
    <img src="<?php echo $image; ?>" alt="Logo" width="200px" style="margin-left: auto; margin-right: auto; display: block" />
    <h2 id="jtvh1"><?php echo $cid; ?></br>CATCHUP</h1>
  </div>

  <?php

  function get_T_time($startEpoch)
  {
    $startTimestampSeconds = $startEpoch / 1000;
    $dateTime = new DateTime('@' . $startTimestampSeconds);
    $newTime = $dateTime->format('Ymd\THis');
    return $newTime;
  }

  $headers = array(
    'Host' => 'jiotvapi.cdn.jio.com',
    'user-agent' => 'okhttp/4.9.3',
    'Accept-Encoding' => 'gzip'
  );

  $opts = ['http' => ['method' => 'GET', 'header' => array_map(
    function ($h, $v) {
      return "$h: $v";
    },
    array_keys($headers),
    $headers
  )]];
  $context = stream_context_create($opts);

  $haystacks = @file_get_contents("https://jiotvapi.cdn.jio.com/apis/v1.3/getepg/get?offset=$pg&channel_id=$id&langId=6", false, $context);

  if ($haystacks === false) {
    echo 'Error fetching data from external API';
    exit;
  }

  $catchupDataArr = @json_decode(gzdecode($haystacks), true);

  for ($i = 0; $i < count($catchupDataArr['epg']); $i++) {
    $episodePoster = "https://jiotv.catchup.cdn.jio.com/dare_images/shows/" . $catchupDataArr['epg'][$i]['episodePoster'];
    $episodedate = $catchupDataArr['epg'][$i]['episodePoster'];
    $result1 = substr($episodedate, 0, 10);
    $showTime = $catchupDataArr['epg'][$i]['showtime'];
    $endTime = $catchupDataArr['epg'][$i]['endtime'];
    $epiShowTime = str_replace(":", "", $showTime);
    $episode_desc = $catchupDataArr['epg'][$i]['description'];
    $name = $catchupDataArr['epg'][$i]['showname'];
    $name = str_replace("/", " ", $name);
    $name = str_replace(" ", " ", $name);
    $srno = $catchupDataArr['epg'][$i]['srno'];

    $begin = get_T_time($catchupDataArr['epg'][$i]['startEpoch']);
    $end = get_T_time($catchupDataArr['epg'][$i]['endEpoch']);

    $data = $name . '=?=' . $id . '=?=' . $epiShowTime . '=?=' . $srno . '=?=' . $begin . '=?=' . $end;
    $link = $jio_path . 'cplay_' . bin2hex($data);

    echo '<div class="movie_card" id="bright">
            <div class="info_section">
                <div class="movie_header">
                    <img class="locandina" src="' . $episodePoster . '" />
                    <h3>' . $name . '</h3>
                    <span class="minutes">' . $result1 . '</span>
                    </br></br>
                    <h4>' . date("g:i A", strtotime($showTime)) . " - " . date("g:i A", strtotime($endTime)) . '</h4>
                </div>
                <div class="movie_desc">
                    <p class="text">' . $episode_desc . '</p>
                </div>
                <div class="movie_social">
                    <ul>
                        <li>
                            <a href="' . $link . '"><img src="https://i.ibb.co/0t2rrqX/play.png" width="100px"></a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="blur_back bright_back"></div>
        </div>';
  }
  ?>

  <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js'></script>
  <script src='https://cdnjs.cloudflare.com/ajax/libs/color-thief/2.0.1/color-thief.min.js'></script>
  <script src='assets/js/script.js'></script>
</body>

</html>