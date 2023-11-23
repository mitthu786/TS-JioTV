<?php

// Copyright 2021-2023 SnehTV, Inc.
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
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];

$id = $_REQUEST["id"] ?? "";

$headers = [
    'Content-type' => 'application/x-www-form-urlencoded',
    'appkey' => 'NzNiMDhlYzQyNjJm',
    'channelId' => $id,
    'channel_id' => $id,
    'crmid' => $crm,
    'deviceId' => 'e4286d7b481d69b8',
    'devicetype' => 'phone',
    'isott' => 'true',
    'languageId' => '6',
    'lbcookie' => '1',
    'os' => 'android',
    'osVersion' => '8.1.0',
    'srno' => '230203144000',
    'ssotoken' => $ssoToken,
    'subscriberId' => $crm,
    'uniqueId' => $uniqueId,
    'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
    'usergroup' => 'tvYR7NSNn7rymo3F',
    'versionCode' => '277',
];

$data = [
    "channelId" => $id,
    "channel_id" => $id,
    "programId" => "230128476000",
    "showtime" => "null",
    "srno" => "20230128",
    "stream_type" => "Seek",
];
$post_data = http_build_query($data);

$opts = [
    'http' => [
        'method' => 'POST',
        'header' => array_map(
            function ($h, $v) {
                return "$h: $v";
            },
            array_keys($headers),
            $headers
        ),
        'content' => $post_data,
    ],
];

$context = stream_context_create($opts);
$haystacks = file_get_contents("https://tv.media.jio.com/apis/v2.2/getchannelurl/getchannelurl?langId=6", false, $context);
$haystack = json_decode($haystacks);
$cookie = explode('?', $haystack->result);

if (strstr($cookie[1], "minrate=")) {
    $cookies_x = explode("&", $cookie[1]);
    $cookies_y = $cookies_x[2];
} else {
    $cookies_y = $cookie[1];
}

$chs = explode('/', $cookie[0]);
$enc_x = strrev(base64_encode($cookies_y));
$enc_xy = str_replace("+", "PLUS", $enc_x);
$ency = str_replace("=", "EQUALS", $enc_xy);

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
    $hs = str_replace('URI="', 'URI="strmm.php?cid=' . $id . '&id=', $hs);
    $hs = str_replace("$chs[4]-video", "strmm.php?cid=$id&id=$chs[4]-video", $hs);
    $hs = str_replace("$chs[4]-audio", "strmm.php?cid=$id&id=$chs[4]-audio", $hs);
    $hs = str_replace('URI="strmm.php?cid=' . $id . '&id=strmm.php?cid=' . $id . '&id=', 'URI="strmm.php?cid=' . $id . '&id=', $hs);
    $hs = str_replace('strmm.php?cid=' . $id . '&id=keyframes/strmm.php?cid=' . $id . '&id=', 'strmm.php?cid=' . $id . '&id=keyframes/', $hs);
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
