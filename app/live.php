<?php
// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Response headers
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

// Server configuration
$protocol = ($_SERVER['HTTPS'] ?? '') === 'on' ? 'https://' : 'http://';
$host_jio = in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', 'localhost'])
    ? getHostByName(php_uname('n'))
    : $_SERVER['HTTP_HOST'];

$host_jio .= str_contains($host_jio, $_SERVER['SERVER_PORT'])
    ? '' : ':' . $_SERVER['SERVER_PORT'];

$jio_path = rtrim(sprintf(
    '%s%s%s',
    $protocol,
    $host_jio,
    str_replace(' ', '%20', dirname($_SERVER['PHP_SELF']))
), '/');

// API request setup
$id = htmlspecialchars($_REQUEST['id'] ?? '');

$haystack = getJioTvData($id);

if (!isset($haystack->code) || $haystack->code !== 200) {
    refresh_token();
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit;
}

// Process response
[$baseUrl, $query] = explode('?', $haystack->result) + ['', ''];
$cookies_y = str_contains($query, "minrate=") ? explode("&", $query)[2] : $query;
$cook = bin2hex($cookies_y);
$chs = explode('/', $baseUrl);

// Response headers
$headers_1 = ["User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7"];

if (str_contains($query, "bpk-tv")) {
    $replacements = [
        'URI="' => "URI=\"stream.php?cid=$id&id=",
        "$chs[4]-video" => "stream.php?cid=$id&id=$chs[4]-video",
        "$chs[4]-audio" => "stream.php?cid=$id&id=$chs[4]-audio",
        'URI="stream.php?cid=' . $id . '&id=stream.php?cid=' . $id . '&id=' => 'URI="stream.php?cid=' . $id . '&id=',
        'stream.php?cid=' . $id . '&id=keyframes/stream.php?cid=' . $id . '&id=' => 'stream.php?cid=' . $id . '&id=keyframes/',
        'stream.php?cid=' => "stream.php?ck=$cook&cid="
    ];

    echo str_replace(
        array_keys($replacements),
        array_values($replacements),
        cUrlGetData($haystack->result, $headers_1)
    );
} elseif (str_contains($query, "/HLS/")) {

    $sonyData = [
        "154" => "1000009248",
        "471" => "1000009248",
        "1396" => "1000009246",
        "291" => "1000009246",
        "1146" => "1000009259",
        "474" => "1000009273",
        "697" => "1000009255",
        "872" => "1000001971",
        "873" => "1000001971",
        "874" => "1000001971",
        "289" => "1000009249",
        "476" => "1000009247",
        "483" => "1000044878",
        "1393" => "1000009253",
    ];

    if (isset($sonyData[$id])) {
        $streamId = $sonyData[$id];
        $url = isApache()
            ? "$jio_path/ts_sony_live_$streamId.m3u8"
            : "$jio_path/s_live.php?id=$streamId&e=.m3u8";
        echo cUrlGetData($url, $headers_1);
    }
} else {

    $url = "https://snehtv.vercel.app/video/tsjiotv.m3u8";
    echo cUrlGetData($url, $headers_1);
}
