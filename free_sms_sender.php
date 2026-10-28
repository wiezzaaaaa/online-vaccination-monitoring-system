<?php
/**
 * FREE SMS Notification System
 * Multiple free SMS APIs for appointment reminders
 */

session_start();
include 'db_connect.php';

// FREE SMS API Configuration
$SMS_CONFIG = [
    // Option 1: TextBelt (1 FREE SMS per day per IP)
    'textbelt' => [
        'url' => 'https://textbelt.com/text',
        'key' => 'textbelt', // Free quota: 1 SMS/day
        'enabled' => true
    ],
    
    // Option 2: Email-to-SMS (COMPLETELY FREE)
    'email_sms' => [
        'enabled' => true,
        'carriers' => [
            'globe' => '@txt.globe.com.ph',
            'smart' => '@sms.smart.com.ph', 
            'sun' => '@messaging.sprintpcs.com'
        ]
    ],
    
    // Option 3: Twilio Trial (Free $15 credits)
    'twilio_trial' => [
        'sid' => 'YOUR_TWILIO_SID',
        'token' => 'YOUR_TWILIO_TOKEN',
        'from' => 'YOUR_TWILIO_NUMBER',
        'enabled' => false
    ],
    
    // Option 4: Android SMS Gateway App (FREE)
    'android_gateway' => [
        'enabled' => false,
        'gateway_url' => 'http://192.168.1.100:8080/send', // Your Android device
        'password' => 'your_gateway_password'
    ]
];

/**
 * Send SMS using multiple FREE methods
 */
function sendFreeSMS($mobile, $message) {
    global $SMS_CONFIG;
    
    // Try methods in order of preference
    $methods = [
        'textbelt' => 'sendViaTextBelt',
        'email_sms' => 'sendViaEmailSMS',
        'twilio_trial' => 'sendViaTwilioTrial',
        'android_gateway' => 'sendViaAndroidGateway'
    ];
    
    foreach ($methods as $method => $function) {
        if ($SMS_CONFIG[$method]['enabled'] && function_exists($function)) {
            $result = $function($mobile, $message);
            if ($result['success']) {
                logSMS($mobile, $message, $method, 'sent', $result);
                return $result;
            }
        }
    }
    
    logSMS($mobile, $message, 'all_methods', 'failed', ['error' => 'All methods failed']);
    return ['success' => false, 'error' => 'All FREE SMS methods failed'];
}

/**
 * Method 1: TextBelt - 1 FREE SMS per day per IP
 */
function sendViaTextBelt($mobile, $message) {
    global $SMS_CONFIG;
    
    $mobile = cleanMobileNumber($mobile);
    
    $data = [
        'phone' => $mobile,
        'message' => $message,
        'key' => $SMS_CONFIG['textbelt']['key']
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $SMS_CONFIG['textbelt']['url']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        $result = json_decode($response, true);
        if (isset($result['success']) && $result['success']) {
            return [
                'success' => true,
                'method' => 'TextBelt (FREE)',
                'quota_remaining' => $result['quotaRemaining'] ?? 0,
                'response' => $result
            ];
        }
    }
    
    return ['success' => false, 'error' => 'TextBelt failed or quota exceeded'];
}

/**
 * Method 2: Email-to-SMS Gateway - COMPLETELY FREE
 */
function sendViaEmailSMS($mobile, $message) {
    global $SMS_CONFIG;
    
    $mobile_clean = preg_replace('/[^0-9]/', '', $mobile);
    
    // Detect carrier
    $carrier = detectCarrier($mobile_clean);
    if (!$carrier) {
        return ['success' => false, 'error' => 'Cannot detect carrier'];
    }
    
    $sms_email = $mobile_clean . $SMS_CONFIG['email_sms']['carriers'][$carrier];
    
    // Prepare email headers
    $subject = ""; // SMS usually don't need subject
    $headers = [
        'From: alawihao@health.local',
        'Reply-To: no-reply@health.local',
        'Content-Type: text/plain; charset=UTF-8',
        'X-Priority: 1'
    ];
    
    // Keep message under 160 characters for SMS
    $sms_message = substr($message, 0, 160);
    
    $sent = mail($sms_email, $subject, $sms_message, implode("\r\n", $headers));
    
    if ($sent) {
        return [
            'success' => true,
            'method' => 'Email-to-SMS (FREE)',
            'carrier' => $carrier,
            'email_sent_to' => $sms_email
        ];
    }
    
    return ['success' => false, 'error' => 'Email sending failed'];
}

