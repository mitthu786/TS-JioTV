<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

$data = null;
$hex = '';

if (isset($_GET['data'])) {
    $hex = $_GET['data'];
} else {
    $parts = explode('_', $_SERVER['REQUEST_URI']);
    $hex = $parts[1] ?? '';
}

// Decode and extract data
if (!empty($hex)) {
    $decoded = hex2bin($hex);
    $data = explode('=?=', $decoded);
}

// Assign variables safely
$name     = isset($data[0]) ? str_replace("_", " ", $data[0]) : '';
$id       = $data[1] ?? '';
$showtime = $data[2] ?? '';
$srno     = $data[3] ?? '';
$begin    = $data[4] ?? '';
$end      = $data[5] ?? '';

// Format date from SRNO
$dates = '';
if (strlen($srno) >= 6) {
    $result = substr($srno, 0, 6);
    $dates = '20' . substr($result, 0, 2) . '-' . substr($result, 2, 2) . '-' . substr($result, 4, 2);
}

// Set Catchup PlayBack URL
$link = (isApache())
    ? "ts_catchup_{$id}_{$srno}_{$begin}_{$end}.m3u8"
    : "cpapi.php?id=$id&srno=$srno&begin=$begin&end=$end&e=.m3u8";

$logo = "https://jiotv.catchup.cdn.jio.com/dare_images/shows/$dates/$srno.jpg";

?>
<html lang="en">

<head>
    <title><?= $name ?> | JioTV +</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="ENJOY FREE LIVE TV">
    <meta name="keywords" content="LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="author" content="TSNEH">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
    <link rel="stylesheet" href="../assets/css/player.css" />
    <script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
    <script type="text/javascript" src="https://content.jwplatform.com/libraries/IDzF9Zmk.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
</head>

<body>
    <style>
        html {
            background: #000;
            margin: 0;
            padding: 0;
        }

        #myElement {
            position: relative;
            width: 100% !important;
            height: 100% !important;
        }

        @keyframes blur-text {
            0% {
                filter: blur(0px);
            }

            100% {
                filter: blur(4px);
            }
        }
    </style>
    <video id="myElement"></video>
    <script type="text/JavaScript">
        document.addEventListener('DOMContentLoaded', () => {
        const player = jwplayer("myElement").setup({
            title: "<?= $name ?>",
            description: "SnehTV",
            image: "<?= $logo ?>",
            skin: {
                name: "netflix"
            },
            aspectratio: '16:9',
            width: '100%',
            mute: false,
            autostart: true,
            file: "<?= $link ?>",
            type: "mp4",
            captions: {color: '#fff',fontSize: 16,backgroundOpacity: 0}
        });
    });
    </script>
</body>

</html>