<?php ?>
<?php if (isset($_SESSION['user_id'])): ?>
<div style="display:inline-flex;align-items:center;gap:6px;">
    <?php
    // Determine correct path for theme_toggle relative to current file's location
    $notif_path = $notif_path ?? '';
    ?>
    <?php if (isset($_SESSION['role']) && in_array($_SESSION['role'], ['Admin', 'Super Admin'])): 
        $sms_link = ($notif_path === '../') ? 'sms_management.php' : 'admin/sms_management.php';
    ?>
    <!-- SMS Topbar Icon -->
    <a href="<?php echo $sms_link; ?>" class="notif-bell-btn" title="SMS Notifications" style="text-decoration:none;">
        <i class="fa fa-comment-sms"></i>
    </a>
    <?php endif; ?>

    <!-- Theme toggle -->
    <button class="theme-toggle-btn" onclick="cycleTheme()" title="Toggle theme" id="themeToggleBtn">
        <span id="themeIcon"></span>
    </button>

    <!-- Notification bell -->
    <div class="notif-wrapper" id="notifWrapper">
        <button class="notif-bell-btn" id="notifBellBtn" onclick="toggleNotifPanel(event)" title="Notifications">
            <i class="fa fa-bell"></i>
            <span id="notifBellBadge" style="display:none;">0</span>
        </button>
        <div class="notif-dropdown" id="notifDropdown">
            <div class="notif-dropdown-header">
                <span><i class="fa fa-bell"></i> Notifications</span>
                <button class="notif-mark-all" onclick="markAllRead()">
                    <i class="fa fa-check-double"></i> Mark all read
                </button>
            </div>
            <div class="notif-list" id="notifList">
                <div class="notif-empty">
                    <i class="fa fa-bell-slash"></i>
                    <p>No notifications yet</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>
