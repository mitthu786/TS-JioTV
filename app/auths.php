<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "functions.php";
$cred = getCRED();
$creds = json_decode($cred, true);
$ssoToken = $creds['ssoToken'];
$crm = $creds['sessionAttributes']['user']['subscriberId'];
$uniqueId = $creds['sessionAttributes']['user']['unique'];

$cokky = @$_REQUEST["ck"];
$encyy = @str_replace("PLUS", "+", $cokky);
$encyyy = @str_replace("EQUALS", "=", $encyy);
$cokk = base64_decode(strrev($encyyy));

if (@$_REQUEST["key"] != "" && @$_REQUEST["ck"] != "") {
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
        'srno' => '230203144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '277'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];
    $cache = str_replace("/", "_", $_REQUEST["key"]);
    if (!file_exists($cache)) {
        $context = stream_context_create($opts);
        $haystack = file_get_contents('https://tv.media.jio.com/streams_live/' . $_REQUEST["key"], false, $context);
    } else {
        $haystack = file_get_contents($cache);
    }
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
        'srno' => '230203144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '277'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];
    $cache = str_replace("/", "_", $_REQUEST["pkey"]);
    if (!file_exists($cache)) {
        $context = stream_context_create($opts);
        $haystack = file_get_contents('https://tv.media.jio.com/fallback/bpk-tv/' . $_REQUEST["pkey"], false, $context);
    } else {
        $haystack = file_get_contents($cache);
    }
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
        'srno' => '230203144000',
        'ssotoken' => "$ssoToken",
        'subscriberId' => "$crm",
        'uniqueId' => "$uniqueId",
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
        'usergroup' => 'tvYR7NSNn7rymo3F',
        'versionCode' => '277'
    );
    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($headers),
        $headers
    ),]];

    $context = stream_context_create($opts);
    $haystack = file_get_contents("https://jiotvmblive.cdn.jio.com/" . $_REQUEST["ts"], false, $context);
    echo $haystack;
}
