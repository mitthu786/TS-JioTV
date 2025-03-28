<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$access_token = $jio_cred['authToken'];
$device_id = $jio_cred['deviceId'];

$ck = $_REQUEST["ck"] ?? '';
$ts = $_REQUEST['ts'] ?? '';
$id = $_REQUEST["id"] ?? '';
$link = $_REQUEST["link"] ?? '';
$data = $_REQUEST['data'] ?? '';

$headers = jio_sony_headers($ck, $id, $crm, $device_id, $access_token, $uniqueId, $ssoToken);

// Common headers for all responses
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Hheaders: Range");
header("Accept-Ranges: bytes");

if ($link && $data) {
    header("Content-Type: application/vnd.apple.mpegurl");

    $content = cUrlGetData("$link/$data", $headers);
    
    $basePath = "cpSonyAuth.php?id=$id&ck=$ck&ts=$link/";

    // Pattern replacements
    $content = str_replace('sonyliv_', "$basePath" . 'sonyliv_', $content);

    if (strpos($content, 'WL/') === false) {
        $content = str_replace('movie_', "$basePath" . 'movie_', $content);
    } else {
        $content = str_replace('WL/', "$basePath" . 'WL/', $content);
    }

    if (strpos($content, 'WL2/') === false) {
        $content = str_replace('movie_', "$basePath" . 'movie_', $content);
    } else {
        $content = str_replace('WL2/', "$basePath" . 'WL2/', $content);
    }

    // Segment path handling
    $trimmedPath = substr($data, 0, strrpos($data, '/'));
    $content = str_replace('segment-', "$basePath$trimmedPath/segment-", $content);

    echo $content;
    exit;
}

if ($ts && $ck) {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");

    echo cUrlGetData($ts, $headers);
    exit;
}
