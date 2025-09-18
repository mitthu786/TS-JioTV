<?php
// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Response headers
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

// Server configuration
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? 'https://' : 'http://';
$host_jio = in_array($_SERVER['SERVER_ADDR'], ['127.0.0.1', 'localhost'])
    ? getHostByName(php_uname('n'))
    : $_SERVER['HTTP_HOST'];

if (!str_contains($host_jio, $_SERVER['SERVER_PORT'])) {
    $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

$jio_path = rtrim(
    sprintf('%s%s%s', $protocol, $host_jio, str_replace(' ', '%20', dirname($_SERVER['PHP_SELF']))),
    '/'
);

// Request
$id = htmlspecialchars($_REQUEST['id'] ?? '');
$haystack = getJioTvData($id);

if (empty($haystack->code) || $haystack->code !== 200) {
    refresh_token();
    header("Location: {$_SERVER['REQUEST_URI']}");
    exit;
}

// Parse response
[$baseUrl, $query] = array_pad(explode('?', $haystack->result, 2), 2, '');
$cookies_y = str_contains($query, "minrate=") ? explode("&", $query)[2] : $query;
$cook = bin2hex($cookies_y);
$chs = explode('/', $baseUrl);

// Playback headers
$headers_1 = ["User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7"];

// Case 1: bpk-tv streams
if (str_contains($query, "bpk-tv")) {
    $playlist = cUrlGetData($haystack->result, $headers_1);
    $replacements = [
        'URI="' => "URI=\"stream.php?cid=$id&id=",
        "$chs[4]-video" => "stream.php?cid=$id&id=$chs[4]-video",
        "$chs[4]-audio" => "stream.php?cid=$id&id=$chs[4]-audio",
        'URI="stream.php?cid=' . $id . '&id=stream.php?cid=' . $id . '&id='
        => 'URI="stream.php?cid=' . $id . '&id=',
        'stream.php?cid=' . $id . '&id=keyframes/stream.php?cid=' . $id . '&id='
        => 'stream.php?cid=' . $id . '&id=keyframes/',
        'stream.php?cid=' => "stream.php?ck=$cook&cid="
    ];
    echo str_replace(array_keys($replacements), array_values($replacements), $playlist);
    exit;
}

// Case 2: HLS streams
if (str_contains($query, "/HLS/")) {
    $link    = implode('/', array_slice($chs, 0, 5));
    $link_1  = implode('/', array_slice($chs, 0, 7));
    $data    = explode("_", $chs[5])[0];
    $playlist = cUrlGetData($haystack->result, $headers_1);

    $cook = "__hdnea" . explode("__hdnea", hex2bin($cook))[1];
    $cook = bin2hex($cook);

    $base_url = "s_live.php?id=$id&ck=$cook&link=";
    if (str_contains($playlist, "WL/")) {
        echo str_replace([$data, 'WL/'], ["{$base_url}$link&data=$data", "{$base_url}$link_1&data=WL/"], $playlist);
    } else {
        echo str_replace([$data, 'WL2/'], ["{$base_url}$link&data=$data", "{$base_url}$link_1&data=WL2/"], $playlist);
    }
    exit;
}

// Case 3: fallback stream
echo cUrlGetData("https://snehtv.pages.dev/video/tsjiotv.m3u8", $headers_1);
exit;
