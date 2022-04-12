<?php
header("Content-Type: application/vnd.apple.mpegurl");
echo '#EXTM3U' . PHP_EOL;
echo '#EXTM3U x-tvg-url="https://github.com/mitthu786/tvepg/releases/download/latest/epg.xml.gz"' . PHP_EOL;
$json = json_decode(file_get_contents('assets/channels.json') , true);
$LANG_MAP = array(
    6 => "English",
    1 => "Hindi",
    2 => "Marathi",
    3 => "Punjabi",
    4 => "Urdu",
    5 => "Bengali",
    7 => "Malayalam",
    8 => "Tamil",
    9 => "Gujarati",
    10 => "Odia",
    11 => "Telugu",
    12 => "Bhojpuri",
    13 => "Kannada",
    14 => "Assamese",
    15 => "Nepali",
    16 => "French"
);
$GENRE_MAP = array(
    8 => "Sports",
    5 => "Entertainment",
    6 => "Movies",
    12 => "News",
    13 => "Music",
    7 => "Kids",
    9 => "Lifestyle",
    10 => "Infotainment",
    15 => "Devotional",
    16 => "Business",
    17 => "Educational",
    18 => "Shopping",
    19 => "JioDarshan"
);
foreach ($json['result'] as $channel)
{
    printf("#EXTINF:-1 tvg-id=\"%u\" group-title=\"%s\" tvg-language=\"%s\" tvg-logo=\"https://jiotv.catchup.cdn.jio.com/dare_images/images/%s\",%s" . PHP_EOL, $channel['channel_id'], $GENRE_MAP[$channel['channelCategoryId']], $LANG_MAP[$channel['channelLanguageId']], $channel['logoUrl'], $channel['channel_name']);
    printf("http://%s/jiotvweb/autoq.php?c=%s" . PHP_EOL . PHP_EOL, $_SERVER['HTTP_HOST'], $channel['target']);
}

