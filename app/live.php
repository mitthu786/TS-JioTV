<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$access_token = $jio_cred['authToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$ip_port = $_SERVER['SERVER_PORT'];
if ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" || 'localhost') {
    $host_jio = $_SERVER['HTTP_HOST'];
} else {
    $host_jio = $local_ip;
}

$jio_path = $protocol . $host_jio . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$id = $_REQUEST["id"] ?? "";

$data = [
    "stream_type" => "Seek",
    "channel_id" => $id,
];

$post_data = http_build_query($data);

$headers = array(
    "Host: jiotvapi.media.jio.com",
    "Content-Type: application/x-www-form-urlencoded",
    "appkey: NzNiMDhlYzQyNjJm",
    "channel_id: " . $id,
    "userid: " . $crm,
    "crmid: " . $crm,
    "deviceId: " . $device_id,
    "devicetype: phone",
    "isott: true",
    "languageId: 6",
    "lbcookie: 1",
    "os: android",
    "dm: Xiaomi 22101316UP",
    "osversion: 14",
    "srno: 240303144000",
    "accesstoken: " . $access_token,
    "subscriberid: " . $crm,
    "uniqueId: " . $uniqueId,
    "content-length: " . strlen($post_data),
    "usergroup: tvYR7NSNn7rymo3F",
    "User-Agent: okhttp/4.9.3",
    "versionCode: 331",
);

$haystacks = cUrlGetData("https://jiotvapi.media.jio.com/playback/apis/v1/geturl?langId=6", $headers, $post_data);
$haystack = @json_decode($haystacks);

if ($haystack->code !== 200) {
    refresh_token();
    header('Location: ' . $_SERVER['REQUEST_URI']);
    exit();
} else {

    $headers_1 = [
        "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7",
    ];

    $cookie = explode('?', $haystack->result);

    if (strstr($cookie[1], "minrate=")) {
        $cookies_x = explode("&", $cookie[1]);
        $cookies_y = $cookies_x[2];
    } else {
        $cookies_y = $cookie[1];
    }

    $chs = explode('/', $cookie[0]);
    $cook = bin2hex($cookies_y);

    if (strpos($cookie[1], "bpk-tv") !== false) {
        $hs = cUrlGetData($haystack->result, $headers_1);

        $search = [
            'URI="',
            "$chs[4]-video",
            "$chs[4]-audio",
            'URI="stream.php?cid=' . $id . '&id=stream.php?cid=' . $id . '&id=',
            'stream.php?cid=' . $id . '&id=keyframes/stream.php?cid=' . $id . '&id=',
            'stream.php?cid=',
        ];

        $replace = [
            'URI="stream.php?cid=' . $id . '&id=',
            "stream.php?cid=$id&id=$chs[4]-video",
            "stream.php?cid=$id&id=$chs[4]-audio",
            'URI="stream.php?cid=' . $id . '&id=',
            'stream.php?cid=' . $id . '&id=keyframes/',
            "stream.php?ck=$cook&cid=",
        ];

        $hs = str_replace($search, $replace, $hs);
        echo $hs;
    } elseif (strpos($cookie[1], "/HLS/") !== false) {

        $url = $jio_path . "assets/video/index.m3u8";
        $hs = cUrlGetData($url, $headers_1);
        $hs = str_replace("snehiptv", "assets/video/snehiptv", $hs);

        echo $hs;
    } else {
        http_response_code(404);
        die();
    }
}
