<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
require 'config/db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fullname = trim($_POST['fullname']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $role = 'user';

    if ($password !== $confirm_password) {
        $_SESSION['error'] = "Passwords do not match!";
        header("Location: register.php");
        exit();
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $_SESSION['error'] = "Username or Email already exists!";
        header("Location: register.php");
        exit();
    }
    $stmt->close();

    $stmt = $conn->prepare("INSERT INTO users (fullname, username, email, password, role, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("sssss", $fullname, $username, $email, $hashed_password, $role);

    if ($stmt->execute()) {
        $_SESSION['success'] = "Registration successful! Please log in.";
        header("Location: index.php");
    } else {
        $_SESSION['error'] = "Something went wrong. Please try again.";
        header("Location: register.php");
    }

    $stmt->close();
    $conn->close();
} else {
    $_SESSION['error'] = "Invalid request!";
    header("Location: register.php");
}
?>