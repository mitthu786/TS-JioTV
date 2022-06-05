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
    'Content-Type:application/json',
    'x-api-key: l7xx938b6684ee9e4bbe8831a9a682b8e19f',
    'app-name: RJIL_JioTV'
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
        'consumptionDeviceName' => 'samsung SM-G930F',
        'info' => array(
            'type' => 'android',
            'platform' => array(
                'name' => 'SM-G930F',
                'version' => '5.1.1'
            ),
            'androidId' => '3022048329094879'
        )
    )
);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.jio.com/v3/dip/user/unpw/verify');
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_USERAGENT, 'Dalvik/2.1.0 (Linux; U; Android 5.1.1; SM-G930F Build/LMY48Z)');
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_AUTOREFERER, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_ENCODING, 'UTF-8');
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$result = curl_exec($ch);
curl_close($ch);

$j = json_decode($result, true);

$k = $j["ssoToken"];
if ($k != "") {
    // echo $k;
    file_put_contents("assets/data/creds.json", $result);
    $sign = "LOGGED IN SUCCESSFULLY !";
} else {
    $sign = "WRONG PHONE NO. OR PASSWORDS.<br> PLEASE TRY AGAIN.";
}

?>

<html>

<head>

    <title>JIOTV LOGIN </title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel="stylesheet" href="assets/css/signin.css" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="author" content="Techie Sneh">
    <meta name="copyright" content="This Created by Techie Sneh">
    <link rel="shortcut icon" type="image/x-icon" href="https://i.ibb.co/37fVLxB/f4027915ec9335046755d489a14472f2.png">
    <meta name="robots" content="all" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">


</head>

<body>
    <div class="container">
        <div class="screen">
            <div class="screen__content">
                <form class="login" action="<?php $_PHP_SELF ?>" method="POST">
                    <div class="login__field">
                        <i class="login__icon fas fa-user"></i>
                        <input type="text" class="login__input" placeholder="Jio Number / Email" name="email">
                    </div>
                    <div class="login__field">
                        <i class="login__icon fas fa-lock"></i>
                        <input type="password" class="login__input" placeholder="Password" name="pass">
                    </div>
                    <button class="button login__submit" type="submit">
                        <span class="button__text">LogIn Now</span>
                        <i class="button__icon fas fa-chevron-right"></i>
                    </button>
                </form>
                <div class="social-login">
                    <br>
                    <h3>JIOTV LOGIN</h3>
                </div>
                <div class="copyright">
                    <br>
                    <h3>BY : TECHIESNEH</h3>
                </div>
                <div class="logsucc"><b></b><?php echo $sign; ?></b></div>
            </div>
            <div class="screen__background">
                <span class="screen__background__shape screen__background__shape3"></span>
                <span class="screen__background__shape screen__background__shape2"></span>
                <span class="screen__background__shape screen__background__shape1"></span>
            </div>
        </div>
    </div>
</body>

</html>