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

include "functions.php";
$cred = getCRED();
$jio_cred = json_decode($cred, true);
$ssoToken = $jio_cred['ssoToken'];
$crm = $jio_cred['sessionAttributes']['user']['subscriberId'];
$uniqueId = $jio_cred['sessionAttributes']['user']['unique'];
$device_id = $jio_cred['deviceId'];

$id = $_REQUEST["id"] ?? "";
$pq_id = $_REQUEST["pq_id"] ?? "";
$qid = $_REQUEST["qid"] ?? "";

$headers = array(
    'Content-type' => 'application/x-www-form-urlencoded',
    'appkey' => 'NzNiMDhlYzQyNjJm',
    'channelId' => $id,
    'channel_id' => $id,
    'crmid' => $crm,
    'deviceId' => $device_id,
    'devicetype' => 'phone',
    'isott' => 'true',
    'languageId' => '6',
    'lbcookie' => '1',
    'os' => 'android',
    'osVersion' => '13',
    'srno' => '240101144000',
    'ssotoken' => $ssoToken,
    'subscriberId' => $crm,
    'uniqueId' => $uniqueId,
    'User-Agent' => 'plaYtv/7.1.3 (Linux;Android 13) ExoPlayerLib/2.11.7',
    'usergroup' => 'tvYR7NSNn7rymo3F',
    'versionCode' => '331',
);

$data = [
    "channelId" => $id,
    "channel_id" => $id,
    "stream_type" => "Seek",
];
$post_data = http_build_query($data);

$haystacks = cUrlGetData("https://tv.media.jio.com/apis/v2.2/getchannelurl/getchannelurl?langId=6", $headers, $post_data);
$haystack = json_decode($haystacks);

$cookie = explode('?', $haystack->result);

if (strstr($cookie[1], "minrate=")) {
    $cookies_ww = explode("&", $cookie[1]);
    $cookies_w = $cookies_ww[2];
} else {
    $cookies_w = $cookie[1];
}

$chs = explode('/', $cookie[0]);
$enc_yy = strrev(base64_encode($cookies_w));
$enc_yyy = str_replace("+", "PLUS", $enc_yy);
$ency = str_replace("=", "EQUALS", $enc_yyy);

if (!empty($qid)) {

    $headers_1 = [
        "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7",
    ];

    $hs = cUrlGetData("https://jiotvbpkmob.cdn.jio.com/{$chs[3]}/{$qid}?{$cookies_w}", $headers_1);
    $qw1 = explode(".", $qid);
    $qw2 = explode("_", $qw1[0]);
    $qw = end($qw2);
    $pattern = "/{$chs[3]}_{$qw}-([^.]+\.)key/";
    $replacement = "auth.php?key={$chs[3]}/{$chs[3]}_{$qw}-$1key&ck={$ency}";
    $hs = preg_replace($pattern, $replacement, $hs);
    $pattern = "/{$chs[3]}_{$qw}-([^.]+\.)ts/";
    $replacement = "auth.php?ts={$chs[3]}/{$chs[3]}_{$qw}-$1ts&ck={$ency}";
    $hs = preg_replace($pattern, $replacement, $hs);
    $hs = str_replace("https://tv.media.jio.com/streams_live/{$chs[3]}/", "", $hs);
    echo $hs;
} elseif (!empty($pq_id)) {
    $hs = cUrlGetData("https://jiotvbpkmob.cdn.jio.com/{$chs[3]}/{$chs[4]}/{$pq_id}?{$cookies_w}", $headers_1);
    $hsw = explode('_', $hs);
    $qc = explode('.m3u8', $pq_id);
    $lived = [];
    foreach ($hsw as $new) {
        if (strstr($new, ".ts")) {
            $lived[] = explode('.ts', $new)[0];
        }
    }
    foreach ($lived as $liv_v) {
        $hs = str_replace("{$qc[0]}_{$liv_v}.ts", "auth.php?ts={$chs[3]}/{$chs[4]}/{$qc[0]}_{$liv_v}.ts&ck={$ency}", $hs);
    }
    $key_y = explode(',URI="', $hs);
    $key_t = explode('.key"', $key_y[1]);
    $key_new = str_replace("https://tv.media.jio.com/streams_live/", "", $key_t[0]);
    $hs = str_replace("{$key_t[0]}.key", "auth.php?key={$key_new}.key&ck={$ency}", $hs);
    echo $hs;
} else {
    echo "SOMETHING WENT WRONG";
}
