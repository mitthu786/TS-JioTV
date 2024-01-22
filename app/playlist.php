<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh


error_reporting(0);
$jio_fname = 'TS-JioTV_' . md5(time() . 'JioTV') . '.m3u';
header("Content-Type: application/vnd.apple.mpegurl");
header("Content-Disposition: inline; filename=$jio_fname");

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$ip_port = $_SERVER['SERVER_PORT'];
if ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" || 'localhost') {
    $host_jio = $_SERVER['HTTP_HOST'];
} else {
    $host_jio = $local_ip;
}

$jio_path = $protocol . $host_jio  . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$url = "==gbvNnauEGdhR2bpp2L2R3bpp2LnBXZ2R3LvlmLiVHa0l2ZuYDO3UHa0RXat9yL6MHc0RHa";
$json = json_decode(file_get_contents(base64_decode(strrev($url))), true);


$sony_data = <<<GFG

#EXTINF:-1 tvg-id="291" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_HD.png" group-title="TS-JioTV SonyLIV",SONY HD
https://dai.google.com/linear/hls/event/dBdwOiGaQvy0TA1zOsjV6w/master.m3u8

#EXTINF:-1 tvg-id="471" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_SAB_HD.png" group-title="TS-JioTV SonyLIV",SONY SAB HD
https://dai.google.com/linear/hls/event/CrTivkDESWqwvUj3zFEYEA/master.m3u8

#EXTINF:-1 tvg-id="474" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pal.png" group-title="TS-JioTV SonyLIV",SONY PAL
https://dai.google.com/linear/hls/event/dhPrGRwDRvuMQtmlzppzQQ/master.m3u8

#EXTINF:-1 tvg-id="762" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Pix_HD.png" group-title="TS-JioTV SonyLIV",SONY PIX HD
https://dai.google.com/linear/hls/event/x7rXWd2ERZ2tvyQWPmO1HA/master.m3u8

#EXTINF:-1 tvg-id="289" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/SET_MAX.png" group-title="TS-JioTV SonyLIV",SONY MAX
https://dai.google.com/linear/hls/event/Oc1isQAET3WaNPoABfScmg/master.m3u8

#EXTINF:-1 tvg-id="476" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Max_HD.png" group-title="TS-JioTV SonyLIV",SONY MAX HD
https://dai.google.com/linear/hls/event/UcjHNJmCQ1WRlGKlZm73QA/master.m3u8

#EXTINF:-1 tvg-id="483" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_MAX2.png" group-title="TS-JioTV SonyLIV",SONY MAX2
https://dai.google.com/linear/hls/event/MdQ5Zy-PSraOccXu8jflCg/master.m3u8

#EXTINF:-1 tvg-id="1393" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Wah.png" group-title="TS-JioTV SonyLIV",SONY WAH
https://dai.google.com/linear/hls/event/gX5rCBf6Q7-D5AWY-sovzQ/master.m3u8

#EXTINF:-1 tvg-id="162" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 1 HD
https://dai.google.com/linear/hls/event/wG75n5U8RrOKiFzaWObXbA/master.m3u8

#EXTINF:-1 tvg-id="514" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 1
https://dai.google.com/linear/hls/event/4_pnLi2QTe6bRGvvahRbfg/master.m3u8

#EXTINF:-1 tvg-id="891" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten2_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 2 HD
https://dai.google.com/linear/hls/event/V9h-iyOxRiGp41ppQScDSQ/master.m3u8

#EXTINF:-1 tvg-id="891" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_2.png" group-title="TS-JioTV SonyLIV",SONY TEN 2
https://dai.google.com/linear/hls/event/LK-ik89MQIi_pWBbg74KNQ/master.m3u8

#EXTINF:-1 tvg-id="892" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten3_HD.png" group-title="TS-JioTV SonyLIV",SONY TEN 3 HD
https://dai.google.com/linear/hls/event/ltsCG7TBSCSDmyq0rQtvSA/master.m3u8

#EXTINF:-1 tvg-id="524" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Ten_3.png" group-title="TS-JioTV SonyLIV",SONY TEN 3
https://dai.google.com/linear/hls/event/BCOFZq1JQjq12fmaO6lAAA/master.m3u8

#EXTINF:-1 tvg-id="817" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 4 HD
https://dai.google.com/linear/hls/event/tNzcW2ZhTVaViggo5ocI-A/master.m3u8

#EXTINF:-1 tvg-id="817" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen4_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 4
https://dai.google.com/linear/hls/event/smYybI_JToWaHzwoxSE9qA/master.m3u8

#EXTINF:-1 tvg-id="155" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 5 HD
https://dai.google.com/linear/hls/event/Sle_TR8rQIuZHWzshEXYjQ/master.m3u8

#EXTINF:-1 tvg-id="525" tvg-logo="https://www.sonypicturesnetworks.com/images/logos/SONY_SportsTen5_HD_Logo_CLR.png" group-title="TS-JioTV SonyLIV",SONY TEN 5
https://dai.google.com/linear/hls/event/r-eLp41YTHWTagvQSXFtAQ/master.m3u8

#EXTINF:-1 tvg-id="821" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_BBC_Earth_HD.png" group-title="TS-JioTV SonyLIV",SONY BBC EARTH HD
https://dai.google.com/linear/hls/event/6bVWYIKGS0CIa-cOpZZJPQ/master.m3u8

#EXTINF:-1 tvg-id="872" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Yay_Hindi.png" group-title="TS-JioTV SonyLIV",SONY YAY
https://dai.google.com/linear/hls/event/GPY7RqOrSkmKJ8z1GbVNhg/master.m3u8

#EXTINF:-1 tvg-id="1146" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_Marathi_SD.png" group-title="TS-JioTV SonyLIV",SONY MARATHI
https://dai.google.com/linear/hls/event/I2phC6tgTDuJngxw9gJgPw/master.m3u8

#EXTINF:-1 tvg-id="697" tvg-logo="https://jiotv.catchup.cdn.jio.com/dare_images/images/Sony_aath.png" group-title="TS-JioTV SonyLIV",SONY AATH 
https://dai.google.com/linear/hls/event/j-YEIDwORxubtP_967VcZg/master.m3u8

GFG;


$jio_data = '#EXTM3U x-tvg-url="https://avkb.short.gy/jioepg.xml.gz"' . PHP_EOL;
$jio_data .= $sony_data . PHP_EOL;
foreach ($json as $channel) {
    $jio_data .= '#EXTINF:-1 tvg-id="' . $channel['channel_id'] . '" tvg-name="' . $channel['channel_name'] . '" tvg-type="' . $channel['channelCategoryId'] . '" group-title="TS-JioTV ' . $channel['channelCategoryId'] . '" tvg-language="' . $channel['channelLanguageId'] . '" tvg-logo="' . $channel['logoUrl'] . '",' . $channel['channel_name'] . PHP_EOL;
    $jio_data .= $jio_path . 'live.php?id=' . $channel['channel_id'] . '&e=.m3u8' . PHP_EOL;
    $jio_data .= "" . PHP_EOL;
}
print($jio_data);
