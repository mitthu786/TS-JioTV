<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By : TechieSneh

error_reporting(0);
$data = base64_decode($_REQUEST['data']);
$data = explode('=?=', $data);
$cid = $data[0];
$name = str_replace('_', ' ', $cid);
$id = $data[1];

?>

<html lang="en">

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
    <script src="assets/js/jwplayer.js"></script>
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/BcjC6R8/jiotv.png">
    <script type='text/javascript' src='https://content.jwplatform.com/libraries/IDzF9Zmk.js'></script>

</head>

<body>
    <style>
        .jw-svg-icon-pause {
            background-image: url("assets/img/jwplay/pause.svg");
        }

        [class~=jw-svg-icon-pause] path,
        [class~=jw-svg-icon-replay] path,
        [class~=jw-svg-icon-next] path,
        .jw-state-paused .jw-svg-icon-play path,
        [class~=jw-svg-icon-volume-0] path,
        [class~=jw-svg-icon-buffer] path,
        [class~=jw-svg-icon-volume-100] path,
        [class~=jw-svg-icon-rewind] path {
            display: none;
        }

        html {
            font-family: Poppins;
        }

        [class~=jw-svg-icon-fullscreen-on],
        [class~=jw-svg-icon-fullscreen-off],
        .jw-svg-icon-pause,
        [class~=jw-svg-icon-cc-off],
        .jw-svg-icon-close,
        [class~=jw-state-paused] [class~=jw-svg-icon-play],
        .jw-svg-icon-rewind,
        .jw-svg-icon-volume-100,
        [class~=jw-svg-icon-replay],
        [class~=jw-svg-icon-playlist],
        [class~=jw-svg-icon-buffer],
        [class~=jw-svg-icon-quality-100],
        [class~=jw-svg-icon-volume-0],
        [class~=jw-svg-icon-settings],
        [class~=jw-svg-icon-next] {
            background-size: contain;
        }

        [class~=jw-svg-icon-quality-100],
        [class~=jw-svg-icon-replay],
        .jw-svg-icon-pause,
        .jw-svg-icon-rewind,
        [class~=jw-svg-icon-buffer],
        [class~=jw-svg-icon-fullscreen-on],
        .jw-svg-icon-close,
        [class~=jw-svg-icon-playlist],
        [class~=jw-svg-icon-cc-off],
        .jw-svg-icon-volume-100,
        [class~=jw-state-paused] [class~=jw-svg-icon-play],
        [class~=jw-svg-icon-next],
        [class~=jw-svg-icon-settings],
        [class~=jw-svg-icon-fullscreen-off],
        [class~=jw-svg-icon-volume-0] {
            background-repeat: no-repeat;
        }

        [class~=jw-svg-icon-buffer] {
            background-image: url("assets/img/jwplay/buffer.svg");
        }

        html {
            background: #000;
        }

        [class~=jw-icon-playback]:hover [class~=jw-svg-icon-pause] {
            background-image: url("assets/img/jwplay/pause-hover.svg");
        }

        [class~=jw-icon-replay]:hover [class~=jw-svg-icon-replay] {
            background-image: url("assets/img/jwplay/replay-hover.svg");
        }

        [class~=jw-svg-icon-replay] {
            background-image: url("assets/img/jwplay/replay.svg");
        }

        .jw-svg-icon-rewind {
            background-image: url("assets/img/jwplay/rewind.svg");
        }

        .loading-text span:nth-child(1),
        .loading-text span:nth-child(4),
        [class~=loading-text] span:nth-child(2),
        [class~=loading-text] span:nth-child(3) {
            filter: blur(0px);
        }

        [class~=jw-state-paused] [class~=jw-svg-icon-play] {
            background-image: url("assets/img/jwplay/play.svg");
        }

        .jw-state-paused .jw-icon-playback:hover .jw-svg-icon-play {
            background-image: url("assets/img/jwplay/play-hover.svg");
        }

        [class~=jw-svg-icon-next] {
            background-image: url("assets/img/jwplay/next.svg");
        }

        html {
            margin-left: 0;
        }

        .loading-text span:nth-child(1) {
            animation: blur-text 1.5s 0s infinite linear alternate;
        }

        [class~=jw-icon-rewind]:hover [class~=jw-svg-icon-rewind] {
            background-image: url("assets/img/jwplay/rewind-hover.svg");
        }

        [class~=jw-icon-next]:hover [class~=jw-svg-icon-next] {
            background-image: url("assets/img/jwplay/next-hover.svg");
        }

        .jw-svg-icon-volume-100 {
            background-image: url("assets/img/jwplay/volume-on.svg");
        }

        html {
            margin-bottom: 0;
        }

        .jw-icon-volume:hover .jw-svg-icon-volume-100 {
            background-image: url("assets/img/jwplay/volume-on-hover.svg");
        }

        [class~=jw-svg-icon-volume-0] {
            background-image: url("assets/img/jwplay/volume-off.svg");
        }

        [class~=jw-svg-icon-cc-off] {
            background-image: url("assets/img/jwplay/captions.svg");
        }

        [class~=loading-text] span:nth-child(2) {
            animation: blur-text 1.5s .2s infinite linear alternate;
        }

        [class~=jw-icon-volume]:hover [class~=jw-svg-icon-volume-0] {
            background-image: url("assets/img/jwplay/volume-off-hover.svg");
        }

        html {
            margin-right: 0;
        }

        [class~=jw-svg-icon-playlist] {
            background-image: url("assets/img/jwplay/playlist.svg");
        }

        [class~=loading-text] span:nth-child(3) {
            animation: blur-text 1.5s .4s infinite linear alternate;
        }

        html {
            margin-top: 0;
        }

        [class~=jw-svg-icon-close] path,
        .jw-svg-icon-cc-off path,
        [class~=jw-svg-icon-playlist] path,
        [class~=jw-svg-icon-quality-100] path,
        [class~=jw-svg-icon-fullscreen-off] path,
        [class~=jw-svg-icon-settings] path,
        [class~=jw-svg-icon-fullscreen-on] path {
            display: none;
        }

        [class~=jw-icon-cc-off]:hover [class~=jw-svg-icon-cc-off] {
            background-image: url("assets/img/jwplay/captions-hover.svg");
        }

        [class~=jw-svg-icon-settings] {
            background-image: url("assets/img/jwplay/settings.svg");
        }

        [class~=jw-svg-icon-quality-100] {
            background-image: url("assets/img/jwplay/quality.svg");
        }

        [class~=jw-playlist-btn]:hover [class~=jw-svg-icon-playlist] {
            background-image: url("assets/img/jwplay/playlist-hover.svg");
        }

        #myElement {
            position: reletive;
        }

        .jw-svg-icon-close {
            background-image: url("assets/img/jwplay/close.svg");
        }

        [class~=jw-icon-settings]:hover [class~=jw-svg-icon-settings] {
            background-image: url("assets/img/jwplay/settings-hover.svg");
        }

        [class~=jw-svg-icon-fullscreen-on] {
            background-image: url("assets/img/jwplay/fullscreen-on.svg");
        }

        .jw-settings-quality:hover .jw-svg-icon-quality-100 {
            background-image: url("assets/img/jwplay/quality-hover.svg");
        }

        [class~=jw-settings-close]:hover [class~=jw-svg-icon-close] {
            background-image: url("assets/img/jwplay/close-hover.svg");
        }

        #myElement {
            width: 100% !important;
        }

        html {
            padding-left: 0;
        }

        .loading-text span:nth-child(4) {
            animation: blur-text 1.5s .6s infinite linear alternate;
        }

        [class~=loading-text] span:nth-child(7),
        [class~=loading-text] span:nth-child(6),
        [class~=loading-text] span:nth-child(8),
        [class~=loading-text] span:nth-child(5) {
            filter: blur(0px);
        }

        html {
            padding-bottom: 0;
        }

        #myElement {
            height: 100% !important;
        }

        [class~=loading-text] span {
            display: inline-block;
        }

        [class~=loading-text] span {
            margin-left: .052083333in;
        }

        [class~=loading-text] span:nth-child(5) {
            animation: blur-text 1.5s .8s infinite linear alternate;
        }

        [class~=jw-svg-icon-fullscreen-off] {
            background-image: url("assets/img/jwplay/fullscreen-off.svg");
        }

        [class~=loading-text] span:nth-child(6) {
            animation: blur-text 1.5s 1s infinite linear alternate;
        }

        [class~=loading] {
            position: fixed;
        }

        [class~=loading],
        .loading-text {
            top: 0;
        }

        [class~=loading-text] span {
            margin-bottom: 0;
        }

        html {
            padding-right: 0;
        }

        [class~=jw-icon-fullscreen]:hover [class~=jw-svg-icon-fullscreen-on] {
            background-image: url("assets/img/jwplay/fullscreen-on-hover.svg");
        }

        .loading-text,
        [class~=loading] {
            left: 0;
        }

        [class~=loading],
        .loading-text {
            width: 100%;
        }

        [class~=loading] {
            height: 100%;
        }

        [class~=loading] {
            background: #000;
        }

        [class~=loading] {
            z-index: 9999;
        }

        .loading-text {
            position: absolute;
        }

        [class~=loading-text] span {
            margin-right: .052083333in;
        }

        html {
            padding-top: 0;
        }

        [class~=loading-text] span {
            margin-top: 0;
        }

        [class~=loading-text] span {
            font-size: 37.5pt;
        }

        [class~=loading-text] span {
            font-weight: bold;
        }

        [class~=loading-text] span {
            color: #ffffff;
        }

        .loading-text {
            bottom: 0;
        }

        [class~=loading-text] span {
            font-family: "Quattrocento Sans", sans-serif;
        }

        .loading-text {
            right: 0;
        }

        [class~=jw-icon-fullscreen]:hover [class~=jw-svg-icon-fullscreen-off] {
            background-image: url("assets/img/jwplay/fullscreen-off-hover.svg");
        }

        .loading-text {
            margin-left: auto;
        }

        .loading-text {
            margin-bottom: auto;
        }

        .loading-text {
            margin-right: auto;
        }

        .loading-text {
            margin-top: auto;
        }

        .loading-text {
            text-align: center;
        }

        [class~=loading-text] span:nth-child(7) {
            animation: blur-text 1.5s 1.2s infinite linear alternate;
        }

        .loading-text {
            height: 100px;
        }

        .loading-text {
            line-height: 1.041666667in;
        }

        [class~=loading-text] span:nth-child(8) {
            animation: blur-text 1.5s 1.4s infinite linear alternate;
        }

        @keyframes blur-text {
            0% {
                filter: blur(0px);
            }

            100% {
                filter: blur(4px);
            }
        }

        [class~=scrollbar-track-y] {
            background: #131720 !important;
        }

        [class~=scrollbar-track-y] {
            top: .625pc !important;
        }

        [class~=scrollbar-track-y],
        [class~=scrollbar-track-x] {
            bottom: 10px !important;
        }

        [class~=scrollbar-track-y] {
            height: auto !important;
        }

        [class~=scrollbar-track-y],
        .scrollbar-thumb-y {
            width: 4px !important;
        }

        .scrollbar-thumb-y,
        [class~=scrollbar-track-x],
        [class~=scrollbar-thumb-x],
        [class~=scrollbar-track-y] {
            border-radius: .041666667in !important;
        }

        #videoContainer {
            position: absolute;
        }

        [class~=scrollbar-track-x],
        [class~=scrollbar-track-y] {
            right: 7.5pt !important;
        }

        [class~=scrollbar-track-y],
        [class~=scrollbar-track-x] {
            overflow: hidden;
        }

        .scrollbar-thumb-y {
            background: #000000 !important;
        }

        [class~=scrollbar-track-x] {
            background: #131720 !important;
        }

        [class~=scrollbar-track-x] {
            left: 10px !important;
        }

        [class~=scrollbar-track-x],
        [class~=scrollbar-thumb-x] {
            height: 4px !important;
        }

        [class~=scrollbar-track-x] {
            width: auto !important;
        }

        [class~=scrollbar-thumb-x] {
            background: #000000 !important;
        }

        #videoContainer {
            width: 100% !important;
        }

        #videoContainer {
            height: 100% !important;
        }
    </style>
    <video id="myElement"></video>
    <script type="text/javascript">
        jwplayer.key = 'Khpp2dHxlBJHC8MCmLnBuV2jK/DwDnJMniwF6EO9HC/riJ712ZmbHg==';
    </script>
    <script type="text/JavaScript">
        jwplayer("myElement").setup({
            title: '<?php echo $name; ?>',
            description: "SnehTV",
            image: 'https://jiotv.catchup.cdn.jio.com/dare_images/images/<?php echo $cid; ?>.png',
            aspectratio: '16:9',
            width: '100%',
            mute: false,
            autostart: true,
            file: "live.php?id=<?php echo $id; ?>&e=.m3u8",
            type: "mp4",
            captions: {color: '#ffb800',fontSize: 30,backgroundOpacity: 0},
            sharing: {
                sites: ['facebook','twitter']
            }
        })
    </script>
</body>

</html>