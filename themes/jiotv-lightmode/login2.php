<?php

$u = $_GET["user"];
$password = $_GET["pass"];

if (strpos($u, "@") !== false) {
    $user = $u;
} else {
    $user = "+91" . $u;
}

$headers = array(
    "x-api-key: l7xx75e822925f184370b2e25170c5d5820a",
    "Content-Type: application/json"
);

$payload = array(
    'identifier' => "$user",
    'password' => "$password",
    'rememberUser' => 'T',
    'upgradeAuth' => 'Y',
    'returnSessionDetails' => 'T',
    'deviceInfo' => array(
        'consumptionDeviceName' => 'Jio',
        'info' => array(
            'type' => 'android',
            'platform' => array(
                'name' => 'vbox86p',
                'version' => '8.0.0'
            ),
            'androidId' => '6fcadeb7b4b10d77'
        )
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.jio.com/v3/dip/user/unpw/verify');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($ch, CURLOPT_ENCODING, "");
curl_setopt($ch, CURLOPT_TIMEOUT, 0);
$result = curl_exec($ch);
curl_close($ch);

$j = json_decode($result, true);

$k = $j["ssoToken"];
if ($k != "") {
    echo $k;
    file_put_contents("assets/creds.json", $result);
    echo "<br>";
    echo "ENJOY, LOGGED IN SUCCESSFULLY !!";
}
