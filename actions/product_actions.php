<?php
require_once '../config/database.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die(json_encode(['success' => false, 'message' => 'Unauthorized']));
}

// Handle POST request (Create/Update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $category_id = $_POST['category_id'];
    $quantity = $_POST['quantity'];
    $unit_price = $_POST['unit_price'];
    $description = $_POST['description'];

    if (isset($_POST['id'])) {
        // Update
        $stmt = $pdo->prepare("UPDATE products SET name = ?, category_id = ?, quantity = ?, unit_price = ?, description = ? WHERE id = ?");
        $stmt->execute([$name, $category_id, $quantity, $unit_price, $description, $_POST['id']]);
    } else {
        // Create
        $stmt = $pdo->prepare("INSERT INTO products (name, category_id, quantity, unit_price, description) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category_id, $quantity, $unit_price, $description]);
    }

    echo json_encode(['success' => true]);
    exit();
}

// Handle DELETE request
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM products WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    echo json_encode(['success' => true]);
    exit();
}
?>
