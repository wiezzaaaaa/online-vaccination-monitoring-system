# 📦 COMPLETE FILE LIST - I-Send mo LAHAT ito sa Team

## 🔥 **CORE SYSTEM FILES** (PRIORITY 1)
*These are the main theme & notification system files*

```
📁 ROOT FOLDER (c:\xampp\htdocs\Capstone\):
✅ theme.js                    ← Theme switcher (light/dark)
✅ theme.css                   ← Dark mode CSS variables  
✅ notif_assets.php            ← CSS + Theme loader
✅ notif_bell.php              ← Notification bell UI
✅ notif_js.php                ← Notification JavaScript
✅ get_notifications.php       ← API: Fetch notifications
✅ mark_notifications_read.php ← API: Mark as read
✅ check_reminders.php         ← Auto schedule reminders
```

---

## 👤 **ALL USER PAGES** (PRIORITY 2) 
*All user_*.php files with theme support & fixed paths*

### **Main User Pages:**
```
✅ user_dashboard.php          ← Fixed hamburger + has custom notification CSS
✅ user_settings.php           ← Fixed hamburger + logo path + theme support
✅ user_profile.php            ← Fixed hamburger + logo path + theme support
✅ user_schedule.php           ← Has complete theme support
✅ user_sidebar.php            ← Removed duplicate hamburger, fixed structure
✅ health_article.php          ← Fixed hamburger button + correct paths
```

### **Registration Pages:**
```
✅ user_maternal_reg.php       ← Has theme support via notif_assets.php
✅ user_infant_reg.php         ← Has theme support via notif_assets.php
✅ user_reg_newborn.php        ← Check if updated
✅ user_reg_pregnancy.php      ← Check if updated
```

### **Records Pages:**  
```
✅ user_maternal_records.php   ← Has theme support via notif_assets.php
✅ user_child_records.php      ← Has theme support via notif_assets.php
✅ user_history.php            ← Has theme support via notif_assets.php
✅ user_infant_hr.php          ← Has theme support via notif_assets.php
```

---

## 👨‍💼 **ALL ADMIN PAGES** (PRIORITY 3)
*All admin/*.php files with theme support & fixed paths*

### **Main Admin Pages:**
```
✅ admin/admin_settings.php       ← Fixed logo path + theme support
✅ admin/super_admin_setting.php  ← Fixed logo path + theme support  
✅ admin/admin_profile.php        ← Has theme support
✅ admin/admin_dashboard.php      ← Check if has theme support
✅ admin/super_admin_dashboard.php ← Check if has theme support
```

### **Schedule Management:**
```
✅ admin/schedule_management.php     ← Has notification + theme system
✅ admin/admin_sched_maternal.php    ← Has theme support
✅ admin/admin_sched_child.php       ← Has theme support
```

### **Health & Records Management:**
```
✅ admin/admin_health_workers.php    ← Has theme support
✅ admin/vaccine_inventory.php       ← Has theme support
✅ admin/admin_maternal_reg.php      ← Has theme support
✅ admin/admin_maternal_hr.php       ← Has theme support
✅ admin/admin_maternal_history.php  ← Has theme support
✅ admin/admin_child_rec.php         ← Has theme support
✅ admin/admin_child_hr.php          ← Has theme support
✅ admin/admin_child_history.php     ← Has theme support
```

### **System & Logs:**
```
✅ admin/admin_approval.php          ← Has theme support
✅ admin/activity_logs.php           ← Has theme support
```

### **Sidebar Files:**
```
✅ admin/admin_sidebar.php           ← Fixed logo paths (../image/logo.png)
✅ admin/super_admin_sidebar.php     ← Fixed logo paths (../image/logo.png)
```

### **Footer & Common:**
```
✅ admin/footer.php                  ← Has smart logo path detection
```

---

## 📄 **DOCUMENTATION FILES** (PRIORITY 4)
```
✅ INTEGRATION_GUIDE.md         ← Complete step-by-step guide
✅ FILES_TO_SEND.md             ← Detailed file list with explanations
✅ SEND_TO_TEAM.txt             ← Simple checklist
✅ COMPLETE_SEND_LIST.md        ← This file (comprehensive list)
```

---

## 🖼️ **IMAGE FILES NEEDED** (PRIORITY 5)
*Make sure these exist in image/ folder*
```
📁 image/ folder:
✅ logo.png          ← Main logo (NOT logo.jpg)
❓ light.png         ← Light mode icon (check if exists)
❓ darkmode.png      ← Dark mode icon (check if exists)
✅ bell.png          ← Notification bell icon
✅ clock.png         ← Clock icon (for articles)
✅ greenbook.png     ← Book icon (for articles)
✅ location.png      ← Location icon
✅ shield.png        ← Shield/security icon
```

---

## 🗂️ **OTHER SUPPORT FILES**
*Files that may have been updated*
```
❓ main_user.php              ← Check if updated
❓ opening.php                ← Check if updated  
❓ index.php                  ← Check if updated
❓ login.php                  ← Check if updated
❓ logout.php                 ← Check if updated
❓ maternal_reg.php           ← Check if updated
```

---

## 📊 **DATABASE REQUIREMENTS**
```sql
-- Make sure this table exists:
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

---

## 🎯 **WHAT TO TELL YOUR TEAM:**

### **Step 1: Copy ALL Files** (10 minutes)
- Copy **ALL files** from Priority 1-3 above
- Replace existing files completely
- Keep same folder structure

### **Step 2: Check Images** (2 minutes)  
- Make sure `image/logo.png` exists
- Create `image/light.png` and `image/darkmode.png` if missing
- You can find sample icons online or use simple sun/moon icons

### **Step 3: Database Setup** (3 minutes)
- Check if `notifications` table exists
- If not, run the SQL above
- No changes needed sa existing tables

### **Step 4: Test** (5 minutes)
1. Open any user page
2. Look for sun/moon icon (theme toggle)  
3. Click to switch dark/light mode
4. Check hamburger button works
5. Check notification bell appears
6. No console errors (F12)

---

## 🔢 **TOTAL COUNT:**

- **Core files:** 8 files
- **User pages:** ~12 files  
- **Admin pages:** ~20 files
- **Documentation:** 4 files
- **Images:** 8 files needed

**TOTAL: ~50 files** to copy

---

## ⚠️ **IMPORTANT NOTES:**

### **Files that MUST be copied:**
1. **ALL files with `notif_assets.php`** - these have theme support
2. **ALL files with fixed hamburger buttons** - UI improvements
3. **ALL files with `../image/logo.png`** - fixed logo paths
4. **ALL core system files** - theme.js, notif_*.php files

### **Files na pwedeng skip (kung walang time):**
- Files na walang theme support yet
- Old backup files  
- Test files

### **Priority Order:**
1. **Core system** (theme.js, notif_*.php) - MUST HAVE
2. **Updated user pages** - HIGH PRIORITY  
3. **Updated admin pages** - HIGH PRIORITY
4. **Documentation** - NICE TO HAVE
5. **Image files** - NEEDED for icons

---

## 🚨 **QUICK CHECK:**

**Before sending, make sure na:**
- [ ] May complete core system files (8 files)
- [ ] May updated user_*.php files  
- [ ] May updated admin/*.php files
- [ ] May documentation files
- [ ] May required image files

**After team receives:**
- [ ] Theme toggle visible on pages
- [ ] Dark/light mode switching works
- [ ] Hamburger buttons working  
- [ ] No broken images
- [ ] No console errors

---

**🎉 READY TO SEND! All ~50 files para sa complete system!**