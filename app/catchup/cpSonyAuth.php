<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$access_token = $jio_cred['authToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$cookie = @$_REQUEST["ck"];
$ts = @$_REQUEST['ts'];

$id = @$_REQUEST["id"];
$link = @$_REQUEST["link"];
$data = @$_REQUEST['data'];

$cook = @str_replace("PLUS", "+", $cookie);
$cook = @str_replace("EQUALS", "=", $cook);
$cook = base64_decode(strrev($cook));

if (@$_REQUEST["link"] != "" && @$_REQUEST["data"] != "") {
    header("Content-Type: application/vnd.apple.mpegurl");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $headers = [
        'cookie: ' . $cook,
        'channelid: ' . $id,
        'userid: ' . $crm,
        'crmid: ' . $crm,
        'deviceId: ' . $device_id,
        'devicetype: phone',
        'x-platform: android',
        'srno: 240106144000',
        'accesstoken: ' . $access_token,
        'subscriberId: ' . $crm,
        'uniqueId: ' . $uniqueId,
        'ssotoken: ' . $ssoToken,
        'usergroup: tvYR7NSNn7rymo3F',
        'User-Agent: plaYtv/7.1.3 (Linux;Android 13) ExoPlayerLib/2.11.7',
        'versionCode: 331',
    ];


    $opts = ['http' => ['method' => 'GET', 'header' => implode("\r\n", $headers)]];
    $cx1 = stream_context_create($opts);

    $hs1 = file_get_contents($link . '/' . $data, false, $cx1);
    $hs1 = @str_replace('sonyliv_', 'cpSonyAuth.php?id=' . $id . '&ck=' . $cookie . '&ts=' . $link . '/sonyliv_', $hs1);

    if (strpos($hs1, 'WL/') === false) {
        $hs1 = @str_replace('movie_', 'cpSonyAuth.php?id=' . $id . '&ck=' . $cookie . '&ts=' . $link . '/movie_', $hs1);
    } else {
        $hs1 = @str_replace('WL/', 'cpSonyAuth.php?id=' . $id . '&ck=' . $cookie . '&ts=' . $link . '/WL/', $hs1);
    }

    print($hs1);
}


if (@$_REQUEST["ts"] != "" && @$_REQUEST["ck"] != "") {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $headers = [
        'cookie: ' . $cook,
        'channelid: ' . $id,
        'userid: ' . $crm,
        'crmid: ' . $crm,
        'deviceId: ' . $device_id,
        'devicetype: phone',
        'x-platform: android',
        'srno: 240106144000',
        'accesstoken: ' . $access_token,
        'subscriberId: ' . $crm,
        'uniqueId: ' . $uniqueId,
        'ssotoken: ' . $ssoToken,
        'usergroup: tvYR7NSNn7rymo3F',
        'User-Agent: plaYtv/7.1.3 (Linux;Android 13) ExoPlayerLib/2.11.7',
        'versionCode: 331',
    ];


    $opts = ['http' => ['method' => 'GET', 'header' => implode("\r\n", $headers)]];
    $cx1 = stream_context_create($opts);
    $hs1 = file_get_contents($ts, false, $cx1);

    print($hs1);
}
