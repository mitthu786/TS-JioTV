<?php
// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Common headers
header("Access-Control-Allow-Origin: *");
header("Access-Control-Expose-Headers: Content-Length, Content-Range");
header("Access-Control-Allow-Headers: Range");
header("Accept-Ranges: bytes");

// Fetch credentials
$cred = getCRED();
$jio_cred = json_decode($cred, true) ?? [];
if (!$jio_cred) die("Invalid credentials");

[
    'ssoToken' => $ssoToken,
    'authToken' => $access_token,
    'deviceId' => $device_id,
    'sessionAttributes' => [
        'user' => [
            'subscriberId' => $crm,
            'unique' => $uniqueId
        ]
    ]
] = $jio_cred;

// Process request
$cookies = isset($_REQUEST['ck']) ? hex2bin($_REQUEST['ck']) : '';
if (empty($cookies)) {
    http_response_code(400);
    die("Missing authentication token");
}

$headers = jio_headers($cookies, $access_token, $crm, $device_id, $ssoToken, $uniqueId);

// Determine request type
$param = match (true) {
    isset($_REQUEST['key']) => ['url' => 'streams_live/' . urlencode($_REQUEST['key']), 'type' => 'm3u8'],
    isset($_REQUEST['pkey']) => ['url' => 'fallback/bpk-tv/' . urlencode($_REQUEST['pkey']), 'type' => 'm3u8'],
    isset($_REQUEST['ts']) => [
        'url' => $_REQUEST['ts'],
        'type' => 'video/mp2t',
        'headers' => ['Connection: keep-alive']
    ],
    default => null
};

if (!$param) {
    http_response_code(400);
    die("Invalid request parameters");
}

// Set content headers
header("Content-Type: {$param['type']}");
foreach ($param['headers'] ?? [] as $header) header($header);

// Fetch and output data
if ($param['type'] === 'm3u8') {
    $data = cUrlGetData("https://tv.media.jio.com/{$param['url']}", $headers);
    echo $data ?: "Error fetching content";
    exit;
}
$data = cUrlGetData("https://jiotvmblive.cdn.jio.com/{$param['url']}", $headers);
echo $data ?: "Error fetching content";
