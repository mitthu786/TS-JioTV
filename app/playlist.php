<?php

// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By: TechieSneh

error_reporting(0);
include "functions.php";

// Generate a unique filename for the M3U playlist
$jio_fname = 'TS-JioTV_' . md5(time() . 'JioTV') . '.m3u';

// Set HTTP headers
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: inline; filename=$jio_fname");

// Determine the protocol and host
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$host_jio = ($_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : $local_ip;

if (strpos($host_jio, $_SERVER['SERVER_PORT']) === false) {
    $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

// Construct the Jio path
$jio_path = $protocol . $host_jio . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

// Decode the URL and fetch the JSON data
$url = "==gbvNnauEGdhR2bpp2L2R3bpp2LnBXZ2R3LvlmLiVHa0l2ZuYDO3UHa0RXat9yL6MHc0RHa";
$json_data = file_get_contents(base64_decode(strrev($url)));
$json = json_decode($json_data, true);

// Start generating the M3U data
$jio_data = '#EXTM3U x-tvg-url="https://avkb.short.gy/jioepg.xml.gz"' . PHP_EOL;

// Add the JioTV channels to the M3U data
if ($json !== null) {
    foreach ($json as $channel) {
        $channel_id = htmlspecialchars($channel['channel_id'], ENT_QUOTES, 'UTF-8');
        $channel_name = htmlspecialchars($channel['channel_name'], ENT_QUOTES, 'UTF-8');
        $channel_category = htmlspecialchars($channel['channelCategoryId'], ENT_QUOTES, 'UTF-8');
        $channel_language = htmlspecialchars($channel['channelLanguageId'], ENT_QUOTES, 'UTF-8');
        $logo_url = htmlspecialchars($channel['logoUrl'], ENT_QUOTES, 'UTF-8');

        if (isApache()) {
            $catchup_source = htmlspecialchars($jio_path . 'catchup/ts_catchup_' . urlencode($channel['channel_id']) . '_${catchup-id}_${start}_${stop}.m3u8', ENT_QUOTES, 'UTF-8');
            $stream_url = $jio_path . 'ts_live_' . urlencode($channel['channel_id']) . '.m3u8';
        } else {
            $catchup_source = $jio_path . 'catchup/cpapi.php?id=' . urlencode($channel['channel_id']) . '&srno=${catchup-id}&begin={start}&end=${stop}&e=.m3u8';
            $stream_url = $jio_path . 'live.php?id=' . urlencode($channel['channel_id']) . '&e=.m3u8';
        }

        $jio_data .= sprintf(
            '#EXTINF:-1 tvg-id="%s" tvg-name="%s" tvg-type="%s" group-title="TS-JioTV %s" tvg-language="%s" tvg-logo="%s"%s,%s',
            $channel_id,
            $channel_name,
            $channel_category,
            $channel_category,
            $channel_language,
            $logo_url,
            $channel['isCatchupAvailable'] == "True" ? ' catchup-days="7" catchup="auto" catchup-source="' . $catchup_source . '"' : '',
            $channel_name
        ) . PHP_EOL;

        $jio_data .= $stream_url . PHP_EOL . PHP_EOL;
    }
}

// Print the M3U data
echo $jio_data;
