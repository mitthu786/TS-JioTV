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

// Define the SonyLIV data
$sony_data = <<<GFG

#EXTINF:-1 tvg-id="291" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_HD.png" group-title="TS-JioTV SonyLIV",SONY HD
https://dai.google.com/ssai/event/HgaB-u6rSpGx3mo4Xu3sLw/master.m3u8

#EXTINF:-1 tvg-id="471" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_SAB_HD.png" group-title="TS-JioTV SonyLIV",SONY SAB HD
https://dai.google.com/ssai/event/UI4QFJ_uRk6aLxIcADqa_A/master.m3u8

#EXTINF:-1 tvg-id="474" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pal.png" group-title="TS-JioTV SonyLIV",SONY PAL
https://dai.google.com/ssai/event/rPzF28qORbKZkhci_04fdQ/master.m3u8

#EXTINF:-1 tvg-id="762" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pix_HD.png" group-title="TS-JioTV SonyLIV",SONY PIX HD
https://dai.google.com/ssai/event/8FR5Q-WfRWCkbMq_GxZ77w/master.m3u8

#EXTINF:-1 tvg-id="289" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/SET_MAX.png" group-title="TS-JioTV SonyLIV",SONY MAX
https://dai.google.com/ssai/event/oJ-TGgVFSgSMBUoTkauvFQ/master.m3u8

#EXTINF:-1 tvg-id="476" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Max_HD.png" group-title="TS-JioTV SonyLIV",SONY MAX HD
https://dai.google.com/ssai/event/Qyqz40bSQriqSuAC7R8_Fw/master.m3u8

#EXTINF:-1 tvg-id="483" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_MAX2.png" group-title="TS-JioTV SonyLIV",SONY MAX2
https://dai.google.com/ssai/event/4Jcu195QTpCNBXGnpw2I6g/master.m3u8

#EXTINF:-1 tvg-id="1393" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Wah.png" group-title="TS-JioTV SonyLIV",SONY WAH
https://dai.google.com/ssai/event/H_ZvXWqHRGKpHcdDE5RcDA/master.m3u8

#EXTINF:-1 tvg-id="162" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 1 HD
https://dai.google.com/ssai/event/yeYP86THQ4yl7US8Zx5eug/master.m3u8

#EXTINF:-1 tvg-id="514" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 1
https://dai.google.com/ssai/event/4_pnLi2QTe6bRGvvahRbfg/master.m3u8

#EXTINF:-1 tvg-id="891" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten2_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 2 HD
https://dai.google.com/ssai/event/Syu8F41-R1y_JmQ7x0oNxQ/master.m3u8

#EXTINF:-1 tvg-id="891" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_2.png" group-title="TS-JioTV SonyLIV",SONY TEN 2
https://dai.google.com/ssai/event/nspQRqO5RmC06VmlPrTwkQ/master.m3u8

#EXTINF:-1 tvg-id="892" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten3_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 3 HD
https://dai.google.com/ssai/event/nmQFuHURTYGQBNdUG-2Qdw/master.m3u8

#EXTINF:-1 tvg-id="524" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_3.png" group-title="TS-JioTV SonyLIV",SONY TEN 3
https://dai.google.com/ssai/event/9kocjiLUSf-erlSrv3d4Mw/master.m3u8

#EXTINF:-1 tvg-id="817" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 4 HD
https://dai.google.com/ssai/event/x4LxWUcVSIiDaq1VCM7DSA/master.m3u8

#EXTINF:-1 tvg-id="817" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 4
https://dai.google.com/ssai/event/hInaEKUJSziZAGv9boOdjg/master.m3u8

#EXTINF:-1 tvg-id="155" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 5 HD
https://dai.google.com/ssai/event/DD7fA-HgSUaLyZp9AjRYxQ/master.m3u8

#EXTINF:-1 tvg-id="525" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 5
https://dai.google.com/ssai/event/S-q8I27RRzmkb-OIdoaiAw/master.m3u8

#EXTINF:-1 tvg-id="821" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_BBC_Earth_HD.png" group-title="TS-JioTV SonyLIV",SONY BBC EARTH HD
https://dai.google.com/ssai/event/V73ovbgASP-xGvQQOukwTQ/master.m3u8

#EXTINF:-1 tvg-id="872" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Yay_Hindi.png" group-title="TS-JioTV SonyLIV",SONY YAY
https://dai.google.com/ssai/event/40H5HfwWTZadFGYkBTqagg/master.m3u8

#EXTINF:-1 tvg-id="1146" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Marathi_SD.png" group-title="TS-JioTV SonyLIV",SONY MARATHI
https://dai.google.com/ssai/event/-_w3Jbq3QoW-mFCM2YIzxA/master.m3u8

#EXTINF:-1 tvg-id="697" tvg-logo="http://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_aath.png" group-title="TS-JioTV SonyLIV",SONY AATH 
https://dai.google.com/ssai/event/pSVzGmMpQR6jdmwwJg87OQ/master.m3u8

#EXTINF:-1 tvg-id="" tvg-logo="https://c.evidon.com/pub_logos/2796-2021122219404475.png" group-title="TS-JioTV SonyLIV", SONY KAL
https://spt-sonykal-1-us.lg.wurl.tv/playlist.m3u8

GFG;

// Start generating the M3U data
$jio_data = '#EXTM3U x-tvg-url="https://avkb.short.gy/jioepg.xml.gz"' . PHP_EOL;
$jio_data .= $sony_data . PHP_EOL;

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
