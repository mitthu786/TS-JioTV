<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

include "functions.php";
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$access_token = $jio_cred['authToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$id = $_REQUEST["id"] ?? "";

$data = [
    "stream_type" => "Seek",
    "channel_id" => $id,
];

$post_data = http_build_query($data);

$headers = [
    "Host: jiotvapi.media.jio.com",
    'Content-Type: application/x-www-form-urlencoded',
    'appkey: NzNiMDhlYzQyNjJm',
    'channel_id: ' . $id,
    'userid: ' . $crm,
    'crmid: ' . $crm,
    'deviceId: ' . $device_id,
    'devicetype: phone',
    'isott: true',
    'languageId: 6',
    'lbcookie: 1',
    'os: android',
    'dm: Xiaomi 22101316UP',
    'osVersion: 13',
    'srno: 240106144000',
    'accesstoken: ' . $access_token,
    'subscriberId: ' . $crm,
    'uniqueId: ' . $uniqueId,
    'content-length: ' . strlen($post_data),
    'usergroup: tvYR7NSNn7rymo3F',
    'User-Agent: okhttp/4.2.2',
    'versionCode: 331',
];

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => implode("\r\n", $headers),
        'content' => $post_data,
    ],
];

$context = stream_context_create($opts);
$haystacks = @file_get_contents("https://jiotvapi.media.jio.com/playback/apis/v1/geturl?langId=6", false, $context);

if ($haystacks === FALSE) {
    header("Location: login/refreshLogin.php");
    exit();
} else {
    $haystack = json_decode($haystacks);
    $cookie = explode('?', $haystack->result);

    if (strstr($cookie[1], "minrate=")) {
        $cookies_x = explode("&", $cookie[1]);
        $cookies_y = $cookies_x[2];
    } else {
        $cookies_y = $cookie[1];
    }

    $chs = explode('/', $cookie[0]);
    $cook = strrev(base64_encode($cookies_y));
    $cook = str_replace("+", "PLUS", $cook);
    $cook = str_replace("=", "EQUALS", $cook);

    if (strpos($cookie[1], "packagerx") !== false) {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7",
            ],
        ];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);
        for ($i = 1; $i <= 6; $i++) {
            $hs = str_replace("$i.m3u8", "getlive.php?id=$id&pqid=$i.m3u8", $hs);
        }
        echo $hs;
    } elseif (strpos($cookie[1], "bpk-tv") !== false) {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7",
            ],
        ];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);

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

        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7",
            ],
        ];

        $cx = stream_context_create($opts);
        $hs = file_get_contents("assets/video/index.m3u8", false, $cx);
        $hs = str_replace("snehiptv", "assets/video/snehiptv", $hs);

        echo $hs;
    } elseif (strpos($cookie[1], "acl=/" . $chs[3] . "/") !== false) {
        $opts = [
            "http" => [
                "method" => "GET",
                "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7",
            ],
        ];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);
        $hs = str_replace($chs[3], "getlive.php?id=$id&qid=$chs[3]", $hs);
        echo $hs;
    } else {
        echo "SOMETHING WENT WRONG";
    }
}