/**
 * Method 3: Twilio Trial - $15 FREE credits
 */
function sendViaTwilioTrial($mobile, $message) {
    global $SMS_CONFIG;
    
    $config = $SMS_CONFIG['twilio_trial'];
    
    if (empty($config['sid']) || $config['sid'] == 'YOUR_TWILIO_SID') {
        return ['success' => false, 'error' => 'Twilio not configured'];
    }
    
    $mobile = cleanMobileNumber($mobile);
    
    $data = [
        'To' => $mobile,
        'From' => $config['from'],
        'Body' => $message
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.twilio.com/2010-04-01/Accounts/{$config['sid']}/Messages.json");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERPWD, $config['sid'] . ':' . $config['token']);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 201) {
        return [
            'success' => true,
            'method' => 'Twilio Trial (FREE Credits)',
            'response' => json_decode($response, true)
        ];
    }
    
    return ['success' => false, 'error' => 'Twilio failed: ' . $response];
}

/**
 * Method 4: Android SMS Gateway - COMPLETELY FREE
 */
function sendViaAndroidGateway($mobile, $message) {
    global $SMS_CONFIG;
    
    $config = $SMS_CONFIG['android_gateway'];
    $mobile = cleanMobileNumber($mobile);
    
    $data = json_encode([
        'phone' => $mobile,
        'message' => $message,
        'password' => $config['password']
    ]);
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $config['gateway_url']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        return [
            'success' => true,
            'method' => 'Android Gateway (FREE)',
            'response' => $response
        ];
    }
    
    return ['success' => false, 'error' => 'Android gateway failed'];
}

/**
 * Helper Functions
 */
function cleanMobileNumber($mobile) {
    // Remove all non-numeric characters except +
    $mobile = preg_replace('/[^0-9+]/', '', $mobile);
    
    // Convert Philippine numbers to international format
    if (substr($mobile, 0, 2) == '09') {
        $mobile = '+63' . substr($mobile, 1);
    } elseif (substr($mobile, 0, 1) == '9' && strlen($mobile) == 10) {
        $mobile = '+63' . $mobile;
    }
    
    return $mobile;
}

function detectCarrier($mobile_number) {
    // Philippine carrier detection by prefix
    $prefixes = [
        'globe' => ['917', '905', '906', '915', '916', '926', '927', '935', '936', '937', '994', '995', '996', '997'],
        'smart' => ['918', '919', '920', '921', '928', '929', '939', '946', '947', '949', '998', '999'],
        'sun' => ['922', '923', '924', '925', '931', '932', '933', '934', '943', '944', '945']
    ];
    
    $first_three = substr($mobile_number, -10, 3); // Get first 3 digits of 10-digit number
    
    foreach ($prefixes as $carrier => $carrier_prefixes) {
        if (in_array($first_three, $carrier_prefixes)) {
            return $carrier;
        }
    }
    
    return 'globe'; // Default fallback
}

function logSMS($mobile, $message, $method, $status, $response) {
    global $conn;
    
    $response_json = json_encode($response);
    $query = "INSERT INTO sms_logs (mobile_number, message, sms_type, method, status, api_response) 
              VALUES (?, ?, 'reminder', ?, ?, ?)";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("sssss", $mobile, $message, $method, $status, $response_json);
        $stmt->execute();
        $stmt->close();
    }
}

/**
 * Main SMS function
 */
function sendSMS($mobile, $message) {
    return sendFreeSMS($mobile, $message);
}

/**
 * Send reminders for tomorrow's appointments
 */
