<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once 'config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'] ?? '';
    $description = $_POST['description'] ?? '';

    if (!empty($name)) {
        $stmt = $conn->prepare("INSERT INTO categories (name, description) VALUES (?, ?)");
        $stmt->bind_param("ss", $name, $description);

        if ($stmt->execute()) {
            $_SESSION['success'] = 'Category added successfully';
        } else {
            $_SESSION['error'] = 'Failed to add category';
        }
        $stmt->close();
    } else {
        $_SESSION['error'] = 'Category name is required';
    }

    header("Location: categories.php");
    exit();
}
?>