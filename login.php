<?php
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $u = $_POST['email'];

    if (strpos($u, "@") !== false) {
        $user = $u;
    } else {
        $user = "+91" . $u;
    }

    $pass = $_POST['pass'];
}

$headers = array(
    "x-api-key: l7xx75e822925f184370b2e25170c5d5820a",
    "Content-Type: application/json"
);

$username = $user;
$password = $pass;

$payload = array(
    'identifier' => "$username",
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
    file_put_contents("assets/data/creds.json", $result);
    $sign = "LOGGED IN SUCCESSFULLY !";
} else {
    $sign = "WRONG USERID OR PASS<br> PLEASE TRY AGAIN";
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>JIO LOGIN</title>
    <link rel="stylesheet" href="assets/css/tslogin.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="Techie Sneh">
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png">
    <meta name="description" content="ENJOY FREE LIVE JIOTV">
    <meta name="keywords" content="JIOTV, LIVETV, SPORTS, MOVIES, MUSIC">
    <meta name="copyright" content="This Created by Techie Sneh">
</head>

<body>
    <div class="container">
        <div class="form">
            <form action="<?php $_PHP_SELF ?>" method="POST">
                <h1>JIO LOGIN</h1>
                <label>Jio Number / Email</label>
                <input type="text" name="email" id="" placeholder="Jio Number / Email" />
                <label>Password</label>
                <input type="password" name="pass" id="" placeholder="Password" />
                <input type="submit" value="LogIn Now" />
                <label id="forgotpwd"><?php echo $sign; ?></label>
                <label id="forgotpwd">OTP LOGIN ? <a href="http://jiologin.unaux.com/otp.php">Click Here</a></label>
            </form>
        </div>
    </div>
</body>

</html>