<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

include "functions.php";
$cred = getCRED();
$creds = json_decode($cred, true);
$ssoToken = $creds['ssoToken'];
$crm = $creds['sessionAttributes']['user']['subscriberId'];
$uniqueId = $creds['sessionAttributes']['user']['unique'];

@$id = @$_REQUEST["id"];
@$cid = @$_REQUEST["cid"];

$headers = array(
    'Content-type' => 'application/x-www-form-urlencoded',
    'appkey' => 'NzNiMDhlYzQyNjJm',
    'channelId' => "$cid",
    'channel_id' => "$cid",
    'crmid' => "$crm",
    'deviceId' => 'e4286d7b481d69b8',
    'devicetype' => 'phone',
    'isott' => 'true',
    'languageId' => '6',
    'lbcookie' => '1',
    'os' => 'android',
    'osVersion' => '8.1.0',
    'srno' => '230203144000',
    'ssotoken' => "$ssoToken",
    'subscriberId' => "$crm",
    'uniqueId' => "$uniqueId",
    'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
    'usergroup' => 'tvYR7NSNn7rymo3F',
    'versionCode' => '277'
);

$data = array("channelId" => $cid, "channel_id" => $cid, "stream_type" => "Seek");
$postdata = http_build_query($data);

$opts = ['http' => ['method' => 'POST', 'header' => array_map(
    function ($h, $v) {
        return "$h: $v";
    },
    array_keys($headers),
    $headers
), 'content' => $postdata]];

$context = stream_context_create($opts);
$haystacks = file_get_contents("https://tv.media.jio.com/apis/v2.2/getchannelurl/getchannelurl?langId=6", false, $context);

$haystack = json_decode($haystacks);
$cookie = explode('?', $haystack->result);

if (strstr($cookie[1], "minrate=")) {
    $cookieww = explode("&", $cookie[1]);
    $cookiew = $cookieww[2];
} else {
    $cookiew = $cookie[1];
}

$chs = explode('/', $cookie[0]);
$chsq = explode($chs[4], $cookie[0]);


$encyy = strrev(base64_encode($cookiew));
$encyyy = @str_replace("+", "PLUS", $encyy);
$ency = @str_replace("=", "EQUALS", $encyyy);

$hdr = array(
    'Cookie' => "$cookiew",
    'Content-type' => 'application/x-www-form-urlencoded',
    'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
);

$data = array("channelId" => $id, "channel_id" => $id, "stream_type" => "Seek");
$pdd = http_build_query($data);

$opts = ['http' => ['method' => 'GET', 'header' => array_map(
    function ($h, $v) {
        return "$h: $v";
    },
    array_keys($hdr),
    $hdr
), 'content' => $pdd]];

$cx = stream_context_create($opts);
$hs = file_get_contents("https://jiotvmblive.cdn.jio.com/bpk-tv/" . $chs[4] . '/Fallback/' . $id, false, $cx);
$hs = @str_replace(',URI="https://tv.media.jio.com/fallback/bpk-tv/', ',URI="auths.php?ck=' . $ency . '&pkey=', $hs);
$hs = @str_replace($chs[4] . '-', 'auths.php?ck=' . $ency . '&ts=' . $chs[3] . '/' . $chs[4] . '/Fallback/' . $chs[4] . '-', $hs);
$hs = @str_replace('auths.php?ck=' . $ency . '&ts=keyframes/auths.php?ckk=' . $ency . '&ts=', 'auths.php?ck=' . $ency . '&ts=keyframes/', $hs);
print($hs);
