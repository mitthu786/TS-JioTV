<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

// Get credentials
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$access_token = $jio_cred['authToken'];
$device_id = $jio_cred['deviceId'];
$ck = $_REQUEST['ck'] ?? '';

// Common headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

if (!empty($ck)) {
    $headers = jio_headers($ck, $crm, $device_id, $ssoToken, $uniqueId);

    // Handle key request
    if (!empty($_REQUEST['key'])) {
        echo cUrlGetData('https://tv.media.jio.com/streams_catchup/' . urlencode($_REQUEST['key']), $headers);
        exit;
    }

    // Handle pkey request
    if (!empty($_REQUEST['pkey'])) {
        echo cUrlGetData('https://tv.media.jio.com/fallback/bpk-tv/' . urlencode($_REQUEST['pkey']), $headers);
        exit;
    }

    // Handle ts/tss requests
    $tsParam = $_REQUEST['ts'] ?? $_REQUEST['tss'] ?? '';
    if (!empty($tsParam)) {
        header("Content-Type: video/mp2t");
        header("Connection: keep-alive");

        $baseUrl = strpos($tsParam, '?') !== false
            ? 'https://jiotvmbcod.cdn.jio.com/' . explode('?', $tsParam)[0]
            : 'https://jiotvmbcod.cdn.jio.com/bpk-tv/' . $tsParam;

        echo cUrlGetData($baseUrl, $headers);
        exit;
    }
}
