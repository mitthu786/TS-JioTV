<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];

$cokky = @$_REQUEST["ck"];
$encyy = @str_replace("PLUS", "+", $cokky);
$encyyy = @str_replace("EQUALS", "=", $encyy);
$cokk = base64_decode(strrev($encyyy));

if (@$_REQUEST["key"] != "" && @$_REQUEST["ck"] != "") {

    $seq = explode('?', @$_REQUEST["key"]);

    $headers = array(
        // 'Cookie' => "$cokk",
        'appkey' => 'NzNiMDhlYzQyNjJm',
        'channelid' => '0',
        'crmid' => "$crm",
        'deviceId' => 'e4286d7b481d69b8',
        'devicetype' => 'phone',
        'lbcookie' => '1',
        'os' => 'android',
        'osVersion' => '8.1.0',
        'srno' => '240101144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '331',
        'Accept-Encoding' => 'identity',
        'Host' => 'jiotvmbcod.cdn.jio.com',
        'Connection' => 'Keep-Alive'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];


    $context = stream_context_create($opts);
    $haystack = file_get_contents('https://tv.media.jio.com/streams_catchup/' . $seq[0] . '?' . $cokk, false, $context);
    echo $haystack;
}

if (@$_REQUEST["pkey"] != "" && @$_REQUEST["ck"] != "") {

    $headers = array(
        'Cookie' => "$cokk",
        'appkey' => 'NzNiMDhlYzQyNjJm',
        'channelid' => '0',
        'crmid' => "$crm",
        'deviceId' => 'e4286d7b481d69b8',
        'devicetype' => 'phone',
        'isott' => 'true',
        'languageId' => '6',
        'lbcookie' => '1',
        'os' => 'android',
        'osVersion' => '8.1.0',
        'srno' => '240101144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '331'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];


    $context = stream_context_create($opts);
    $haystack = file_get_contents('https://tv.media.jio.com/fallback/bpk-tv/' . $_REQUEST["pkey"], false, $context);
    echo $haystack;
}

if (@$_REQUEST["ts"] != "" && @$_REQUEST["ck"] != "") {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $headers = array(
        'Cookie' => "$cokk",
        'appkey' => 'NzNiMDhlYzQyNjJm',
        'channelid' => '0',
        'crmid' => "$crm",
        'deviceId' => 'e4286d7b481d69b8',
        'devicetype' => 'phone',
        'isott' => 'true',
        'languageId' => '6',
        'lbcookie' => '1',
        'os' => 'android',
        'osVersion' => '8.1.0',
        'srno' => '240101144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '331'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];

    $context = stream_context_create($opts);
    $haystack = file_get_contents("https://jiotvcod.cdn.jio.com/bpk-tv/" . $_REQUEST["ts"], false, $context);
    echo $haystack;
}
if (@$_REQUEST["tss"] != "" && @$_REQUEST["ck"] != "") {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $tsq = explode('?', @$_REQUEST["tss"]);

    $headers = array(
        'Cookie' => "$cokk",
        'appkey' => 'NzNiMDhlYzQyNjJm',
        'channelid' => '0',
        'crmid' => "$crm",
        'deviceId' => 'e4286d7b481d69b8',
        'devicetype' => 'phone',
        'isott' => 'true',
        'languageId' => '6',
        'lbcookie' => '1',
        'os' => 'android',
        'osVersion' => '8.1.0',
        'srno' => '240101144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '331'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];

    $context = stream_context_create($opts);
    $haystack = file_get_contents("https://jiotvcod.cdn.jio.com/" . $tsq[0], false, $context);
    echo $haystack;
}
