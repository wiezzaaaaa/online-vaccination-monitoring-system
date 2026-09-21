<?php /* theme_settings_block.php — embed inside any settings page */ ?>
<div style="margin-bottom: 28px;">
    <h3 style="font-size:1rem; font-weight:700; margin-bottom:14px; color:var(--dm-dark-sage, #5A6B47);">
        🎨 Display Mode
    </h3>
    <div class="theme-mode-group">
        <button class="theme-mode-btn" data-theme-btn="light" onclick="setTheme('light')">
            <span class="mode-icon">☀️</span> Light
        </button>
        <button class="theme-mode-btn" data-theme-btn="dark" onclick="setTheme('dark')">
            <span class="mode-icon">🌙</span> Dark
        </button>
        <button class="theme-mode-btn" data-theme-btn="system" onclick="setTheme('system')">
            <span class="mode-icon">⚙️</span> Auto
        </button>
    </div>
    <p style="font-size:0.78rem; color:var(--dm-muted,#6b7280); margin-top:10px;">
        Auto follows your system/device preference. Your choice is saved locally.
    </p>
</div>
