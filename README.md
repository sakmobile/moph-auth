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
composer require sakmobile/moph-auth
```

---

## ⚙️ Configuration

สร้างไฟล์ config:

```php
return [
    'health_url' => 'https://moph.id.th',
    'client_id' => 'YOUR_CLIENT_ID',
    'client_secret' => 'YOUR_CLIENT_SECRET',
    'redirect_uri' => 'http://localhost/callback.php',

    'provider_url' => 'https://provider.id.th',
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

---

## ❤️ สนับสนุนค่ากาแฟ

หาก Library นี้ช่วยให้งานของคุณง่ายขึ้น ☕
สามารถสนับสนุนผู้พัฒนาเพื่อเป็นกำลังใจในการพัฒนาต่อได้ครับ

> 🙏 ทุกการสนับสนุนมีความหมายมาก

**ช่องทางสนับสนุน**

* 💸 PromptPay: `0991013326`
* 📱 หรือช่องทางอื่น ๆ ตามสะดวก

---

## 🛒 บริการ / ติดต่อพัฒนาเพิ่มเติม

<!--หากต้องการ:

* เชื่อมต่อระบบ **HIS / HOSxP / JHCIS**
* ทำระบบ **SSO Login ทั้งองค์กร**
* เชื่อมต่อ **WiFi Login / Captive Portal / Firewall (Fortigate)**
* พัฒนาระบบเฉพาะสำหรับโรงพยาบาล
-->
สามารถติดต่อได้ที่:

* 🌐 GitHub: https://github.com/sakmobile/moph-auth
* 📧 Email: [sak.janenii@email.com](mailto:sak.janenii@email.com)
* 💬 Line: yourlineid

---

## 🤝 สำหรับพี่น้อง IT โรงพยาบาล

โปรเจคนี้จัดทำขึ้นเพื่อ:

> 🎯 **แบ่งปันความรู้ และช่วยลดภาระงานของเพื่อน ๆ พี่ ๆ IT โรงพยาบาล**

สามารถ:

* ✅ นำไปใช้งานได้ฟรี
* ✅ แก้ไข / ปรับปรุงได้
* ✅ ต่อยอดได้เต็มที่

ภายใต้ MIT License 🎉

---

## 📢 หมายเหตุเพิ่มเติม

* Library นี้เป็น **Open Source**
* ไม่มีค่าใช้จ่ายในการใช้งาน
* หากพบปัญหา หรืออยากให้เพิ่ม feature สามารถเปิด Issue ได้เลย

---

## ⭐ ฝากกด Star ให้กำลังใจ

หากโปรเจคนี้มีประโยชน์
ฝากกด ⭐ บน GitHub เพื่อเป็นกำลังใจด้วยนะครับ 🙏

👉 https://github.com/sakmobile/moph-auth

---

