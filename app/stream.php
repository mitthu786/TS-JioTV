<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

// Retrieve and sanitize input parameters
$id = isset($_REQUEST["id"]) ? htmlspecialchars($_REQUEST["id"]) : '';
$cid = isset($_REQUEST["cid"]) ? htmlspecialchars($_REQUEST["cid"]) : '';
$cooks = isset($_REQUEST["ck"]) ? htmlspecialchars($_REQUEST["ck"]) : '';

if ($cid !== '' && $cooks !== '') {

    $chs = explode('-', $id);

    // Prepare headers
    $headers = [
        'Cookie: ' . hex2bin($cooks),
        'Content-Type: application/x-www-form-urlencoded',
        'User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7',
    ];

    // Fetch data
    $url = "https://jiotvmblive.cdn.jio.com/bpk-tv/{$chs[0]}/Fallback/{$id}";
    $hs = cUrlGetData($url, $headers);
    $cooKee = get_and_refresh_cookie($url, $headers);

    $search = [
        ',URI="https://tv.media.jio.com/fallback/bpk-tv/',
        "{$chs[0]}-"
    ];

    if ($PROXY) {
        $replace = [
            ',URI="auth.php?ck=' . $cooKee . '&pkey=',
            "auth.php?ck=$cooKee&ts=bpk-tv/{$chs[0]}/Fallback/{$chs[0]}-"
        ];
    } else {
        $cookies_1 = hex2bin($cooKee);
        $search[] = ".ts";
        $replace = [
            ',URI="auth.php?ck=' . $cooKee . '&pkey=',
            "https://jiotvmblive.cdn.jio.com/bpk-tv/{$chs[0]}/Fallback/{$chs[0]}-",
            ".ts?{$cookies_1}"
        ];
    }

    // Perform search and replace in the response
    $hs = str_replace($search, $replace, $hs);
    echo $hs;
} else {
    // Handle missing parameters
    http_response_code(400);
    echo "Missing required parameters.";
}
