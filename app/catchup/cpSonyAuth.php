<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

$cred = getCRED();
$jio_cred = json_decode($cred, true);

$ssoToken     = $jio_cred['ssoToken'];
$crm          = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId     = $jio_cred['sessionAttributes']['user']['unique'];
$access_token = $jio_cred['authToken'];
$device_id    = $jio_cred['deviceId'];

$ck   = $_REQUEST["ck"]   ?? '';
$ts   = $_REQUEST['ts']   ?? '';
$id   = $_REQUEST["id"]   ?? '';
$link = $_REQUEST["link"] ?? '';
$data = $_REQUEST['data'] ?? '';

$headers = jio_sony_headers($ck, $id, $crm, $device_id, $access_token, $uniqueId, $ssoToken);

// Common headers for all responses
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

if ($link && $data) {
    header("Content-Type: application/vnd.apple.mpegurl");

    $content  = cUrlGetData("$link/$data", $headers);
    $basePath = "cpSonyAuth.php?id=$id&ck=$ck&ts=$link/";

    // Replace playlist paths
    $patterns = [
        // 'sonyliv_' => $basePath . 'sonyliv_',
        // 'movie_'   => $basePath . 'movie_',
        'WL/'      => $basePath . 'WL/',
        'WL2/'     => $basePath . 'WL2/'
    ];

    foreach ($patterns as $search => $replace) {
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
        }
    }

    // Handle segment paths
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
