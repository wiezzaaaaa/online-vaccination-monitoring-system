# 📋 INTEGRATION GUIDE - Notification System & Theme Toggle

## Mga Ginawa at Paano I-integrate

### 🔔 Notification System Files

Ito ang mga files na kailangan para gumana ang notification system:

```
ROOT FOLDER (c:\xampp\htdocs\Capstone\):
├── notif_assets.php          ← CSS + Theme engine (include sa <head>)
├── notif_bell.php            ← Bell icon UI (include sa topbar)
├── notif_js.php              ← JavaScript logic (include bago </body>)
├── get_notifications.php     ← API: Fetch notifications
├── mark_notifications_read.php ← API: Mark as read
├── theme.js                  ← Theme switcher logic
└── theme.css                 ← Theme colors
```

---

## 🎯 STEP-BY-STEP: Paano I-add sa Pages

### Para sa ROOT PAGES (user_*.php, main_user.php, etc.)

#### 1️⃣ **SA <HEAD> SECTION** - I-add ang CSS + Theme:

```php
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Page Title</title>
    
    <!-- 👇 I-add ito BEFORE </head> -->
    <?php $notif_path=''; include 'notif_assets.php'; ?>
</head>
```

**Bakit `$notif_path=''`?** 
- Kasi nasa ROOT ka, so DIRECT access lang sa files (walang ../)

---

#### 2️⃣ **SA TOPBAR** - I-add ang Bell Icon:

```php
<div class="topbar">
    <div class="topbar-brand">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>
        <img src="image/logo.png" alt="Logo" class="logo-img">
        <span class="page-label">Page Title</span>
    </div>
    
    <!-- 👇 I-add ang notification bell dito -->
    <?php include 'notif_bell.php'; ?>
</div>
```

**NOTE:** Ang `notif_bell.php` ay may theme toggle button + notification bell

---

#### 3️⃣ **BAGO </BODY>** - I-add ang JavaScript:

```php
<script>
    // Your existing scripts here
</script>

<!-- 👇 I-add ito BEFORE </body> -->
<?php $notif_path=''; include 'notif_js.php'; ?>

</body>
</html>
```

---

### Para sa ADMIN PAGES (admin/*.php)

#### 1️⃣ **SA <HEAD> SECTION**:

```php
<head>
    <meta charset="UTF-8">
    <title>Admin Page</title>
    
    <!-- 👇 Notice: may '../' kasi nasa admin/ folder -->
    <?php $notif_path='../'; include '../notif_assets.php'; ?>
</head>
```

**Bakit `$notif_path='../'`?**
- Kasi nasa `admin/` folder ka
- Kailangan UMAKYAT ng 1 level (`../`) para ma-access ang root files

---

#### 2️⃣ **SA TOPBAR**:

```php
<div class="topbar">
    <div class="topbar-left">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>
        <img src="../image/logo.png" alt="Logo" class="logo-img">
        <span class="page-label">Admin Page</span>
    </div>
    
    <!-- 👇 I-add ang notification bell -->
    <?php include '../notif_bell.php'; ?>
</div>
```

**IMPORTANT:** Notice `../image/logo.png` at `../notif_bell.php` (may ../)

---

#### 3️⃣ **BAGO </BODY>**:

```php
<?php include 'footer.php'; ?>

<!-- 👇 I-add ito -->
<?php $notif_path='../'; include '../notif_js.php'; ?>

</body>
</html>
```

---

## 📁 File Path Reference

### ROOT FILES → ROOT RESOURCES:
```php
include 'notif_assets.php';      ✅ Tama
include 'notif_bell.php';        ✅ Tama
include 'notif_js.php';          ✅ Tama
src="image/logo.png"             ✅ Tama
src="theme.js"                   ✅ Tama
```

### ADMIN FILES → ROOT RESOURCES:
```php
include '../notif_assets.php';   ✅ Tama (umakyat ng 1 level)
include '../notif_bell.php';     ✅ Tama
include '../notif_js.php';       ✅ Tama
src="../image/logo.png"          ✅ Tama
src="../theme.js"                ✅ Tama
```

### ADMIN FILES → ADMIN RESOURCES:
```php
include 'admin_sidebar.php';     ✅ Tama (same folder)
include 'footer.php';            ✅ Tama
```

### ROOT FILES → DB CONNECTION:
```php
include 'db_connect.php';        ✅ Tama (same level)
```

### ADMIN FILES → DB CONNECTION:
```php
include '../db_connect.php';     ✅ Tama (umakyat ng 1)
```

---

## 🎨 Theme System

Ang theme system ay automatic na! Pag nag-include ka ng `notif_assets.php`, kasama na ang:
- ✅ Theme toggle button (light/dark/auto)
- ✅ Automatic theme persistence (naka-save sa localStorage)
- ✅ CSS variables na nag-switch based sa theme

**Walang kailangan gawin** - it just works!

---

## ✅ Checklist: Ano ang Kailangan Checked?

### Para sa BAWAT NEW PAGE na gagawin:

- [ ] May `<?php $notif_path=''; include 'notif_assets.php'; ?>` sa `<head>`
- [ ] May `<?php include 'notif_bell.php'; ?>` sa topbar area
- [ ] May `<?php $notif_path=''; include 'notif_js.php'; ?>` bago `</body>`
- [ ] Tama ang path: `''` for root, `'../'` for admin folder

### Para sa ADMIN PAGES:

- [ ] May `<?php $notif_path='../'; include '../notif_assets.php'; ?>` sa `<head>`
- [ ] May `<?php include '../notif_bell.php'; ?>` sa topbar
- [ ] May `<?php $notif_path='../'; include '../notif_js.php'; ?>` bago `</body>`
- [ ] Logo path: `../image/logo.png` (with ../)

---

## 🐛 Common Issues at Solutions

### Issue 1: "File not found" error
**Problem:** Hindi mahanap ang notif_assets.php  
**Solution:** Check mo kung tama ang `$notif_path`:
```php
// ROOT pages:
$notif_path = '';

// ADMIN pages:
$notif_path = '../';
```

---

### Issue 2: Notification bell walang style
**Problem:** CSS hindi nag-load  
**Solution:** Siguraduhing naka-include ang `notif_assets.php` sa `<head>`

---

### Issue 3: Notifications hindi nag-fetch
**Problem:** JavaScript error  
**Solution:** 
1. Check kung naka-include ang `notif_js.php` bago `</body>`
2. Tignan sa browser console (F12) kung may errors
3. Check kung logged in ang user (`$_SESSION['user_id']`)

---

### Issue 4: Theme toggle hindi gumagana
**Problem:** `theme.js` not loaded  
**Solution:** Kasama na ang `theme.js` sa `notif_assets.php`, so no need to manually include

---

## 📊 Database Requirements

Ang notification system ay nangangailangan ng `notifications` table:

```sql
CREATE TABLE IF NOT EXISTS `notifications` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `target_role` enum('User','Admin','Super Admin') NOT NULL DEFAULT 'User',
  `schedule_id` int(11) DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `schedule_id` (`schedule_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

**NOTE:** Kung wala pa ang table, i-import ang SQL file

---

## 🎯 Quick Reference: Complete Template

### ROOT PAGE TEMPLATE (user_*.php):

```php
<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="fil">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Title | Alawihao Health Center</title>
    
    <!-- Notification CSS + Theme -->
    <?php $notif_path=''; include 'notif_assets.php'; ?>
    
    <style>
        /* Your custom CSS here */
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar-container">
    <?php include 'user_sidebar.php'; ?>
</div>

<!-- Topbar -->
<div class="topbar">
    <div class="topbar-brand">
        <button class="hamburger-btn" onclick="toggleSidebar()">
            <i class="fa fa-bars"></i>
        </button>
        <img src="image/logo.png" alt="Logo" class="logo-img">
        <span class="page-label">Page Title</span>
    </div>
    <?php include 'notif_bell.php'; ?>
</div>

<!-- Main Content -->
<div id="main">
    <h1>Your Content Here</h1>
</div>

<script>
    function toggleSidebar() {
        document.body.classList.toggle('sidebar-closed');
    }
</script>

<!-- Notification JavaScript -->
<?php $notif_path=''; include 'notif_js.php'; ?>

</body>
</html>
```

---

### ADMIN PAGE TEMPLATE (admin/*.php):

```php
<?php
session_start();
include '../db_connect.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: ../login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Page | Alawihao</title>
    
    <!-- Notification CSS + Theme -->
    <?php $notif_path='../'; include '../notif_assets.php'; ?>
    
    <style>
        /* Your custom CSS here */
    </style>
</head>
<body>

<!-- Sidebar -->
<?php include 'admin_sidebar.php'; ?>

<!-- Topbar -->
<div class="topbar">
    <button class="hamburger-btn" onclick="toggleSidebar()">
        <i class="fa fa-bars"></i>
    </button>
    <img src="../image/logo.png" alt="Logo" class="logo-img">
    <span class="page-label">Admin Page</span>
    
    <?php include '../notif_bell.php'; ?>
</div>

<!-- Main Content -->
<div id="main">
    <h1>Admin Content</h1>
</div>

<?php include 'footer.php'; ?>

<!-- Notification JavaScript -->
<?php $notif_path='../'; include '../notif_js.php'; ?>

</body>
</html>
```

---

## 📞 Need Help?

Kung may problema ka:
1. Check ang browser console (F12 → Console tab)
2. Check kung tama ang file paths
3. Tignan kung logged in ang user
4. Verify kung may `notifications` table sa database

---

## ✨ Summary

**Tatlong files lang ang kailangan i-include:**

1. `notif_assets.php` - sa `<head>` (CSS + Theme)
2. `notif_bell.php` - sa topbar (UI)
3. `notif_js.php` - bago `</body>` (JavaScript)

**Remember ang `$notif_path`:**
- Root pages: `$notif_path = '';`
- Admin pages: `$notif_path = '../';`

**That's it!** Simple lang. Copy-paste lang ang templates sa taas! 🎉
