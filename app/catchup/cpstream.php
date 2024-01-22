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

$cook = @str_replace("PLUS", "+", $cooks);
$cook = @str_replace("EQUALS", "=", $cook);
$cook = base64_decode(strrev($cook));


if (@$_REQUEST["cid"] != "" && @$_REQUEST["ck"] != "") {

    $seqq = explode('?', $cid);
    $seq = explode('=', $seqq[1]);
    $chs = explode('-', $seqq[0]);


    $hdr = array(
        'Cookie' => "$cook",
        'authority' => 'jiotvcod.cdn.jio.com',
        'Content-type' => 'application/x-www-form-urlencoded',
        'user-agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
    );

    $opts = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($hdr),
        $hdr
    )]];

    $cx = stream_context_create($opts);

    $hs1 = file_get_contents("https://jiotvcod.cdn.jio.com/bpk-tv/" . $chs[0] . '/Catchup_Fallback/' . $seqq[0] . '?vbegin=' . $seq[1] . '&vend=' . $seq[2], false, $cx);
    $hs1 = @str_replace('https://tv.media.jio.com/fallback/bpk-tv/', 'cpauth.php?ck=' . $cooks . '&pkey=', $hs1);
    $hs1 = @str_replace($chs[0] . '-', 'cpauth.php?ck=' . $cooks . '&ts=' . $chs[0] . '/Catchup_Fallback/' . $chs[0] . '-', $hs1);


    print($hs1);
}



if (@$_REQUEST["sid"] != "" && @$_REQUEST["ck"] != "") {

    $seq = explode('?', $sid);
    $chs = explode('/', $seq[0]);

    $hdr = array(
        'Cookie' => "$cook",
        'Content-type' => 'application/x-www-form-urlencoded',
        'user-agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
    );

    $opts11 = ['http' => ['method' => 'GET', 'header' => array_map(
        function ($h, $v) {
            return "$h: $v";
        },
        array_keys($hdr),
        $hdr
    )]];
    $cx1 = stream_context_create($opts11);
    $link = "https://jiotvmbcod.cdn.jio.com/" . $chs[0] . "/" . $chs[1] . "/" . $chs[2] . "?" . $cook;
    $hs1 = file_get_contents($link, false, $cx1);
    $hs1 = @str_replace('https://tv.media.jio.com/streams_catchup/', 'cpauth.php?ck=' . $cooks . '&key=', $hs1);
    $hs1 = @str_replace('https://jiotvmbcod.cdn.jio.com/', 'cpauth.php?ck=' . $cooks . '&tss=', $hs1);

    print_r($hs1);
}
