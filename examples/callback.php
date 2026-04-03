<?php
session_start();
$config = require '../config.php';
require '../src/MophAuth.php';

$auth = new sakmobile\MophAuth\MophAuth($config);

try {

    if (!isset($_GET['code'])) {
        throw new Exception("No code");
    }

    // ✅ Step 1: Health Token
    $healthToken = $auth->getHealthToken($_GET['code']);

   // Step 2: Provider Token
    $providerData = $auth->getProviderToken($healthToken);

    // ✅ FIX ตรงนี้
    if (!isset($providerData['data']['access_token'])) {
        throw new Exception("Provider Token Invalid: " . json_encode($providerData));
    }

    $providerToken = $providerData['data']['access_token'];

    // Step 3: Profile
    $profile = $auth->getProfile($providerToken);

    // หลังได้ profile
    $_SESSION['user'] = $profile;

    // redirect ไปหน้า profile
    header("Location: profile.php");
    exit;

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}