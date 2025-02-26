<?php
session_start();
require_once 'config/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = mysqli_real_escape_string($conn, $_POST['product_id']);
    $movement_type = mysqli_real_escape_string($conn, $_POST['movement_type']);
    $quantity = (int)$_POST['quantity'];
    $reference = mysqli_real_escape_string($conn, $_POST['reference']);
    $user_id = $_SESSION['user_id'];
    
    // Start transaction
    mysqli_begin_transaction($conn);
    
    try {
        // Insert movement record
        $query = "INSERT INTO stock_movements (product_id, movement_type, quantity, reference, user_id, status) 
                 VALUES (?, ?, ?, ?, ?, 'Completed')";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "isiss", $product_id, $movement_type, $quantity, $reference, $user_id);
        mysqli_stmt_execute($stmt);
        
        // Update product stock
        $stock_change = $movement_type === 'IN' ? $quantity : -$quantity;
        $query = "UPDATE products SET stock_quantity = stock_quantity + ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "ii", $stock_change, $product_id);
        mysqli_stmt_execute($stmt);
        
        mysqli_commit($conn);
        $_SESSION['success'] = "Stock movement recorded successfully";
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $_SESSION['error'] = "Error recording stock movement: " . $e->getMessage();
    }
    
    header('Location: stock_movements.php');
    exit();
}
