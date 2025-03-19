<?php
session_start();

// Check if user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

require_once 'config/db_connect.php';

if (isset($_GET['id'])) {
    $category_id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM categories WHERE id = ?");
    $stmt->bind_param("i", $category_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Category deleted successfully';
    } else {
        $_SESSION['error'] = 'Failed to delete category';
    }
    $stmt->close();
}

header("Location: categories.php");
exit();
?>