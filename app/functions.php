<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

// Set Proxy  
$PROXY = false;

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
    "srno: 240707144000",
    "ssotoken: $ssoToken",
    "subscriberId: $crm",
    "uniqueId: $uniqueId",
    "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7",
    "usergroup: tvYR7NSNn7rymo3F",
    "versionCode: 331",
    "Origin: https://www.jiocinema.com",
    "Referer: https://www.jiocinema.com/",
  ];
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
  $encrypted = array_map(fn ($char) => chr(ord($char) + $key), str_split($data));
  return base64_encode(implode('', $encrypted));
}

// Decrypt data
function decrypt_data($e_data, $key)
{
  $key = (int) $key;
  $encrypted = base64_decode($e_data);
  $decrypted = array_map(fn ($char) => chr(ord($char) - $key), str_split($encrypted));
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
