<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config/db_connect.php';  // Ensure this file is correctly included

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        // Use a prepared statement to prevent SQL injection
        $query = "SELECT id, email, password, role FROM users WHERE email = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($result && $user = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $user['password'])) {
                // Set session variables if login is successful
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['email'] = $user['email'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: dashboard.php");
                } else {
                    header("Location: index.php");
                }
                exit();
            } else {
                $_SESSION['error'] = "Invalid email or password!";
            }
        } else {
            $_SESSION['error'] = "Invalid email or password!";
        }

        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['error'] = "Please enter both email and password!";
    }

    header("Location: index.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>