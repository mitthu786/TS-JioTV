<?php

// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By : TechieSneh

error_reporting(0);
$data = hex2bin(explode('_', $_SERVER['REQUEST_URI'])[1]);
$data = explode('=?=', $data);
$cid = $data[0];
$name = str_replace('_', ' ', $cid);
$id = $data[1];

?>

<html lang="en">

<head>
    <title><?= $name; ?> | JioTV +</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="description" content="ENJOY FREE LIVE TV">
    <meta name="keywords" content="LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="author" content="TSNEH">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
    <link rel="stylesheet" href="assets/css/player.css" />
    <script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
    <script src="assets/js/jwplayer.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
    <!-- <script type='text/javascript' src='https://content.jwplatform.com/libraries/IDzF9Zmk.js'></script> -->

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
                title: '<?= $name; ?>',
                description: "SnehTV",
                image: 'https://jiotv.catchup.cdn.jio.com/dare_images/images/<?= $cid; ?>.png',
                skin: {
                    name: "netflix"
                },
                aspectratio: '16:9',
                width: '100%',
                mute: false,
                autostart: true,
                file: "ts_live_<?= $id; ?>.m3u8",
                type: "mp4",
                captions: {color: '#fff',fontSize: 16,backgroundOpacity: 0}
            });

            player.on('error', (e) => {
                console.error('Player error:', e);
            });
        });
    </script>
</body>

</html>