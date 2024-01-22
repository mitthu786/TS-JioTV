<?php

// Copyright 2021-2024 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");


$id = @$_REQUEST["id"];
$cid = @$_REQUEST["cid"];
$cooks = @$_REQUEST["ck"];

if (@$_REQUEST["cid"] != "" && @$_REQUEST["ck"] != "") {

    $cook = @str_replace("PLUS", "+", $cooks);
    $cook = @str_replace("EQUALS", "=", $cook);
    $cook = base64_decode(strrev($cook));
    $chs = explode('-', $id);


    $hdr = [
        'Cookie' => "$cook",
        'Content-type' => 'application/x-www-form-urlencoded',
        'User-Agent' => 'plaYtv/7.0.5 (Linux;Android 8.1.0) ExoPlayerLib/2.11.7',
    ];

    $opts = [
        'http' => [
            'method' => 'GET',
            'header' => array_map(
                function ($h, $v) {
                    return "$h: $v";
                },
                array_keys($hdr),
                $hdr
            ),
        ],
    ];

    $cx = stream_context_create($opts);
    $hs = file_get_contents("https://jiotvmblive.cdn.jio.com/bpk-tv/{$chs[0]}/Fallback/$id", false, $cx);
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
