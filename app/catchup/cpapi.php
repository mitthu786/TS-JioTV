<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");


include "cpfunctions.php";
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$access_token = $jio_cred['authToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$st = $_REQUEST['st'];
$pid = $_REQUEST['pid'];
$id = $_REQUEST['id'];
$result = substr($pid, 0, 6);
$begin = $_REQUEST['begin'];
$end = $_REQUEST['end'];

$data = [
    'stream_type' => 'Catchup',
    'channel_id' => $id,
    'programId' => "$pid",
    'showtime' => "$st",
    'srno' => "20$result",
    'begin' => "$begin",
    'end' => "$end",
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
    header("Location: ../login/refreshLogin.php");
    exit();
} else {
    $haystack = json_decode($haystacks);
    $cookie = explode('?', $haystack->result);
    $chs = explode('/', $cookie[0]);

    if (strstr($cookie[1], "bpk-tv")) {
        $cookie = explode("&_", $cookie[1]);
        $cookie = '_' . $cookie[1];
    } elseif (strstr($cookie[1], "/HLS/")) {
        $cookie = $cookie[1];
        $cookie = explode("&_", $cookie);
        $cookie = '_' . $cookie[1];
    } else {
        $cookie = $cookie[1];
    }

    $cook = $cookie;
    $cook = strrev(base64_encode($cook));
    $cook = @str_replace("+", "PLUS", $cook);
    $cook =  @str_replace("=", "EQUALS", $cook);

    if (strstr($cookie, "bpk-tv") == True) {

        $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);
        $hs = @str_replace('URI="', 'URI="cpstream.php?cid=', $hs);
        $hs = @str_replace($chs[4] . '-video', 'cpstream.php?cid=' . $chs[4] . '-video', $hs);
        $hs = @str_replace($chs[4] . '-audio', 'cpstream.php?cid=' . $chs[4] . '-audio', $hs);
        $hs = @str_replace('URI="cpstream.php?cid=cpstream.php?cid=', 'URI="cpstream.php?cid=', $hs);
        $hs = @str_replace('cpstream.php?cid=keyframes/cpstream.php?cid=', 'cpstream.php?cid=keyframes/', $hs);
        $hs = @str_replace('?vbegin=', '?vb=', $hs);
        $hs = @str_replace('&vend=', '=', $hs);
        $hs = @str_replace('cpstream.php?cid=', 'cpstream.php?ck=' . $cook . '&cid=', $hs);

        print_r($hs);
    } elseif (strstr($cookie, "/HLS/") == True) {

        $link = $chs[0] . '/' . $chs[1] . '/' . $chs[2] . '/' . $chs[3] . '/' . $chs[4];
        $data = explode("_", $chs[5]);
        $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);
        $hs = @str_replace($data[0], 'cpSonyAuth.php?id=' . $id . '&ck=' . $cook . '&link=' . $link . '&data=' . $data[0], $hs);

        print $hs;
    } elseif (strstr($cookie, "acl=/" . $chs[3] . "/") == True) {

        $opts = ["http" => ["method" => "GET", "header" => "User-Agent: plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7"]];
        $cx = stream_context_create($opts);
        $hs = file_get_contents($haystack->result, false, $cx);
        $hs = @str_replace('https://jiotvmbcod.cdn.jio.com/', 'cpstream.php?ck=' . $cook . '&sid=', $hs);

        print $hs;
    } else {
        echo "SOMETHING WENT WRONG";
    }
}
