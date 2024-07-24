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
$ts = @$_REQUEST['ts'];
$id = @$_REQUEST["id"];
$link = @$_REQUEST["link"];
$data = @$_REQUEST['data'];
$headers = jio_sony_headers($ck, $id, $crm, $device_id, $access_token, $uniqueId, $ssoToken);

if (!empty($_REQUEST["link"]) && !empty($_REQUEST["data"])) {
    header("Content-Type: application/vnd.apple.mpegurl");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $new_link = $link . '/' . $data;
    $content = cUrlGetData($new_link, $headers);
    $content = @str_replace('sonyliv_', 'cpSonyAuth.php?id=' . $id . '&ck=' . $ck . '&ts=' . $link . '/sonyliv_', $content);

    if (strpos($content, 'WL/') === false) {
        $content = @str_replace('movie_', 'cpSonyAuth.php?id=' . $id . '&ck=' . $ck . '&ts=' . $link . '/movie_', $content);
    } else {
        $content = @str_replace('WL/', 'cpSonyAuth.php?id=' . $id . '&ck=' . $ck . '&ts=' . $link . '/WL/', $content);
    }

    $lastSlashPos = strrpos($data, '/');
    $trimmedUrl = substr($data, 0, $lastSlashPos);
    $content = @str_replace('segment-', 'cpSonyAuth.php?id=' . $id . '&ck=' . $ck . '&ts=' . $link . '/' . $trimmedUrl . '/segment-', $content);
    echo $content;
}

if (!empty($_REQUEST["ts"]) && !empty($_REQUEST["ck"])) {
    header("Content-Type: video/mp2t");
    header("Connection: keep-alive");
    header("Access-Control-Allow-Origin: *");
    header("Access-Control-Expose-Headers: Content-Length,Content-Range");
    header("Access-Control-Allow-Headers: Range");
    header("Accept-Ranges: bytes");

    $content = cUrlGetData($ts, $headers);
    echo $content;
}
