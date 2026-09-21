 <?php
session_start();
include 'db_connect.php';

if (isset($_POST['signup'])) {
    $first_name = trim($_POST['first_name']);
    $last_name  = trim($_POST['last_name']);
    $email      = trim($_POST['email']);
    $password   = $_POST['password'];
    $confirm_password = $_POST['password_confirmation'];

    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Invalid email format.'); window.location.href='signup.php';</script>";
        exit();
    }

    // Password strength check
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || !preg_match('/[0-9]/', $password) || !preg_match('/[^A-Za-z0-9]/', $password)) {
        echo "<script>alert('Password must be at least 8 characters with an uppercase letter, number, and special character.'); window.location.href='signup.php';</script>";
        exit();
    }

    if ($password !== $confirm_password) {
        echo "<script>alert('Passwords do not match!'); window.location.href='signup.php';</script>";
        exit();
    }

    $hashed = password_hash($password, PASSWORD_BCRYPT);

    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $first_name, $last_name, $email, $hashed);

    if ($stmt->execute()) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['email']   = $email;
        $stmt->close();
        header("Location: vaccines.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>