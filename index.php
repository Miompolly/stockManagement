<?php
session_start();
require_once 'config/database.php';

if(isset($_SESSION['user_id'])) {
    header('Location: dashboard.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Stock Management</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <div class="card" style="max-width: 400px; margin: 100px auto;">
            <h2>Login</h2>
            <form action="actions/auth_actions.php" method="POST">
                <div class="form-group">
                    <label>Username</label>
                    <input type="text" name="username" required class="form-control">
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required class="form-control">
                </div>
                <button type="submit" name="login" class="btn btn-primary">Login</button>
            </form>
            <p>Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </div>
</body>
</html>
