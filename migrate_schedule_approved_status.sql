-- Run once against the existing FINAL_CAPSTONE database (for example, in phpMyAdmin).
ALTER TABLE `schedules`
MODIFY `status` ENUM('Pending','Approved','Completed','Cancelled','Reschedule Requested') DEFAULT 'Pending';