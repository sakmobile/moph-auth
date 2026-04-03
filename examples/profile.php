<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['user'];

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>Profile</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <div class="max-w-2xl mx-auto mt-10">
        
        <div class="bg-white shadow-lg rounded-2xl p-6">
            
            <h1 class="text-2xl font-bold text-gray-800 mb-4">
                👤 ข้อมูลผู้ใช้งาน
            </h1>

            <div class="space-y-3">

                <div>
                    <span class="font-semibold">CID (Hash):</span>
                    <?= $user['hash_cid'] ?? '-' ?>
                </div>

                <div>
                    <span class="font-semibold">ชื่อ:</span>
                    <?= $user['title_th'] ?? '-' ?> <?= $user['name_th'] ?? '-' ?>
                </div>
                <div>
                    <span class="font-semibold">Name ENG:</span>
                     <?= $user['title_en'] ?? '-' ?> <?= $user['name_eng'] ?? '-' ?>
                </div>
                <div>
                    <span class="font-semibold">Email:</span>
                    <?= $user['email'] ?? '-' ?>
                </div>
                <div>
                    <span class="font-semibold">วัน-เดือน-ปี เกิด:</span>
                    <?= $user['date_of_birth'] ?? '-' ?>
                </div>

                <div>
                    <span class="font-semibold">Provider ID:</span>
                    <?= $user['provider_id'] ?? '-' ?>
                </div>

                <div>
                    <span class="font-semibold">ตำแหน่ง:</span>
                    <?= $user['organization'][0]['position'] ?? '-' ?>
                </div>

                <div>
                    <span class="font-semibold">Director:</span>
                    <?= ($user['is_director'] ?? 0) ? 'ใช่' : 'ไม่ใช่' ?>
                </div>

                <div>
                    <span class="font-semibold">HR Admin:</span>
                    <?= ($user['organization'][0]['is_hr_admin'] ?? 0) ? 'ใช่' : 'ไม่ใช่' ?>
                </div>
                <div>
                    <span class="font-semibold">รหัสหน่วยงาน 9 หลัก:</span>
                    <?= $user['organization'][0]['hcode9'] ?? '-' ?>
                    </div>
                <div>
                    <span class="font-semibold">หน่วยงาน:</span>
                    <?= $user['organization'][0]['hname_th'] ?? '-' ?> (<?= $user['organization'][0]['hcode'] ?? '-' ?>)
                </div>

            </div>

            <a href="logout.php"
               class="mt-6 inline-block bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg">
                Logout
            </a>

        </div>
    </div>

</body>
</html>