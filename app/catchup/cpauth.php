<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
include "cpfunctions.php";

// Get credentials
$cred       = getCRED();
$jio_cred   = json_decode($cred, true);
$ssoToken   = $jio_cred['ssoToken'] ?? '';
$crm        = $jio_cred['sessionAttributes']['user']['subscriberId'] ?? '';
$uniqueId   = $jio_cred['sessionAttributes']['user']['unique'] ?? '';
$access_tok = $jio_cred['authToken'] ?? '';
$device_id  = $jio_cred['deviceId'] ?? '';

$ck = $_REQUEST['ck'] ?? '';

// Common headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

if ($ck) {
    $headers = jio_headers($ck, $crm, $device_id, $ssoToken, $uniqueId);

    // Handle key / pkey
    foreach (['key' => 'streams_catchup/', 'pkey' => 'fallback/bpk-tv/'] as $param => $path) {
        if (!empty($_REQUEST[$param])) {
            exit(cUrlGetData("https://tv.media.jio.com/{$path}" . urlencode($_REQUEST[$param]), $headers));
        }
    }

    // Handle ts / tss
    $tsParam = $_REQUEST['ts'] ?? $_REQUEST['tss'] ?? '';
    if ($tsParam) {
        header("Content-Type: video/mp2t");
        header("Connection: keep-alive");

        // Choose correct base URL
        $baseUrl = (strpos($tsParam, '?') !== false)
            ? "https://jiotvmbcod.cdn.jio.com/" . strtok($tsParam, '?')
            : "https://jiotvmbcod.cdn.jio.com/bpk-tv/$tsParam";

        exit(cUrlGetData($baseUrl, $headers));
    }
}
