<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
if (isset($_GET['data'])) {
    $data = hex2bin(explode('_', $_SERVER['REQUEST_URI'])[1]);
    $data = explode('=?=', $data);
    $name = $data[0];
    $name = str_replace("_", " ", $name);
    $id = $data[1];
    $showtime = $data[2];
    $srno = $data[3];
    $result = substr($srno, 0, 6);
    $dates = "20" . substr($result, 0, 2) . "-" . substr($result, 2, 2) . "-" . substr($result, 4, 5);
    $begin = $data[4];
    $end = $data[5];
    $link = "ts_catchup_" . $id . "_" . $srno . '_' . $begin . '_' . $end . '.m3u8';
    $logo = "https://jiotv.catchup.cdn.jio.com/dare_images/shows/" . $dates . "/" . $srno . ".jpg";

    echo <<<GFG
     <html lang="en">

     <head>
         <title>$name | JioTV +</title>
         <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
         <meta name="description" content="ENJOY FREE LIVE TV">
         <meta name="keywords" content="LIVETV, SPORTS, MOVIES, MUSIC">
         <meta name="author" content="TSNEH">
         <meta name="viewport" content="width=device-width,initial-scale=1.0,maximum-scale=1.0,user-scalable=no" />
         <meta http-equiv="X-UA-Compatible" content="IE=edge" />
         <link rel="stylesheet" href="https://cdn.plyr.io/3.6.2/plyr.css" />
         <link rel="stylesheet" href="../assets/css/player.css" />
         <script src="https://cdn.plyr.io/3.6.3/plyr.js"></script>
         <script src="../assets/js/jwplayer.js"></script>
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
                    title: "$name",
                    description: "SnehTV",
                    image: "$logo",
                    skin: {
                        name: "netflix"
                    },
                    aspectratio: '16:9',
                    width: '100%',
                    mute: false,
                    autostart: true,
                    file: "$link",
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
     GFG;
    die();
} else {
    echo "Something Went Wrong";
}
