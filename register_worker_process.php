<?php
session_start();
include 'db_connect.php';

if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Super Admin') {
    header('Location: login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: register_worker.php");
    exit();
}

$generated_id = trim($_POST['generated_id'] ?? '');
$first_name = trim($_POST['first_name'] ?? '');
$last_name = trim($_POST['last_name'] ?? '');
$email = trim($_POST['email'] ?? '');
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$address = trim($_POST['address'] ?? '');
$contact = trim($_POST['contact_number'] ?? '');

if ($generated_id === '' || $first_name === '' || $last_name === '' || $address === '') {
    exit('Please complete all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    exit('Please provide a valid email address.');
}

if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
    exit('Password must be at least 8 characters with an uppercase letter, number, and special character.');
}

if ($password !== $confirm_password) {
    exit('Passwords do not match.');
}

if (!preg_match('/^09[0-9]{9}$/', $contact)) {
    exit('Contact number must be 11 digits and start with 09.');
}

$check = $conn->prepare("SELECT id FROM users WHERE email = ? OR generated_id = ?
                         UNION ALL
                         SELECT worker_id AS id FROM health_workers WHERE email = ? OR generated_id = ?
                         LIMIT 1");
$check->bind_param('ssss', $email, $generated_id, $email, $generated_id);
$check->execute();
if ($check->get_result()->num_rows > 0) {
    $check->close();
    header('Location: register_worker.php?error=duplicate');
    exit();
}
$check->close();

$hashed_password = password_hash($password, PASSWORD_DEFAULT);
$conn->begin_transaction();

try {
    // Keep the account pending until a Super Admin verifies it.
    $user_stmt = $conn->prepare("INSERT INTO users (generated_id, first_name, last_name, email, password, role, status, contact_number, address, created_at) VALUES (?, ?, ?, ?, ?, 'Admin', 'Pending', ?, ?, NOW())");
    $user_stmt->bind_param('sssssss', $generated_id, $first_name, $last_name, $email, $hashed_password, $contact, $address);
    if (!$user_stmt->execute()) {
        throw new RuntimeException('User insert failed.');
    }
    $user_stmt->close();

    $notification_title = 'New Staff Registration';
    $notification_message = "{$first_name} {$last_name} submitted a staff account for approval.";
    $notification_type = 'worker_registration';
    $notification_stmt = $conn->prepare("INSERT INTO notifications (user_id, target_role, schedule_id, title, message, type, created_at) VALUES (0, 'Super Admin', 0, ?, ?, ?, NOW())");
    $notification_stmt->bind_param('sss', $notification_title, $notification_message, $notification_type);
    if (!$notification_stmt->execute()) {
        throw new RuntimeException('Notification insert failed.');
    }
    $notification_stmt->close();

    $conn->commit();
    echo "<script>alert('Worker registration submitted for Super Admin approval. ID: " . htmlspecialchars($generated_id, ENT_QUOTES) . "'); window.location.href='admin/super_admin_dashboard.php#pendingWorkersPad';</script>";
} catch (Throwable $error) {
    $conn->rollback();
    http_response_code(500);
    echo 'Worker registration failed. Please try again.';
}
?>