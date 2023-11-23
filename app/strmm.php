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
$cid = $_REQUEST["cid"] ?? "";

$headers = [
    'Content-type' => 'application/x-www-form-urlencoded',
    'appkey' => 'NzNiMDhlYzQyNjJm',
    'channelId' => $cid,
    'channel_id' => $cid,
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
    'versionCode' => '277'
];

$data = [
    "channelId" => $cid,
    "channel_id" => $cid,
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
    $cookies_xw = explode("&", $cookie[1]);
    $cookies_x = $cookies_xw[2];
} else {
    $cookies_x = $cookie[1];
}

$chs = explode('/', $cookie[0]);

$enc_x = strrev(base64_encode($cookies_x));
$enc_xy = str_replace("+", "PLUS", $enc_x);
$enc_y = str_replace("=", "EQUALS", $enc_xy);

$hdr = [
    'Cookie' => "$cookies_x",
    'Content-type' => 'application/x-www-form-urlencoded',
    'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
];

$data = [
    "channelId" => $id,
    "channel_id" => $id,
    "stream_type" => "Seek",
];
$pdd = http_build_query($data);

$opts = [
    'http' => [
        'method' => 'GET',
        'header' => array_map(
            function ($h, $v) {
                return "$h: $v";
            },
            array_keys($hdr),
            $hdr
        ),
        'content' => $pdd,
    ],
];

$cx = stream_context_create($opts);
$hs = file_get_contents("https://jiotvmblive.cdn.jio.com/bpk-tv/{$chs[4]}/Fallback/$id", false, $cx);
$hs = str_replace(',URI="https://tv.media.jio.com/fallback/bpk-tv/', ',URI="auths.php?ck=' . $enc_y . '&pkey=', $hs);
$hs = str_replace("{$chs[4]}-", "auths.php?ck=$enc_y&ts={$chs[3]}/{$chs[4]}/Fallback/{$chs[4]}-", $hs);
$hs = str_replace("auths.php?ck=$enc_y&ts=keyframes/auths.php?ckk=$enc_y&ts=", "auths.php?ck=$enc_y&ts=keyframes/", $hs);

print($hs);
