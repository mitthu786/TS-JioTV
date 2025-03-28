<?php

// Copyright 2021-2025 SnehTV, Inc.
// Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// Created By : TechieSneh

error_reporting(0);
header("Access-Control-Allow-Origin: *");

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
$key = filter_input(INPUT_GET, 'key', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
$chunks = filter_input(INPUT_GET, 'chunks', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
$segment = filter_input(INPUT_GET, 'segment', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";

$streamHeader = [
  "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
];

function execute_curl($url, $headers = [])
{
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_HTTPHEADER      => $headers,
    CURLOPT_HEADER          => 0,
    CURLOPT_TIMEOUT         => 5,
    CURLOPT_RETURNTRANSFER  => 1,
    CURLOPT_FOLLOWLOCATION  => 1,
    CURLOPT_SSL_VERIFYHOST  => 0,
    CURLOPT_SSL_VERIFYPEER  => 0
  ]);

  $response = curl_exec($ch);
  $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  $effURL = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);

  if ($response === false) {
    error_log("cURL Error: " . curl_error($ch));
    $http_code = 500;
  }

  curl_close($ch);

  return [$response, $http_code, $effURL];
}


if (!empty($id)) {
  $streamURL = ch_play($id);
  [$return, $http_code, $effURL] = execute_curl($streamURL, $streamHeader);

  if ($http_code !== 200) {
    http_response_code(503);
    exit();
  }

  if (stripos($return, '#EXTM3U') !== false) {
    $tine = "";
    $lines = explode("\n", $return);
    foreach ($lines as $line) {
      if (stripos($line, ".m3u8") !== false) {
        $iBaseURL = ($line[0] == "/") ? getRootBase($effURL) : getRelBase($effURL);
        $tine .= "s_live.php?chunks=" . hide_data("encrypt", $iBaseURL . $line) . "\n";
      } else {
        $tine .= $line . "\n";
      }
    }
    header("Content-Type: application/vnd.apple.mpegurl");
    exit(trim($tine));
  } else {
    http_response_code(503);
    exit();
  }
}

if (!empty($chunks)) {
  $streamURL = hide_data("decrypt", $chunks);
  if (!filter_var($streamURL, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit();
  }

  [$return, $http_code, $effURL] = execute_curl($streamURL, $streamHeader);

  if ($http_code !== 200) {
    http_response_code(404);
    exit();
  }

  if (stripos($return, '#EXTM3U') !== false) {
    $tine = "";
    $lines = explode("\n", $return);
    foreach ($lines as $line) {
      if (stripos($line, 'URI="') !== false) {
        $orgURL = get_uri($line);
        $norgURL = "s_live.php?key=" . hide_data("encrypt", $orgURL);
        $tine .= str_replace($orgURL, $norgURL, $line) . "\n";
      } elseif (stripos($line, ".ts") !== false) {
        $iBaseURL = ($line[0] == "/") ? getRootBase($effURL) : (($line[0] == "h") ? "" : getRelBase($effURL));
        $tine .= "s_live.php?segment=" . hide_data("encrypt", $iBaseURL . $line) . "\n";
      } else {
        $tine .= $line . "\n";
      }
    }
    header("Content-Type: application/vnd.apple.mpegurl");
    exit(trim($tine));
  } else {
    http_response_code(404);
    exit();
  }
}

if (!empty($segment)) {
  $streamURL = hide_data("decrypt", $segment);
  if (!filter_var($streamURL, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit();
  }

  [$return, $http_code] = execute_curl($streamURL, $streamHeader);

  if ($http_code == 200 || $http_code == 206) {
    header("Content-Type: video/m2ts");
    exit($return);
  } else {
    http_response_code(410);
    exit();
  }
}

if (!empty($key)) {
  $streamURL = hide_data("decrypt", $key);
  if (!filter_var($streamURL, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    exit();
  }

  [$return, $http_code] = execute_curl($streamURL, $streamHeader);

  if ($http_code == 200 || $http_code == 206) {
    header("Content-Type: application/binary");
    exit($return);
  } else {
    http_response_code(403);
    exit();
  }
}

function getRootBase($url)
{
  $purl = parse_url($url);
  return isset($purl['scheme'], $purl['host']) ? "{$purl['scheme']}://{$purl['host']}" : "";
}

function getRelBase($url)
{
  $url = strtok($url, '?');
  return str_replace(basename($url), "", $url);
}

function get_uri($string)
{
  preg_match('/URI="(.*?)"/', $string, $matches);
  return $matches[1] ?? "";
}

function hide_data($action, $data)
{
  $output = "";
  $key = "TS_JioTv";
  $iv = substr(hash('sha256', "._._TS_JioTv_._.", true), 0, 16); // 

  if ($action === "encrypt") {
    $encrypted = openssl_encrypt($data, "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
    $output = $encrypted !== false ? bin2hex($encrypted) : "";
  } elseif ($action === "decrypt") {
    $decrypted = openssl_decrypt(hex2bin($data), "AES-128-CBC", $key, OPENSSL_RAW_DATA, $iv);
    $output = $decrypted !== false ? $decrypted : "";
  }

  return $output;
}

function ch_play($id)
{
  $play_url = "";
  $json_url = "https://avkb.short.gy/slivChs.json";
  $ch_list = @file_get_contents($json_url);

  if ($ch_list !== false) {
    $channels = json_decode($ch_list, true);
    if (is_array($channels)) {
      foreach ($channels as $ch) {
        if (isset($ch['id'], $ch['url']) && $ch['id'] === $id) {
          return $ch['url'];
        }
      }
    }
  }
  return $play_url;
}
