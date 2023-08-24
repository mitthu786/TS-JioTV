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

$headers = array(
    'Content-type' => 'application/x-www-form-urlencoded',
    'appkey' => 'NzNiMDhlYzQyNjJm',
    'channelId' => "$id",
    'channel_id' => "$id",
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

$data = array("channelId" => $id, "channel_id" => $id, "programId" => "230128476000", "showtime" => "null", "srno" => "20230128", "stream_type" => "Seek");
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
$encyy = strrev(base64_encode($cookiew));
$encyyy = @str_replace("+", "PLUS", $encyy);
$ency = @str_replace("=", "EQUALS", $encyyy);

if (strstr($cookie[1], "packagerx") == True) {
    $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
    $cx = stream_context_create($opts);
    $hs = file_get_contents($haystack->result, false, $cx);
    $hs = str_replace("1.m3u8", "getlive.php?id=" . $id . "&pqid=1.m3u8", $hs);
    $hs = str_replace("2.m3u8", "getlive.php?id=" . $id . "&pqid=2.m3u8", $hs);
    $hs = str_replace("3.m3u8", "getlive.php?id=" . $id . "&pqid=3.m3u8", $hs);
    $hs = str_replace("4.m3u8", "getlive.php?id=" . $id . "&pqid=4.m3u8", $hs);
    $hs = str_replace("5.m3u8", "getlive.php?id=" . $id . "&pqid=5.m3u8", $hs);
    $hs = str_replace("6.m3u8", "getlive.php?id=" . $id . "&pqid=6.m3u8", $hs);
    print($hs);
} elseif (strstr($cookie[1], "bpk-tv") == True) {
    $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
    $cx = stream_context_create($opts);
    $hs = file_get_contents($haystack->result, false, $cx);
    $hs = @str_replace('URI="', 'URI="strmm.php?cid=' . $id . '&id=', $hs);
    $hs = @str_replace($chs[4] . '-video', 'strmm.php?cid=' . $id . '&id=' . $chs[4] . '-video', $hs);
    $hs = @str_replace($chs[4] . '-audio', 'strmm.php?cid=' . $id . '&id=' . $chs[4] . '-audio', $hs);
    $hs = @str_replace('URI="strmm.php?cid=' . $id . '&id=strmm.php?cid=' . $id . '&id=', 'URI="strmm.php?cid=' . $id . '&id=', $hs);
    $hs = @str_replace('strmm.php?cid=' . $id . '&id=keyframes/strmm.php?cid=' . $id . '&id=', 'strmm.php?cid=' . $id . '&id=keyframes/', $hs);
    print_r($hs);
} elseif (strstr($cookie[1], "acl=/" . $chs[3] . "/") == True) {
    $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
    $cx = stream_context_create($opts);
    $hs = file_get_contents($haystack->result, false, $cx);
    $hs = @str_replace($chs[3], 'getlive.php?id=' . $id . '&qid='  . $chs[3], $hs);
    print $hs;
} else {
    echo "SOMETHING WENT WRONG";
}
