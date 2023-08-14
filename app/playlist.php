<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$host = $protocol . $_SERVER['HTTP_HOST'];
$filePath = $_SERVER['PHP_SELF'];
$fullURL =  $host . "/" . explode("/", $filePath)[1];

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
echo '#EXTM3U x-tvg-url="https://avkb.short.gy/jioepg.xml.gz"' . PHP_EOL;
echo "<br>" . PHP_EOL;
$json = json_decode(file_get_contents('https://jiotvapi.cdn.jio.com/apis/v1.4/getMobileChannelList/get/?langId=6&os=android&devicetype=phone&usergroup=tvYR7NSNn7rymo3F&version=277&langId=6'), true);

$LANG_MAP = array(
    1 => "Hindi",
    2 => "Marathi",
    3 => "Punjabi",
    4 => "Urdu",
    5 => "Bengali",
    6 => "English",
    7 => "Malayalam",
    8 => "Tamil",
    9 => "Gujarati",
    10 => "Odia",
    11 => "Telugu",
    12 => "Bhojpuri",
    13 => "Kannada",
    14 => "Assamese",
    15 => "Nepali",
    16 => "French"
);
$GENRE_MAP = array(
    5 => "Entertainment",
    6 => "Movies",
    7 => "Kids",
    8 => "Sports",
    9 => "Lifestyle",
    10 => "Infotainment",
    12 => "News",
    13 => "Music",
    15 => "Devotional",
    16 => "Business",
    17 => "Educational",
    18 => "Shopping",
    19 => "JioDarshan"
);
foreach ($json['result'] as $channel) {
    $target = $channel['logoUrl'];
    $targetnew = trim($target, ".png");
    printf("#EXTINF:-1 tvg-id=\"%u\" group-title=\"%s\" tvg-language=\"%s\" tvg-logo=\"http://jiotv.catchup.cdn.jio.com/dare_images/images/%s\",%s" . PHP_EOL, $channel['channel_id'], $GENRE_MAP[$channel['channelCategoryId']], $LANG_MAP[$channel['channelLanguageId']], $channel['logoUrl'], $channel['channel_name']);
    echo "<br>" . PHP_EOL;
    printf("%s/app/live.php?id=%s&e=.m3u8" . PHP_EOL . PHP_EOL, $fullURL, $channel['channel_id']);
    echo "<br>" . PHP_EOL;
}
