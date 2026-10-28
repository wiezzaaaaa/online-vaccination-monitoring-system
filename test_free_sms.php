<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FREE SMS Tester | Alawihao</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: #f8fffb; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; }
        .card { background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.06); margin-bottom: 20px; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 600; color: #5A6B47; }
        input, textarea { width: 100%; padding: 12px; border: 1px solid #edf2ed; border-radius: 8px; font-size: 1rem; }
        .btn { background: #5A6B47; color: white; border: none; padding: 12px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; }
        .btn:hover { background: #6B8E55; }
        .result { padding: 15px; border-radius: 8px; margin-top: 20px; }
        .success { background: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
        .error { background: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }
        .info { background: #d1ecf1; color: #0c5460; border: 1px solid #bee5eb; }
        .method-status { display: inline-block; margin: 5px; padding: 5px 10px; border-radius: 5px; font-size: 0.8rem; }
        .enabled { background: #d4edda; color: #155724; }
        .disabled { background: #f8d7da; color: #721c24; }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <h1>🆓 FREE SMS Testing Center</h1>
        <p>Test different FREE SMS methods for appointment reminders</p>
        
        <!-- Method Status -->
        <h3>📱 Available FREE Methods:</h3>
        <div style="margin-bottom: 20px;">
            <span class="method-status enabled">✅ TextBelt (1/day FREE)</span>
            <span class="method-status enabled">✅ Email-to-SMS (Unlimited FREE)</span>
            <span class="method-status disabled">⚠️ Twilio Trial (Setup required)</span>
            <span class="method-status disabled">⚠️ Android Gateway (Setup required)</span>
        </div>
        
        <!-- Test Form -->
        <form method="POST" style="margin-top: 30px;">
            <div class="form-group">
                <label for="mobile">Your Mobile Number</label>
                <input type="text" id="mobile" name="mobile" placeholder="09171234567" required>
                <small style="color: #666;">Format: 09xxxxxxxxx (Philippines)</small>
            </div>
            
            <div class="form-group">
                <label for="message">Test Message</label>
                <textarea id="message" name="message" placeholder="Type your test message here..." required>FREE SMS test from Alawihao Health Center - This is working! 🎉</textarea>
                <small style="color: #666;">Keep under 160 characters for best results</small>
            </div>
            
            <button type="submit" name="send_test" class="btn">📱 Send FREE Test SMS</button>
        </form>
        
        <!-- Results -->
        <?php
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_test'])) {
            include 'free_sms_sender.php';
            
            $mobile = $_POST['mobile'];
            $message = $_POST['message'];
            
            echo "<div class='info'>";
            echo "<h4>🧪 Testing SMS to: $mobile</h4>";
            echo "<p><strong>Message:</strong> $message</p>";
            echo "<p><strong>Length:</strong> " . strlen($message) . " characters</p>";
            echo "</div>";
            
            $result = sendSMS($mobile, $message);
            
            if ($result['success']) {
                echo "<div class='result success'>";
                echo "<h4>✅ SUCCESS!</h4>";
                echo "<p><strong>Method Used:</strong> {$result['method']}</p>";
                echo "<p>SMS should arrive within 1-5 minutes.</p>";
                if (isset($result['quota_remaining'])) {
                    echo "<p><strong>Quota Remaining:</strong> {$result['quota_remaining']}</p>";
                }
                echo "</div>";
            } else {
                echo "<div class='result error'>";
                echo "<h4>❌ FAILED</h4>";
                echo "<p><strong>Error:</strong> {$result['error']}</p>";
                echo "<p>Try again or check your mobile number format.</p>";
                echo "</div>";
            }
        }
        ?>
    </div>
    
    <!-- Schedule Test -->
    <div class="card">
        <h2>📅 Schedule Reminder Test</h2>
        <p>Test automatic reminders for tomorrow's appointments</p>
        
        <form method="POST">
            <button type="submit" name="test_reminders" class="btn">🔔 Send Tomorrow's Reminders</button>
        </form>
        
        <?php
        if (isset($_POST['test_reminders'])) {
            include 'free_sms_sender.php';
            
            echo "<div class='info'>";
            echo "<h4>🔍 Checking tomorrow's appointments...</h4>";
            echo "</div>";
            
            $result = sendScheduleReminders();
            
            echo "<div class='result " . ($result['sent'] > 0 ? 'success' : 'info') . "'>";
            echo "<h4>📊 Reminder Results:</h4>";
            echo "<p><strong>SMS Sent:</strong> {$result['sent']}</p>";
            echo "<p><strong>Failed:</strong> {$result['failed']}</p>";
            echo "<p><strong>Total Processed:</strong> {$result['total_processed']}</p>";
            if (!empty($result['methods_used'])) {
                echo "<p><strong>Methods Used:</strong> " . implode(', ', $result['methods_used']) . "</p>";
            }
            
            if ($result['sent'] == 0 && $result['failed'] == 0) {
                echo "<p>No appointments scheduled for tomorrow, or all reminders already sent.</p>";
            }
            echo "</div>";
        }
        ?>
    </div>
    
    <!-- Instructions -->
    <div class="card">
        <h2>📋 Quick Instructions</h2>
        
        <h4>🎯 To Enable More Methods:</h4>
        <ol>
            <li><strong>Twilio Trial:</strong> Sign up at twilio.com for $15 FREE credits</li>
            <li><strong>Android Gateway:</strong> Install "SMS Gateway API" app on your phone</li>
            <li><strong>Email-to-SMS:</strong> Already enabled and unlimited!</li>
        </ol>
        
        <h4>📱 Supported Carriers:</h4>
        <ul>
            <li>Globe: Auto-detected</li>
            <li>Smart: Auto-detected</li>
            <li>Sun: Auto-detected</li>
        </ul>
        
        <h4>💰 Daily FREE Limits:</h4>
        <ul>
            <li>TextBelt: 1 SMS per day</li>
            <li>Email-to-SMS: Unlimited</li>
            <li>Twilio Trial: 200+ SMS total</li>
            <li>Android Gateway: Unlimited</li>
        </ul>
        
        <div style="background: #f0f5ec; padding: 15px; border-radius: 8px; margin-top: 15px;">
            <strong>💡 Pro Tip:</strong> The system tries multiple methods automatically, so you get maximum free SMS coverage!
        </div>
    </div>
</div>

</body>
</html>