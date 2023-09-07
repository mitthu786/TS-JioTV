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
@$pqid = @$_REQUEST["pqid"];
@$qid = @$_REQUEST["qid"];

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

$data = array("channelId" => $id, "channel_id" => $id, "stream_type" => "Seek");
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

if (@$_REQUEST["qid"] != "") {

    $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
    $cx = stream_context_create($opts);
    $hs = file_get_contents("https://jiotvmblive.cdn.jio.com/" . $chs[3] . "/" . $qid . "?" . $cookiew, false, $cx);
    $qw1 = explode(".", $qid);
    $qw2 = explode("_", $qw1[0]);
    $qw = end($qw2);
    $hs = @preg_replace("/" . $chs[3] . "_" . $qw . "-([^.]+\.)key/", 'auths.php?key=' . $chs[3] . '/' . $chs[3] . '_' . $qw . '-\1key&ck=' . $ency, $hs);
    $hs = @preg_replace("/" . $chs[3] . "_" . $qw  . "-([^.]+\.)ts/", 'auths.php?ts=' . $chs[3] . '/' . $chs[3] . '_' . $qw . '-\1ts&ck=' . $ency, $hs);
    $hs = str_replace("https://tv.media.jio.com/streams_live/" . $chs[3] . "/", "", $hs);
    print $hs;
} elseif (@$_REQUEST["pqid"] != "") {

    $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
    $cx = stream_context_create($opts);
    $hs = file_get_contents('https://jiotvmblive.cdn.jio.com/' . $chs[3] . '/' . $chs[4] . '/' . $pqid . '?' . $cookiew, false, $cx);
    $hsw = explode('_', $hs);
    $qc = explode('.m3u8', $pqid);
    foreach ($hsw as $new) {
        if (strstr($new, ".ts")) {
            $lived[] = explode('.ts', $new)[0];
        }
    }
    foreach ($lived as $livv) {
        $hs = @str_replace($qc[0] . "_" . $livv . ".ts", 'auths.php?ts='  . $chs[3] . '/' . $chs[4] . '/' . $qc[0] . '_' . $livv . '.ts&ck=' . $ency, $hs);
    }
    $keyy = explode(',URI="', $hs);
    $keyt = explode('.key"', $keyy[1]);
    $keynew = @str_replace("https://tv.media.jio.com/streams_live/", "", $keyt[0]);;
    $hs = @str_replace($keyt[0] . ".key", 'auths.php?key='  . $keynew . '.key&ck=' . $ency, $hs);
    print $hs;
} else {
    echo "SOMETHING WENT WRONG";
}
