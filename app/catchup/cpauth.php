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
$access_token = $jio_cred['authToken'];
$device_id = $jio_cred['deviceId'];
$ck = @$_REQUEST["ck"];

$headers = jio_headers($ck, $crm, $device_id, $ssoToken, $uniqueId);

if (isset($_REQUEST["ck"]) && $_REQUEST["ck"] !== "") {
    if (isset($_REQUEST["key"]) && $_REQUEST["key"] !== "") {
        echo cUrlGetData('https://tv.media.jio.com/streams_catchup/' . urlencode($_REQUEST["key"]), $headers);
    }

    if (isset($_REQUEST["pkey"]) && $_REQUEST["pkey"] !== "") {
        echo cUrlGetData('https://tv.media.jio.com/fallback/bpk-tv/' . urlencode($_REQUEST["pkey"]), $headers);
    }

    if ((isset($_REQUEST["ts"]) && $_REQUEST["ts"] !== "") || (isset($_REQUEST["tss"]) && $_REQUEST["tss"] !== "")) {
        header("Content-Type: video/mp2t");
        header("Connection: keep-alive");
        header("Access-Control-Allow-Origin: *");
        header("Access-Control-Expose-Headers: Content-Length,Content-Range");
        header("Access-Control-Allow-Headers: Range");
        header("Accept-Ranges: bytes");

        $url = isset($_REQUEST["ts"]) ? 'https://jiotvmbcod.cdn.jio.com/bpk-tv/' . $_REQUEST["ts"] : 'https://jiotvmbcod.cdn.jio.com/' . explode('?', $_REQUEST["tss"])[0];
        echo cUrlGetData($url, $headers);
    }
}
