# MOPH Auth PHP Library

ไลบรารีสำหรับเชื่อมต่อ **Provider ID (MOPH Account Center)** ด้วย OAuth2
รองรับการ Login และดึงข้อมูลผู้ใช้งาน (Profile) สำหรับระบบโรงพยาบาล / หน่วยงานสาธารณสุข

---

## ✨ Features

* 🔐 Login ด้วย Health ID (OAuth2)
* 🎫 รับ Access Token (Health + Provider)
* 👤 ดึงข้อมูลผู้ใช้งาน (Profile)
* ⚡ ใช้งานง่าย (Pure PHP / รองรับทุก Framework)
* 📦 ใช้ผ่าน Composer ได้

---

## 📦 Installation

ติดตั้งผ่าน Composer:

```bash
composer require sakwerachai/moph-auth
```

---

## ⚙️ Configuration

สร้างไฟล์ config:

```php
return [
    'health_url' => 'https://uat-moph.id.th',
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri' => 'http://localhost/callback.php',

    'provider_url' => 'https://uat-provider.id.th',
    'provider_client_id' => 'YOUR_PROVIDER_CLIENT_ID',
    'provider_secret' => 'YOUR_PROVIDER_SECRET',
];
```

---

## 🚀 Usage

### 1. สร้าง Login URL

```php
use sakmobile\MophAuth\MophAuth;

$config = require 'config.php';

$auth = new MophAuth($config);

$loginUrl = $auth->getLoginUrl();

header("Location: $loginUrl");
```

---

### 2. Callback (รับ code และแลก token)

```php
use sakmobile\MophAuth\MophAuth;

$auth = new MophAuth($config);

$healthToken = $auth->getHealthToken($_GET['code']);
$providerData = $auth->getProviderToken($healthToken);

$providerToken = $providerData['data']['access_token'];
```

---

### 3. ดึงข้อมูล Profile

```php
$profile = $auth->getProfile($providerToken);

print_r($profile);
```

---

## 📊 ตัวอย่างข้อมูลที่ได้

```php
Array
(
    [hash_cid] => xxxxxxxxxxxxxxxxx
    [name_th] => สมชาย ใจดี
    [email] => example@gmail.com
    [organization] => Array(...)
    [is_director] => false
    [is_hr_admin] => true
)
```

---

## 🔐 หมายเหตุด้านความปลอดภัย

* `hash_cid` เป็น SHA256 ของเลขบัตรประชาชน (ไม่สามารถ decode ได้)
* ควรใช้ `hash_cid` เป็น User ID ในระบบ
* หลีกเลี่ยงการเก็บ CID จริง (PDPA)

---

## 🧠 Tips

### ดึงตำแหน่งจาก array ซ้อน

```php
$position = $profile['organization'][0]['position'] ?? null;
```

---

### แปลงข้อมูลให้ใช้ง่าย

```php
function normalizeProfile($p)
{
    return [
        'cid' => $p['hash_cid'] ?? null,
        'name' => $p['name_th'] ?? null,
        'position' => $p['organization'][0]['position'] ?? null,
        'is_director' => $p['is_director'] ?? 0,
        'is_hr_admin' => $p['is_hr_admin'] ?? 0,
    ];
}
```

---

## 🔄 Flow การทำงาน

```
Login → code
   ↓
Health Token
   ↓
Provider Token
   ↓
Profile
```

---

## 🛠 Requirements

* PHP >= 7.4
* cURL extension

---

## 📄 License

MIT License

---

## 👨‍💻 Author

**Sakwerachai**

---

## ⭐ Contributing

Pull requests ยินดีต้อนรับ 🙌
หากพบปัญหา สามารถเปิด issue ได้เลย

---