function sendScheduleReminders() {
    global $conn;
    
    $tomorrow = date('Y-m-d', strtotime('+1 day'));
    
    $query = "SELECT s.*, 
                     COALESCE(m.first_name, c.child_name) as patient_name,
                     COALESCE(u.contact_number, m.contact_number) as mobile_number
              FROM schedules s
              LEFT JOIN users u ON s.user_id = u.id
              LEFT JOIN maternal_registration m ON s.patient_id = m.id AND s.category = 'Maternal'
              LEFT JOIN children c ON s.patient_id = c.id AND s.category = 'Child'
              WHERE DATE(s.schedule_date) = ? 
              AND s.status = 'Confirmed'
              AND (u.contact_number IS NOT NULL OR m.contact_number IS NOT NULL)
              AND (s.sms_sent != 1 OR s.sms_sent IS NULL)
              LIMIT 5"; // Limit to 5 to avoid hitting quotas
    
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $tomorrow);
    $stmt->execute();
    $result = $stmt->get_result();
    
    $sent_count = 0;
    $failed_count = 0;
    $methods_used = [];
    
    while ($row = $result->fetch_assoc()) {
        $mobile = $row['mobile_number'];
        $patient_name = $row['patient_name'];
        $date = date('M j, Y', strtotime($row['schedule_date']));
        $time = date('g:i A', strtotime($row['schedule_time']));
        $service = $row['service_type'];
        
        // Short message for SMS
        $message = "Hi {$patient_name}! Reminder: {$service} tomorrow {$date} at {$time}. Alawihao Health Center. Bring health records. See you!";
        
        // Send SMS
        $sms_result = sendSMS($mobile, $message);
        
        if ($sms_result['success']) {
            // Mark as sent
            $update_query = "UPDATE schedules SET sms_sent = 1, sms_sent_at = NOW() WHERE id = ?";
            $update_stmt = $conn->prepare($update_query);
            $update_stmt->bind_param("i", $row['id']);
            $update_stmt->execute();
            
            $sent_count++;
            $methods_used[] = $sms_result['method'];
            
            echo "✅ FREE SMS sent via {$sms_result['method']} to {$mobile}\n";
        } else {
            $failed_count++;
            echo "❌ SMS failed for {$mobile}: {$sms_result['error']}\n";
        }
        
        // Delay between sends
        sleep(3);
    }
    
    return [
        'sent' => $sent_count,
        'failed' => $failed_count,
        'total_processed' => $sent_count + $failed_count,
        'methods_used' => array_unique($methods_used)
    ];
}

// API endpoints
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
    header('Content-Type: application/json');
    
    switch ($_POST['action']) {
        case 'send_reminders':
            $result = sendScheduleReminders();
            echo json_encode([
                'success' => true,
                'message' => "FREE SMS: Sent {$result['sent']}, Failed {$result['failed']}",
                'data' => $result
            ]);
            break;
            
        case 'test_sms':
            $mobile = $_POST['mobile'] ?? '';
            $message = $_POST['message'] ?? 'FREE Test SMS from Alawihao Health Center';
            
            if (!empty($mobile)) {
                $result = sendSMS($mobile, $message);
                echo json_encode($result);
            } else {
                echo json_encode(['success' => false, 'error' => 'Mobile number required']);
            }
            break;
            
        case 'check_methods':
            global $SMS_CONFIG;
            $available_methods = [];
            
            foreach ($SMS_CONFIG as $method => $config) {
                $available_methods[$method] = [
                    'enabled' => $config['enabled'],
                    'status' => $config['enabled'] ? 'Ready' : 'Disabled'
                ];
            }
            
            echo json_encode([
                'success' => true,
                'methods' => $available_methods
            ]);
            break;
    }
    exit;
}

// Test page
if (isset($_GET['test'])) {
    echo "<h2>FREE SMS Test Results</h2>";
    echo "<p><strong>Testing SMS system...</strong></p>";
    
    $result = sendScheduleReminders();
    
    echo "<div style='background:#d4edda;padding:15px;border-radius:8px;margin:10px 0;'>";
    echo "<h3>Results:</h3>";
    echo "<p>✅ Sent: {$result['sent']}</p>";
    echo "<p>❌ Failed: {$result['failed']}</p>";
    echo "<p>📱 Methods Used: " . implode(', ', $result['methods_used']) . "</p>";
    echo "</div>";
    
    if ($result['sent'] > 0) {
        echo "<p style='color:green;font-weight:bold;'>🎉 FREE SMS system is working!</p>";
    } else {
        echo "<p style='color:red;'>⚠️ No SMS sent. Check configuration or appointment schedule.</p>";
    }
}
?>