<?php
// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

error_reporting(0);
header("Content-Type: application/vnd.apple.mpegurl");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length,Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

include "cpfunctions.php";

// Get credentials
$cred      = getCRED();
$jio_cred  = json_decode($cred, true);
$ssoToken  = $jio_cred['ssoToken'] ?? '';
$crm       = $jio_cred['sessionAttributes']['user']['subscriberId'] ?? '';
$uniqueId  = $jio_cred['sessionAttributes']['user']['unique'] ?? '';

// Request parameters
$cid   = $_REQUEST['cid'] ?? '';
$sid   = $_REQUEST['sid'] ?? '';
$cooks = $_REQUEST['ck']  ?? '';

// Common cURL headers
$headers = [
    'Cookie: ' . hex2bin($cooks),
    'authority: jiotvcod.cdn.jio.com',
    'Content-type: application/x-www-form-urlencoded',
    'user-agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7',
];

// Process Catchup (CID)
if ($cid && $cooks) {
    [$seqq, $query] = explode('?', $cid, 2) + ['', ''];
    [, $vb, $ve]    = explode('=', $query) + ['', '', ''];
    $chs            = explode('-', $seqq);

    $link = sprintf(
        "https://jiotvcod.cdn.jio.com/bpk-tv/%s/Catchup_Fallback/%s?vbegin=%s&vend=%s",
        $chs[0],
        $seqq,
        $vb,
        $ve
    );

    $hs   = cUrlGetData($link, $headers);
    $base = 'cpauth.php?ck=' . $cooks;

    $replacements = [
        'https://tv.media.jio.com/fallback/bpk-tv/' => $base . '&pkey=',
        $chs[0] . '-'                               => $PROXY
            ? $base . '&ts=' . $chs[0] . '/Catchup_Fallback/' . $chs[0] . '-'
            : "https://jiotvcod.cdn.jio.com/bpk-tv/{$chs[0]}/Catchup_Fallback/{$chs[0]}-",
        '.ts'                                       => $PROXY
            ? '.ts'
            : '.ts?' . hex2bin($cooks),
    ];

    exit(str_replace(array_keys($replacements), array_values($replacements), $hs));
}

// Process Stream (SID)
if ($sid && $cooks) {
    [$seq, $query] = explode('?', $sid, 2) + ['', ''];
    $chs           = explode('/', $seq);

    $link = sprintf(
        "https://jiotvmbcod.cdn.jio.com/%s/%s/%s?%s",
        $chs[0],
        $chs[1],
        $chs[2],
        hex2bin($cooks)
    );

    $hs   = cUrlGetData($link, $headers);
    $base = 'cpauth.php?ck=' . $cooks;

    $replacements = [
        'https://tv.media.jio.com/streams_catchup/' => $base . '&key=',
        'https://jiotvmbcod.cdn.jio.com/'           => $PROXY ? $base . '&tss=' : 'https://jiotvmbcod.cdn.jio.com/',
        '.ts'                                       => $PROXY ? '.ts' : '.ts?' . hex2bin($cooks),
    ];

    exit(str_replace(array_keys($replacements), array_values($replacements), $hs));
}
