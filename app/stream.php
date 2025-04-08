<?php
// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Set common headers
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

// Get and sanitize parameters
$id = htmlspecialchars($_REQUEST['id'] ?? '');
$cid = htmlspecialchars($_REQUEST['cid'] ?? '');
$cooks = htmlspecialchars($_REQUEST['ck'] ?? '');

if (empty($cid) || empty($cooks)) {
    http_response_code(400);
    exit("Missing required parameters");
}

// Process request
$chs = explode('-', $id);
$cookie = hex2bin($cooks);

// Prepare headers
$headers = [
    'Cookie: ' . $cookie,
    'Content-Type: application/x-www-form-urlencoded',
    'User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7'
];

// Fetch data
$url = sprintf("https://jiotvmblive.cdn.jio.com/bpk-tv/%s/Fallback/%s", $chs[0], $id);
$hs = cUrlGetData($url, $headers);
$cuk = get_and_refresh_cookie($url, $headers);

// Prepare replacement arrays
[$search, $replace] = $PROXY
    ? [
        [',URI="https://tv.media.jio.com/fallback/bpk-tv/', $chs[0] . '-', '.ts'],
        [',URI="auth.php?ck=' . $cuk . '&pkey=', "auth.php?ck=$cuk&ts=bpk-tv/{$chs[0]}/Fallback/{$chs[0]}-", '.ts']
    ]
    : [
        [',URI="https://tv.media.jio.com/fallback/bpk-tv/', $chs[0] . '-', '.ts'],
        [
            ',URI="auth.php?ck=' . $cuk . '&pkey=',
            "https://jiotvmblive.cdn.jio.com/bpk-tv/{$chs[0]}/Fallback/{$chs[0]}-",
            ".ts?" . hex2bin($cuk)
        ]
    ];

echo str_replace($search, $replace, $hs);
