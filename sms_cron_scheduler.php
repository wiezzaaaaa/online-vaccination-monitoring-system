<?php
/**
 * Automated SMS Scheduler
 * Run this file daily via cron job or Windows Task Scheduler
 * Sends appointment reminders automatically
 */

// Set timezone
date_default_timezone_set('Asia/Manila');

// Include the SMS sender
require_once 'send_schedule_sms.php';

// Log file for tracking
$log_file = 'sms_cron_log.txt';

function log_message($message) {
    global $log_file;
    $timestamp = date('Y-m-d H:i:s');
    file_put_contents($log_file, "[$timestamp] $message\n", FILE_APPEND | LOCK_EX);
}

// Main execution
try {
    log_message("Starting automated SMS reminder process");
    
    $result = sendScheduleReminders();
    
    $message = "SMS Reminders sent - Success: {$result['sent']}, Failed: {$result['failed']}, Total: {$result['total_processed']}";
    log_message($message);
    
    // Send summary to admin (optional)
    if ($result['total_processed'] > 0) {
        $admin_message = "Daily SMS Report: Sent {$result['sent']} appointment reminders. Failed: {$result['failed']}.";
        // You can send this to admin mobile number if needed
        // sendSMS('+639171234567', $admin_message); // Admin number
    }
    
    echo "Success: $message\n";
    
} catch (Exception $e) {
    $error_message = "SMS Cron Error: " . $e->getMessage();
    log_message($error_message);
    echo "Error: $error_message\n";
}

log_message("SMS reminder process completed");
?>