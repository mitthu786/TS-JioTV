<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

// Set Proxy  
$config = parse_ini_file('../../config.ini', true);
$PROXY = $config['settings']['proxy'] ?? null;

// Constants
define('DATA_FOLDER', '../assets/data');
define('TOKEN_EXPIRY_TIME', 7000);

// Determine protocol, local IP address
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$host_jio = ($_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : $local_ip;

if (strpos($host_jio, $_SERVER['SERVER_PORT']) === false) {
  $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

$jio_path = $protocol . $host_jio . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$DATA_FOLDER = "../assets/data";

function refresh_token()
{
  global $DATA_FOLDER, $jio_path;
  $filePath = $DATA_FOLDER . "/creds.jtv";
  $jio_path = @str_replace("/catchup/", "/", $jio_path);
  // Check if the file exists and if it's older than 7000 seconds
  if (file_exists($filePath) && (time() - filemtime($filePath) > TOKEN_EXPIRY_TIME)) {
    return cUrlGetData($jio_path . "/login/refreshLogin.php");
  }
}


function getCRED()
{
  global $DATA_FOLDER;
  $filePath = $DATA_FOLDER . "/creds.jtv";
  $key_data = file_get_contents($DATA_FOLDER . "/credskey.jtv");
  $cred_data = decrypt_data(file_get_contents($filePath), $key_data);
  return $cred_data;
}


function jio_sony_headers($ck, $id, $crm, $device_id, $access_token, $uniqueId, $ssoToken)
{
  $reqHeader = array();
  $reqHeader[] = "Cookie: " . hex2bin($ck);
  $reqHeader[] = "appkey: NzNiMDhlYcQyNjJm";
  $reqHeader[] = "accesstoken: " . $access_token;
  $reqHeader[] = "channel_id: " . $id;
  $reqHeader[] = "channelid: " . $id;
  $reqHeader[] = "crmid: " . $crm;
  $reqHeader[] = "deviceId: " . $device_id;
  $reqHeader[] = "devicetype: phone";
  $reqHeader[] = "x-platform: android";
  $reqHeader[] = "srno: 250918144000";
  $reqHeader[] = "ssotoken: " . $ssoToken;
  $reqHeader[] = "subscriberId: " . $crm;
  $reqHeader[] = "uniqueId: " . $uniqueId;
  $reqHeader[] = "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7";
  $reqHeader[] = "usergroup: tvYR7NSNn7rymo3F";
  $reqHeader[] = "versionCode: 452";
  $reqHeader[] = "appname: RJIL_JioTV";
  $reqHeader[] = "Origin: https://www.jiocinema.com";
  $reqHeader[] = "Referer: https://www.jiocinema.com/";
  return $reqHeader;
}

function jio_headers($cookies, $crm, $device_id, $ssoToken, $uniqueId)
{
  $reqHeader = array();
  $reqHeader[] = "Cookie: " . hex2bin($cookies);
  $reqHeader[] = "appkey: NzNiMDhlYcQyNjJm";
  $reqHeader[] = "channel_id: 144";
  $reqHeader[] = "channelid: 144";
  $reqHeader[] = "crmid: " . $crm;
  $reqHeader[] = "deviceId: " . $device_id;
  $reqHeader[] = "devicetype: phone";
  $reqHeader[] = "isott: true";
  $reqHeader[] = "languageId: 6";
  $reqHeader[] = "lbcookie: 1";
  $reqHeader[] = "os: android";
  $reqHeader[] = "osVersion: 14";
  $reqHeader[] = "srno: 250918144000";
  $reqHeader[] = "ssotoken: " . $ssoToken;
  $reqHeader[] = "subscriberId: " . $crm;
  $reqHeader[] = "uniqueId: " . $uniqueId;
  $reqHeader[] = "appname: RJIL_JioTV";
  $reqHeader[] = "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7";
  $reqHeader[] = "usergroup: tvYR7NSNn7rymo3F";
  $reqHeader[] = "versionCode: 452";
  return $reqHeader;
}

function cUrlGetData($url, $headers = null, $post_fields = null)
{

  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);

  if (!empty($post_fields)) {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
  }

  if (!empty($headers))
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }

  curl_close($ch);
  return $data;
}

// Check if server is Apache compatible
function isApache(): bool
{
  $software = strtolower($_SERVER['SERVER_SOFTWARE'] ?? '');
  $compatibleServers = ['apache', 'litespeed', 'openlitespeed'];

  foreach ($compatibleServers as $server) {
    if (strpos($software, strtolower($server)) !== false) {
      return true;
    }
  }

  return false;
}

// ENCRYPTION && DECRYPTION
function encrypt_data($data, $key)
{
  $key = intval($key);
  $encrypted = '';
  for ($i = 0; $i < strlen($data); $i++) {
    $encrypted .= chr(ord($data[$i]) + $key);
  }
  return base64_encode($encrypted);
}

function decrypt_data($e_data, $key)
{
  $key = intval($key);
  $encrypted = base64_decode($e_data);
  $decrypted = '';
  for ($i = 0; $i < strlen($encrypted); $i++) {
    $decrypted .= chr(ord($encrypted[$i]) - $key);
  }
  return $decrypted;
}

function getEPGData($id, $pg)
{
  $headers = [
    'Host' => 'jiotvapi.cdn.jio.com',
    'user-agent' => 'okhttp/4.12.13',
    'Accept-Encoding' => 'gzip'
  ];

  $context = stream_context_create([
    'http' => [
      'method' => 'GET',
      'header' => implode("\r\n", array_map(
        fn($k, $v) => "$k: $v",
        array_keys($headers),
        $headers
      ))
    ]
  ]);

  $url = "https://jiotvapi.cdn.jio.com/apis/v1.3/getepg/get?offset=$pg&channel_id=$id&langId=6";
  $response = @file_get_contents($url, false, $context);

  return $response ? @json_decode(gzdecode($response), true) : null;
}
