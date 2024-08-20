<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");


include "cpfunctions.php";
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];

$cid = @$_REQUEST["cid"];
$sid = @$_REQUEST["sid"];
$cooks = @$_REQUEST["ck"];

$headers = array(
    'Cookie: ' . hex2bin($cooks),
    'authority: jiotvcod.cdn.jio.com',
    'Content-type: application/x-www-form-urlencoded',
    'user-agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7',
);

if (@$_REQUEST["cid"] != "" && @$_REQUEST["ck"] != "") {

    $seqq = explode('?', $cid);
    $seq = explode('=', $seqq[1]);
    $chs = explode('-', $seqq[0]);
    $link = "https://jiotvcod.cdn.jio.com/bpk-tv/" . $chs[0] . '/Catchup_Fallback/' . $seqq[0] . '?vbegin=' . $seq[1] . '&vend=' . $seq[2];
    $hs = cUrlGetData($link, $headers);
    $hs = @str_replace('https://tv.media.jio.com/fallback/bpk-tv/', 'cpauth.php?ck=' . $cooks . '&pkey=', $hs);

    if ($PROXY) {
        $hs = @str_replace($chs[0] . '-', 'cpauth.php?ck=' . $cooks . '&ts=' . $chs[0] . '/Catchup_Fallback/' . $chs[0] . '-', $hs);
    } else {
        $hs = @str_replace($chs[0] . '-', 'https://jiotvcod.cdn.jio.com/bpk-tv/' . $chs[0] . '/Catchup_Fallback/' . $chs[0] . '-', $hs);
        $hs = @str_replace(".ts", ".ts?" . hex2bin($cooks), $hs);
    }
    echo $hs;
}

if (@$_REQUEST["sid"] != "" && @$_REQUEST["ck"] != "") {

    $seq = explode('?', $sid);
    $chs = explode('/', $seq[0]);
    $link = "https://jiotvmbcod.cdn.jio.com/" . $chs[0] . "/" . $chs[1] . "/" . $chs[2] . "?" . $cook;
    $hs = cUrlGetData($link, $headers);
    $hs = @str_replace('https://tv.media.jio.com/streams_catchup/', 'cpauth.php?ck=' . $cooks . '&key=', $hs);

    if ($PROXY) {
        $hs = @str_replace('https://jiotvmbcod.cdn.jio.com/', 'cpauth.php?ck=' . $cooks . '&tss=', $hs);
    } else {
        $hs = @str_replace(".ts", ".ts?" . hex2bin($cooks), $hs);
    }

    echo $hs;
}
