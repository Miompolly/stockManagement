<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include 'config/db_connect.php';

$name = $_POST['name'];
$category_id = (int)$_POST['category_id'];
$quantity = (int)$_POST['quantity'];
$unit_price = (float)$_POST['unit_price'];
$description = $_POST['description'];
$created_at = date("Y-m-d H:i:s");
$total_price = $unit_price * $quantity;
// Check if product already exists
$check_query = "SELECT * FROM products WHERE name = '$name' AND category_id = $category_id";
$result = mysqli_query($conn, $check_query);

if ($result && mysqli_num_rows($result) > 0) {
    // Update existing product
    $row = mysqli_fetch_assoc($result);
    $updated_qty = $row['quantity'] + $quantity;
    $updated_price = ($row['unit_price'] + $unit_price) / 2;
    $new_total_price =$row['total_price'] + $total_price;

    $update_query = "UPDATE products SET 
                        quantity = $updated_qty, 
                        unit_price = $updated_price, 
                        total_price = $new_total_price,
                        description = '$description' 
                    WHERE id = {$row['id']}";

    if (mysqli_query($conn, $update_query)) {
        $_SESSION['success'] = "✅ Product updated successfully.";
    } else {
        $_SESSION['error'] = "❌ Update failed. " . mysqli_error($conn);
    }

} else {
    // Insert new product
    $insert_query = "INSERT INTO products (name, unit_price, category_id, created_at, quantity,total_price, description) 
                     VALUES ('$name', $unit_price, $category_id, '$created_at', '$quantity' ,'$total_price','$description')";

    if (mysqli_query($conn, $insert_query)) {
        $_SESSION['success'] = "✅ New product added successfully.";
    } else {
        $_SESSION['error'] = "❌ Insert failed. " . mysqli_error($conn);
    }
}

// Redirect back
header("Location: products.php"); 
exit();
?>