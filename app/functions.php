<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
$local_ip = getHostByName(php_uname('n'));
$ip_port = $_SERVER['SERVER_PORT'];
if ($_SERVER['SERVER_ADDR'] !== "127.0.0.1" || 'localhost') {
  $host_jio = $_SERVER['HTTP_HOST'];
} else {
  $host_jio = $local_ip;
}

$jio_path = $protocol . $host_jio . str_replace(" ", "%20", str_replace(basename($_SERVER['PHP_SELF']), '', $_SERVER['PHP_SELF']));

$DATA_FOLDER = "assets/data";

function refresh_token()
{
  global $DATA_FOLDER, $jio_path;
  $filePath = $DATA_FOLDER . "/creds.jtv";
  // Check if the file exists and if it's older than 7000 seconds
  if (file_exists($filePath) && (time() - filemtime($filePath) > 7000)) {
    return cUrlGetData($jio_path . "/login/refreshLogin.php");
  }
}

function jio_headers($cookies, $access_token, $crm, $device_id, $ssoToken, $uniqueId)
{
  $reqHeader = array();
  $reqHeader[] = "Cookie: " . $cookies;
  $reqHeader[] = "accesstoken: " . $access_token;
  $reqHeader[] = "appkey: NzNiMDhlYcQyNjJm";
  $reqHeader[] = "channel_id: 144";
  $reqHeader[] = "crmid: " . $crm;
  $reqHeader[] = "deviceId: " . $device_id;
  $reqHeader[] = "devicetype: phone";
  $reqHeader[] = "isott: true";
  $reqHeader[] = "languageId: 6";
  $reqHeader[] = "lbcookie: 1";
  $reqHeader[] = "os: android";
  $reqHeader[] = "osVersion: 14";
  $reqHeader[] = "srno: 240303144000";
  $reqHeader[] = "ssotoken: " . $ssoToken;
  $reqHeader[] = "subscriberId: " . $crm;
  $reqHeader[] = "uniqueId: " . $uniqueId;
  $reqHeader[] = "User-Agent: plaYtv/7.1.3 (Linux;Android 14) ExoPlayerLib/2.11.7";
  $reqHeader[] = "usergroup: tvYR7NSNn7rymo3F";
  $reqHeader[] = "versionCode: 331";
  $reqHeader[] = "Origin: https://www.jiocinema.com";
  $reqHeader[] = "Referer: https://www.jiocinema.com/";
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


function getCRED()
{
  global $DATA_FOLDER;
  $filePath = $DATA_FOLDER . "/creds.jtv";
  $key_data = file_get_contents($DATA_FOLDER . "/credskey.jtv");
  $cred_data = decrypt_data(file_get_contents($filePath), $key_data);
  return $cred_data;
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
