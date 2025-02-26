<?php
session_start();
require_once '../config/database.php';

// Handle Login
if(isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $pdo->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        header('Location: ../dashboard.php');
        exit();
    } else {
        header('Location: ../index.php?error=invalid');
        exit();
    }
}

// Handle Register
if(isset($_POST['register'])) {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $email = $_POST['email'];
    
    try {
        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $email]);
        header('Location: ../index.php?registered=1');
    } catch(PDOException $e) {
        header('Location: ../register.php?error=exists');
    }
    exit();
}

// Handle Logout
if(isset($_GET['logout'])) {
    session_destroy();
    header('Location: ../index.php');
    exit();
}
