<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

$id = @$_REQUEST["id"];
$cid = @$_REQUEST["cid"];
$cooks = @$_REQUEST["ck"];

if (@$_REQUEST["cid"] != "" && @$_REQUEST["ck"] != "") {

    $chs = explode('-', $id);

    $headers = array(
        'Cookie' => hex2bin($cooks),
        'Content-type' => 'application/x-www-form-urlencoded',
        'User-Agent' => 'plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7',
    );

    $hs = cUrlGetData("https://jiotvbpkmob.cdn.jio.com/bpk-tv/{$chs[0]}/Fallback/$id", $headers);

    $search = [
        ',URI="https://tv.media.jio.com/fallback/bpk-tv/',
        "{$chs[0]}-",
        "auth.php?ck=$cooks&ts=keyframes/auth.php?ckk=$cooks&ts=",
    ];

    $replace = [
        ',URI="auth.php?ck=' . $cooks . '&pkey=',
        "auth.php?ck=$cooks&ts=bpk-tv/{$chs[0]}/Fallback/{$chs[0]}-",
        "auth.php?ck=$cooks&ts=keyframes/",
    ];

    $hs = str_replace($search, $replace, $hs);
    echo $hs;
}
