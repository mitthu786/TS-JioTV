<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$access_token = $jio_cred['authToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$cookies = @$_REQUEST["ck"];
$cookies = hex2bin($cookies);
$headers = jio_headers($cookies, $access_token, $crm, $device_id, $ssoToken, $uniqueId);

if (!empty($_REQUEST["key"]) && !empty($_REQUEST["ck"])) {

    $url = 'https://tv.media.jio.com/streams_live/' . $_REQUEST["key"];
    $haystack = cUrlGetData($url, $headers);
    echo $haystack;
}

if (!empty($_REQUEST["pkey"]) && !empty($_REQUEST["ck"])) {
    $url = 'https://tv.media.jio.com/fallback/bpk-tv/' . $_REQUEST["pkey"];
    $haystack = cUrlGetData($url, $headers);
    echo $haystack;
}

if (!empty($_REQUEST["ts"]) && !empty($_REQUEST["ck"])) {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $url = 'https://jiotvbpkmob.cdn.jio.com/' . $_REQUEST["ts"];
    $haystack = cUrlGetData($url, $headers);
    echo $haystack;
}
