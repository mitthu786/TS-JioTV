<?php

// * Copyright 2021-2024 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

$DATA_FOLDER = "../assets/data";

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



function send_jio_otp($mobile)
{
  $j_otp_api = 'https://jiotvapi.media.jio.com/userservice/apis/v1/loginotp/send';
  $j_otp_headers = array('appname: RJIL_JioTV', 'os: android', 'devicetype: phone', 'content-type: application/json', 'user-agent: okhttp/3.14.9');
  $j_otp_payload = array('number' => base64_encode('+91' . $mobile));
  $process = curl_init($j_otp_api);
  curl_setopt($process, CURLOPT_POST, 1);
  curl_setopt($process, CURLOPT_POSTFIELDS, json_encode($j_otp_payload));
  curl_setopt($process, CURLOPT_HTTPHEADER, $j_otp_headers);
  curl_setopt($process, CURLOPT_HEADER, 0);
  curl_setopt($process, CURLOPT_TIMEOUT, 10);
  curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
  $j_otp_resp = curl_exec($process);
  $j_otp_info = curl_getinfo($process);
  curl_close($process);
  $j_otp_data = @json_decode($j_otp_resp, true);
  if ($j_otp_info['http_code'] == 204) {
    $resp['status'] = "success";
    $resp['user'] = "$mobile";
    $resp['message'] = "OTP Sent Successfully";
  } else {
    $resp['status'] = "error";
    $resp['user'] = "$mobile";
    if (isset($j_otp_data['message']) && !empty($j_otp_data['message'])) {
      $resp['message'] = "Jio Error - " . $j_otp_data['message'];
    } else {
      $resp['message'] = "Unknown Error Occured : Code " . $j_otp_info['http_code'];
    }
  }
  return $resp;
}

function verify_jio_otp($mobile, $otp)
{
  global $DATA_FOLDER;
  $u_name = encrypt_data($mobile, "TS-JIOTV");
  $j_otp_api = 'https://jiotvapi.media.jio.com/userservice/apis/v1/loginotp/verify';
  $j_otp_headers = [
    'appname: RJIL_JioTV',
    'os: android',
    'devicetype: phone',
    'content-type: application/json',
    'user-agent: okhttp/3.14.9'
  ];

  $j_otp_payload = [
    'number' => base64_encode('+91' . $mobile),
    'otp' => $otp,
    'deviceInfo' => [
      'consumptionDeviceName' => 'RMX1945',
      'info' => [
        'type' => 'android',
        'platform' => ['name' => 'RMX1945'],
        'androidId' => substr(sha1(time() . rand(00, 99)), 0, 16)
      ]
    ]
  ];

  $j_otp_payload_json = json_encode($j_otp_payload);

  $process = curl_init($j_otp_api);
  curl_setopt($process, CURLOPT_POST, 1);
  curl_setopt($process, CURLOPT_POSTFIELDS, $j_otp_payload_json);
  curl_setopt($process, CURLOPT_HTTPHEADER, $j_otp_headers);
  curl_setopt($process, CURLOPT_HEADER, 0);
  curl_setopt($process, CURLOPT_TIMEOUT, 10);
  curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);

  $j_otp_resp = curl_exec($process);
  $j_otp_info = curl_getinfo($process);
  curl_close($process);

  $j_otp_data = @json_decode($j_otp_resp, true);

  $resp = [
    'status' => 'error',
    'user' => $mobile,
    'message' => ''
  ];

  if (isset($j_otp_data['ssoToken']) && !empty($j_otp_data['ssoToken'])) {
    if (
      file_put_contents($DATA_FOLDER . "/creds.jtv", encrypt_data(json_encode($j_otp_data), $u_name)) &&
      file_put_contents($DATA_FOLDER . "/credskey.jtv", $u_name)
    ) {
      $resp['status'] = 'success';
      $resp['message'] = 'Jio LoggedIn Successfully';
    } else {
      $resp['message'] = 'Logged In Successfully But Failed To Save Data';
    }
  } else {
    if (isset($j_otp_data['message']) && !empty($j_otp_data['message'])) {
      $resp['message'] = 'Jio Error - ' . $j_otp_data['message'];
    } elseif (isset($j_otp_data['errors'][1]['message'])) {
      $resp['message'] = 'Jio Error - ' . $j_otp_data['errors'][1]['message'];
    } elseif (isset($j_otp_data['errors'][0]['message'])) {
      $resp['message'] = 'Jio Error - ' . $j_otp_data['errors'][0]['message'];
    } else {
      $resp['message'] = 'Unknown Error Occurred: Code ' . $j_otp_info['http_code'];
    }
  }

  return $resp;
}


function refresh_jio_token()
{
  $error = true;
  $Msg = "Unknown";
  $newAuthToken = "";
  $JIO_AUTH = json_decode(getCRED(), true);

  if (!empty($JIO_AUTH)) {
    $ref_TokenApi = "https://auth.media.jio.com/tokenservice/apis/v1/refreshtoken?langId=6";
    $ref_TokenPost = '{"appName":"RJIL_JioTV","deviceId":"' . $JIO_AUTH['deviceId'] . '","refreshToken":"' . $JIO_AUTH['refreshToken'] . '"}';
    $ref_TokenHeads = array(
      "accesstoken: " . $JIO_AUTH['authToken'],
      "uniqueId: " . $JIO_AUTH['sessionAttributes']['user']['unique'],
      "devicetype: phone",
      "versionCode: 331",
      "os: android",
      "Content-Type: application/json"
    );

    $process = curl_init($ref_TokenApi);
    curl_setopt($process, CURLOPT_POST, 1);
    curl_setopt($process, CURLOPT_POSTFIELDS, $ref_TokenPost);
    curl_setopt($process, CURLOPT_HTTPHEADER, $ref_TokenHeads);
    curl_setopt($process, CURLOPT_HEADER, 0);
    curl_setopt($process, CURLOPT_TIMEOUT, 10);
    curl_setopt($process, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($process, CURLOPT_FOLLOWLOCATION, 1);
    $ref_data = json_decode(curl_exec($process), true);
    curl_close($process);

    $resp = [
      'status' => 'error',
      'message' => '',
      'newAuthToken' => ''
    ];

    if (isset($ref_data['message']) && !empty($ref_data['message'])) {
      $resp["message"] = "JioTV [OTP Login] - AuthToken Refresh Failed";
    }

    if (isset($ref_data['authToken']) && !empty($ref_data['authToken'])) {
      $resp["status"] = "success";
      $resp["message"] = "JioTV [OTP Login] - AuthToken Refreshed Successfully";
      $resp["authToken"] = $ref_data['authToken'];
    }
  }

  return $resp;
}
