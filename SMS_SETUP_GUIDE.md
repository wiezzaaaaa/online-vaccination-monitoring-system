# 📱 SMS Notification Setup Guide

## 🎯 Complete SMS System para sa Schedule Reminders

### **What it does:**
- ✅ Auto-send SMS reminders 1 day before appointment
- ✅ Send confirmation SMS when appointment is scheduled  
- ✅ Admin interface para sa manual SMS sending
- ✅ SMS tracking and logging
- ✅ Test SMS functionality

---

## 📋 **Step 1: Database Setup**

### **Run ang SQL updates:**
```sql
-- Copy paste ito sa phpmyadmin SQL tab
-- File: sms_database_update.sql
```

**Or i-import mo yung file:**
1. Open phpMyAdmin
2. Select your FINAL_CAPSTONE database
3. Go to "Import" tab
4. Upload `sms_database_update.sql`
5. Click "Go"

---

## 🔧 **Step 2: SMS API Setup**

### **Option A: Semaphore (Philippines - Recommended)**

1. **Sign up sa Semaphore:**
   - Go to: https://semaphore.co/
   - Create account
   - Verify your account
   
2. **Get API Key:**
   - Login to dashboard
   - Go to "API" section
   - Copy your API Key
   
3. **Add credits:**
   - Minimum 100 pesos para sa testing
   - 1 SMS ≈ 2.50 pesos
   
4. **Configure sender name:**
   - Sa dashboard, set sender name: "ALAWIHAO"
   - Wait for approval (usually 1-2 days)

### **Option B: Twilio (International)**

1. Sign up sa Twilio.com
2. Get phone number
3. Get Account SID and Auth Token
4. More expensive pero mas reliable

---

## ⚙️ **Step 3: Configure ang Code**

### **Edit `send_schedule_sms.php`:**
```php
// Line 9: Replace with your actual API key
define('SMS_API_KEY', 'YOUR_ACTUAL_SEMAPHORE_API_KEY_HERE');
```

### **Test kung working:**
1. Open: `localhost/FINAL_CAPSTONE/send_schedule_sms.php?test`
2. Should see results kung may appointments tomorrow

---

## 🎛️ **Step 4: Admin Interface**

### **Access SMS Management:**
1. Login as admin
2. Go to: `localhost/FINAL_CAPSTONE/admin/sms_management.php`
3. You can:
   - Send test SMS
   - Send all reminders manually
   - View SMS logs
   - Check tomorrow's appointments

---

## 🤖 **Step 5: Automated Scheduling**

### **Windows Task Scheduler:**

1. **Open Task Scheduler**
   - Windows + R → `taskschd.msc`
   
2. **Create Basic Task**
   - Name: "Daily SMS Reminders"
   - Description: "Send daily appointment reminders"
   
3. **Trigger:**
   - Daily at 6:00 PM (or preferred time)
   - Start tomorrow
   
4. **Action:**
   - Program: `C:\xampp\php\php.exe`
   - Arguments: `C:\xampp\htdocs\FINAL_CAPSTONE\sms_cron_scheduler.php`
   
5. **Finish and Test**

### **Alternative: Manual Cron (Linux/Mac):**
```bash
# Add to crontab
0 18 * * * php /path/to/FINAL_CAPSTONE/sms_cron_scheduler.php
```

---

## 📝 **Step 6: Integration sa Existing System**

### **A. Add SMS sa Schedule Creation:**

Sa `schedule_management.php` or wherever schedules are created:
```php
// After successful schedule creation
include '../send_schedule_sms.php';
$sms_result = sendScheduleConfirmationSMS($new_schedule_id);
```

### **B. Add Mobile Number Fields:**

Sa registration forms, make sure may:
```html
<div class="form-group">
    <label>Mobile Number</label>
    <input type="text" name="contact_number" placeholder="09171234567" required>
    <small>For SMS appointment reminders</small>
</div>
```

### **C. Add SMS Status sa Schedule List:**

```php
// Sa schedule display
<?php if ($schedule['sms_sent']): ?>
    <span class="sms-status sent">✓ SMS Sent</span>
<?php else: ?>
    <span class="sms-status pending">⏳ Pending</span>  
<?php endif; ?>
```

---

## 🧪 **Step 7: Testing**

### **Test Process:**
1. **Create test appointment for tomorrow**
2. **Go to admin/sms_management.php**  
3. **Send test SMS to your number first**
4. **If working, send all reminders**
5. **Check SMS logs**

### **Test Checklist:**
- [ ] Test SMS sends to your mobile
- [ ] Appointment reminder works
- [ ] Confirmation SMS works  
- [ ] Admin interface accessible
- [ ] SMS logs updating
- [ ] Automated scheduler runs

---

## 💰 **Pricing Reference (Semaphore):**

| Volume | Price per SMS |
|--------|---------------|
| 1-1,000 | ₱2.50 |
| 1,001-5,000 | ₱2.25 |
| 5,001+ | ₱2.00 |

**Example cost:**
- 50 patients/day = ₱125/day
- Monthly ≈ ₱3,750

---

## 🔍 **Troubleshooting**

### **Common Issues:**

#### **"SMS API Error"**
- Check API key correctness
- Verify Semaphore account has credits
- Check mobile number format (+63 vs 09)

#### **"No SMS received"**  
- Check if number is Globe/Smart (some delays)
- Verify sender name is approved
- Test with different mobile network

#### **"Database errors"**
- Run the SQL updates first
- Check if sms_logs table exists
- Verify foreign key constraints

#### **"Cron not running"**
- Check Task Scheduler settings
- Verify PHP path is correct
- Check file permissions

### **Debug Mode:**
Add to `send_schedule_sms.php`:
```php
// Add after line 10
define('SMS_DEBUG', true);
if (SMS_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
}
```

---

## 📊 **Expected Results**

### **Daily Flow:**
1. **6:00 PM** - Automated script runs
2. **Finds tomorrow's appointments**
3. **Sends SMS reminders**
4. **Logs results**
5. **Updates database**

### **User Experience:**
- Patient gets confirmation SMS when appointment is booked
- Patient gets reminder SMS 1 day before
- Clear, professional messages
- Includes date, time, service type

### **Admin Benefits:**
- Dashboard showing SMS stats
- Manual override capability  
- SMS history tracking
- Cost monitoring

---

## 🎉 **Success Metrics**

**After setup:**
- ✅ Reduced no-shows by 30-50%
- ✅ Better appointment management
- ✅ Improved patient communication
- ✅ Professional image
- ✅ Time savings for staff

---

## 📞 **Support**

**If may problems:**
1. Check the troubleshooting section
2. Test with simple SMS first
3. Verify database updates ran properly
4. Check API credentials

**Message format examples:**
```
Hi Maria! Reminder: You have a Pre-natal Check-up tomorrow (Dec 15, 2024) at 9:00 AM at Alawihao Health Center. Please bring your health records and arrive 15 minutes early. Thank you! - Alawihao HC
```

**Total setup time: 30-60 minutes**
**Technical level: Intermediate**
**Dependencies: SMS API account, PHP cURL**

🚀 **Ready to reduce no-shows and improve patient communication!**