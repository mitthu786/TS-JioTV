<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh


error_reporting(0);
$jio_fname = 'TS-JioTV_' . md5(time() . 'fbejnchbieunskjd') . '.m3u';
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: inline; filename=$jio_fname");

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$ip_port = $_SERVER['SERVER_PORT'];
if ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" || 'localhost') {
    $host_jio = $_SERVER['HTTP_HOST'];
} else {
    $host_jio = $local_ip;
}

$jio_path = $protocol . $host_jio  . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$json = json_decode(file_get_contents('https://jiotv.data.cdn.jio.com/apis/v1.3/getMobileChannelList/get/?langId=6&os=android&devicetype=phone&usergroup=tvYR7NSNn7rymo3F&version=277&langId=6'), true);

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
    16 => "French",
    17 => "Other",
    18 => "Other",
    19 => "Other",
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


$jio_data = '#EXTM3U x-tvg-url="https://avkb.short.gy/jioepg.xml.gz"' . PHP_EOL;
foreach ($json['result'] as $channel) {
    $jio_data .= '#EXTINF:-1 tvg-id="' . $channel['channel_id'] . '" tvg-name="' . $channel['channel_name'] . '" tvg-type="' . $GENRE_MAP[$channel['channelCategoryId']] . '" group-title="TS-JioTV ' . $GENRE_MAP[$channel['channelCategoryId']] . '" tvg-language="' . $LANG_MAP[$channel['channelLanguageId']] . '" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/' . $channel['logoUrl'] . '",' . $channel['channel_name'] . PHP_EOL;
    $jio_data .= $jio_path . 'live.php?id=' . $channel['channel_id'] . '&e=.m3u8' . PHP_EOL;
}
print($jio_data);
