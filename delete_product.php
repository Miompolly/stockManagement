<?php
session_start();
require_once 'config/db_connect.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Check if the product exists
    $checkQuery = "SELECT * FROM products WHERE id = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows === 0) {
        // If no product is found, redirect with error
        $_SESSION['error'] = "Product not found!";
        header("Location: products.php");
        exit();
    }

    // Delete the product
    $deleteQuery = "DELETE FROM products WHERE id = ?";
    $stmt = $conn->prepare($deleteQuery);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        // Success message
        $_SESSION['success'] = "Product deleted successfully!";
    } else {
        // Error message if deletion fails
        $_SESSION['error'] = "Failed to delete product!";
    }
    
    $stmt->close();
} else {
    // Error message if 'id' is not provided in the URL
    $_SESSION['error'] = "Invalid request!";
}

// Redirect back to the products page
header("Location: products.php");
exit();
?>