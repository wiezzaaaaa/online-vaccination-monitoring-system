<?php
session_start();
include 'db_connect.php'; 

// Check if the login form was submitted
if (isset($_POST['login'])) {

    // Get the email, password, and role from the form
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; // Role from dropdown

    // Use prepared statement for security
    $stmt = $conn->prepare("SELECT * FROM users WHERE email=? AND role=? LIMIT 1");
    $stmt->bind_param("ss", $email, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        if (password_verify($password, $user['password'])) {

            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['role'] = $user['role'];

            if ($role === 'admin') {
                header("Location: admin_dashboard.php"); // Admin dashboard page
                exit(); 
            } else {
                header("Location: vaccines.php"); // Go directly to vaccines page
                exit(); 
            }

        } else {
            // FAIL: Password is incorrect
            echo "<script>alert('Invalid Password'); window.location.href='login.php';</script>";
        }

    } else {
        // FAIL: No user found with that email and role
        echo "<script>alert('Invalid Email or Role'); window.location.href='login.php';</script>";
    }

} else {
    // If someone tries to access this file directly without clicking the login button
    header("Location: login.php");
    exit();
}

?>