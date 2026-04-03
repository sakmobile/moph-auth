<?php
$config = require '../config.php';
require '../src/MophAuth.php';

$auth = new sakmobile\MophAuth\MophAuth($config);

$loginUrl = $auth->getLoginUrl();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Login - MOPH</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white shadow-xl rounded-2xl p-8 w-full max-w-md text-center">
        
        <h1 class="text-2xl font-bold text-gray-800 mb-2">
            ระบบเข้าสู่ระบบ
        </h1>
        
        <p class="text-gray-500 mb-6">
            เข้าสู่ระบบด้วย Provider ID (MOPH)
        </p>

        <a href="<?= $loginUrl ?>"
           class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-3 rounded-xl transition">
            🔐 Login with Provider ID
        </a>

        <p class="text-xs text-gray-400 mt-6">
            โรงพยาบาล / หน่วยงานสาธารณสุข
        </p>
    </div>

</body>
</html>