<?php

// * Copyright 2021-2023 SnehTV, Inc.
// * Licensed under MIT (https://github.com/mitthu786/TS-JioTV/blob/main/LICENSE)
// * Created By : TechieSneh

include "functions.php";
$user = isset($_GET["user"]) ? trim($_GET["user"]) : "";
$password = isset($_GET["pass"]) ? $_GET["pass"] : "";

if (empty($user) || empty($password)) {
    die("Username and password are required.<br><br># USAGE :- <br>1. With Mobile No. : login_direct.php?user=6287******&pass=Ram******<br>2. With Email ID : login_direct.php?user=hello@gmail.com&pass=Ram******<br>");
}

if (strpos($user, "@") !== false) {
    $nUser = $user;
} else {
    $nUser = "+91" . $user;
}

$payload = [
    'identifier' => $nUser,
    'password' => $password,
    'rememberUser' => 'T',
    'upgradeAuth' => 'Y',
    'returnSessionDetails' => 'T',
    'deviceInfo' => [
        'consumptionDeviceName' => 'SM-G935FD',
        'info' => [
            'type' => 'android',
            'platform' => [
                'name' => 'SM-G935FD',
                'version' => '8.0.0'
            ],
            'androidId' => '3c6d6b5702fa09bd'
        ]
    ]
];

$headers = [
    "x-api-key: l7xx75e822925f184370b2e25170c5d5820a",
    "Content-Type: application/json"
];

$options = [
    'http' => [
        'header' => implode("\r\n", $headers),
        'method' => 'POST',
        'content' => json_encode($payload),
        'timeout' => 10,
    ],
];

$context = stream_context_create($options);
$url = 'https://api.jio.com/v3/dip/user/unpw/verify';
$result = file_get_contents($url, false, $context);

if ($result === false) {
    die("Request Error: " . error_get_last()['message']);
}

$responseData = json_decode($result, true);
$ssoToken = $responseData["ssoToken"] ?? "";

if (!empty($ssoToken)) {
    $u_name = encrypt_data($nUser, "TS-JIOTV");
    file_put_contents("assets/data/credskey.jtv", $u_name);
    $j_data = encrypt_data($result, $u_name);
    file_put_contents("assets/data/creds.jtv", $j_data);
    echo 'ENJOY, LOGGED IN SUCCESSFULLY. <a href="../" style="text-decoration:none;color:green;">WATCH NOW</a>';
    exit();
} else {
    $message = "OOPS !! LOGIN FAILED. <a href='./login.php' style='text-decoration:none;color:red;'>UI LOGIN</a>";
    echo $message;
    echo "<script>alert('$message');</script>";
}
