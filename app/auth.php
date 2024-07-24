<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Fetch credentials
$cred = getCRED();
$jio_cred = json_decode($cred, true);

if (!$jio_cred) {
    die("Invalid credentials");
}

$ssoToken = $jio_cred['ssoToken'] ?? '';
$access_token = $jio_cred['authToken'] ?? '';
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'] ?? '';
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'] ?? '';
$device_id = $jio_cred['deviceId'] ?? '';

$cookies = $_REQUEST["ck"] ?? '';
$cookies = hex2bin($cookies);
$headers = jio_headers($cookies, $access_token, $crm, $device_id, $ssoToken, $uniqueId);

// Function to fetch and echo data
function fetchAndEchoData($url, $headers)
{
    $data = cUrlGetData($url, $headers);
    if ($data === false) {
        http_response_code(500);
        echo "Error fetching data from: $url";
        return false;
    }
    echo $data;
    return true;
}

if (!empty($_REQUEST["key"]) && !empty($cookies)) {
    $url = 'https://tv.media.jio.com/streams_live/' . urlencode($_REQUEST["key"]);
    fetchAndEchoData($url, $headers);
} elseif (!empty($_REQUEST["pkey"]) && !empty($cookies)) {
    $url = 'https://tv.media.jio.com/fallback/bpk-tv/' . urlencode($_REQUEST["pkey"]);
    fetchAndEchoData($url, $headers);
} elseif (!empty($_REQUEST["ts"]) && !empty($cookies)) {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length, Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $url = 'https://jiotvmblive.cdn.jio.com/' . urlencode($_REQUEST["ts"]);
    fetchAndEchoData($url, $headers);
} else {
    http_response_code(400);
    echo "Invalid request parameters.";
}
