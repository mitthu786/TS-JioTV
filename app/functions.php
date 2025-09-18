<?php

// * Copyright 2021-2025 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

// Load configuration 
$config = parse_ini_file('../config.ini', true);
$PROXY = $config['settings']['proxy'] ?? null;

// Constants
define('DATA_FOLDER', 'assets/data');
define('TOKEN_EXPIRY_TIME', 7000);
define('COOKIE_EXPIRY_TIME', 40000);

// Determine protocol
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

// Get local IP address
$local_ip = getHostByName(php_uname('n'));

// Determine host
$host_jio = ($_SERVER['SERVER_ADDR'] !== '127.0.0.1' && $_SERVER['SERVER_ADDR'] !== 'localhost') ? $_SERVER['HTTP_HOST'] : $local_ip;

if (strpos($host_jio, $_SERVER['SERVER_PORT']) === false) {
  $host_jio .= ':' . $_SERVER['SERVER_PORT'];
}

// Build Jio path
$jio_path = $protocol . $host_jio . str_replace(' ', '%20', str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

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

// Refresh token if necessary
function refresh_token()
{
  $filePath = DATA_FOLDER . '/creds.jtv';
  $TokenNeedsRefresh = !file_exists($filePath) || (time() - filemtime($filePath) > TOKEN_EXPIRY_TIME);

  if ($TokenNeedsRefresh) {
    return cUrlGetData($GLOBALS['jio_path'] . '/login/refreshLogin.php');
  }
  return null;
}

// Refresh cookie if necessary
function get_and_refresh_cookie($url, $headers)
{
  $filePath = DATA_FOLDER . '/cookie.jtv';
  $cookieNeedsRefresh = !file_exists($filePath) || (time() - filemtime($filePath) > COOKIE_EXPIRY_TIME);

  if ($cookieNeedsRefresh) {
    $cookies = getCookiesFromUrl($url, $headers);
    if (isset($cookies['__hdnea__'])) {
      $cooKee = bin2hex('__hdnea__=' . $cookies['__hdnea__']);
      file_put_contents($filePath, $cooKee);
    } else {
      throw new Exception("Cookie '__hdnea__' not found in response.");
    }
  } else {
    $cooKee = file_get_contents($filePath);
  }

  return $cooKee;
}


// Generate Jio headers
function jio_headers($cookies, $access_token, $crm, $device_id, $ssoToken, $uniqueId)
{
  return [
    "Cookie: $cookies",
    "accesstoken: $access_token",
    "appkey: NzNiMDhlYcQyNjJm",
    "channel_id: 144",
    "crmid: $crm",
    "deviceId: $device_id",
    "devicetype: phone",
    "isott: true",
    "languageId: 6",
    "lbcookie: 1",
    "os: android",
    "osVersion: 14",
    "srno: 250918144000",
    "ssotoken: $ssoToken",
    "subscriberId: $crm",
    "uniqueId: $uniqueId",
    "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7",
    "usergroup: tvYR7NSNn7rymo3F",
    "versionCode: 452",
    "Origin: https://www.jiocinema.com",
    "Referer: https://www.jiocinema.com/",
  ];
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

// Fetch data using cURL
function cUrlGetData($url, $headers = null, $post_fields = null)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);

  if (!empty($post_fields)) {
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
  }

  if (!empty($headers)) {
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
  }

  $data = curl_exec($ch);

  if (curl_errno($ch)) {
    echo 'Error:' . curl_error($ch);
  }

  curl_close($ch);
  return $data;
}

// Get credentials
function getCRED()
{
  $filePath = DATA_FOLDER . '/creds.jtv';
  $key_data = file_get_contents(DATA_FOLDER . '/credskey.jtv');
  return decrypt_data(file_get_contents($filePath), $key_data);
}

// Encrypt data
function encrypt_data($data, $key)
{
  $key = (int) $key;
  $encrypted = array_map(fn($char) => chr(ord($char) + $key), str_split($data));
  return base64_encode(implode('', $encrypted));
}

// Decrypt data
function decrypt_data($e_data, $key)
{
  $key = (int) $key;
  $encrypted = base64_decode($e_data);
  $decrypted = array_map(fn($char) => chr(ord($char) - $key), str_split($encrypted));
  return implode('', $decrypted);
}

// Get cookies from URL
function getCookiesFromUrl($url, $headers = [], $post_fields = null)
{
  $ch = curl_init($url);
  curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HEADER => true,
    CURLOPT_HTTPHEADER => $headers,
  ]);

  if ($post_fields !== null) {
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
  }

  $response = curl_exec($ch);
  $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
  $header = substr($response, 0, $header_size);
  curl_close($ch);

  return extractCookies($header);
}

// Extract cookies from header
function extractCookies($header)
{
  $cookies = [];
  foreach (explode("\r\n", $header) as $line) {
    if (preg_match('/^Set-Cookie:\s*([^;]*)/mi', $line, $matches)) {
      parse_str($matches[1], $cookie);
      $cookies = array_merge($cookies, $cookie);
    }
  }
  return $cookies;
}

function getUserData()
{
  $filePath = __DIR__ . "/assets/data/creds.jtv";
  $key_data = file_get_contents(__DIR__ . "/assets/data/credskey.jtv");
  $cred = decrypt_data(file_get_contents($filePath), $key_data);

  $jio_cred = json_decode($cred, true) ?? [];

  $name = $jio_cred['sessionAttributes']['user']['commonName'];
  $mobile = $jio_cred['sessionAttributes']['user']['mobile'];
  $jwt = $jio_cred['authToken'];
  $parts = explode('.', $jwt);
  $payload = base64_decode($parts[1]);
  $expiry_time = json_decode($payload, true)['exp'];


  $date = new DateTime();
  $date->setTimestamp($expiry_time);
  $date->setTimezone(new DateTimeZone('Asia/Kolkata'));
  $exp_date_time = $date->format('d-M-Y h:i:s A');

  return [
    'name' => $name,
    'mobile' => $mobile,
    'exp_date_time' => $exp_date_time
  ];
}

// JIOTV FUNCTIONS

function getJioTvData($id)
{

  // Get credentials
  $cred = getCRED();
  $jio_cred = json_decode($cred, true) ?? [];
  extract($jio_cred['sessionAttributes']['user'] ?? []);

  $access_token = $jio_cred['authToken'] ?? '';
  $crm = $subscriberId ?? '';
  $uniqueId = $unique ?? '';
  $device_id = $jio_cred['deviceId'] ?? '';

  $post_data = http_build_query(['stream_type' => 'Seek', 'channel_id' => $id]);

  $headers = [
    "Host: jiotvapi.media.jio.com",
    "Content-Type: application/x-www-form-urlencoded",
    "appkey: NzNiMDhlYzQyNjJm",
    "channel_id: $id",
    "userid: $crm",
    "crmid: $crm",
    "deviceId: $device_id",
    "devicetype: phone",
    "isott: true",
    "languageId: 6",
    "lbcookie: 1",
    "os: android",
    "dm: Xiaomi 22101316UP",
    "osversion: 14",
    "srno: 250918144000",
    "accesstoken: $access_token",
    "subscriberid: $crm",
    "uniqueId: $uniqueId",
    "content-length: " . strlen($post_data),
    "usergroup: tvYR7NSNn7rymo3F",
    "User-Agent: okhttp/4.12.13",
    "versionCode: 452",
  ];

  $response = cUrlGetData("https://jiotvapi.media.jio.com/playback/apis/v1/geturl?langId=6", $headers, $post_data);
  return json_decode($response);
}
