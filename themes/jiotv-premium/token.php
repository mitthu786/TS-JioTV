<?php
$creds = json_decode(file_get_contents('assets/data/creds.json'), true);
$ssoToken = $creds['ssoToken'];

function magic($str)
{
    $str = base64_encode(md5($str, true));
    return str_replace("\n", "", str_replace("\r", "", str_replace("/", "_", str_replace("+", "-", str_replace("=", "", $str)))));
}

function generateToken()
{
    global $ssoToken;
    $st = magic($ssoToken);
    $pxe = time() + 6000000;
    $jct = trim(magic("cutibeau2ic" . $st . $pxe));
    return "?jct=" . $jct . "&pxe=" . $pxe . "&st=" . $st;
}

$token = generateToken();
