<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
require_once 'config/db_connect.php';

// Fetch data for categories, products, movements, and users
$query_categories = "SELECT id, name, description, created_at FROM categories ORDER BY name";
$query_products = "SELECT p.id, p.name, c.name as category_name, p.quantity, p.unit_price, p.description, p.created_at
                   FROM products p
                   JOIN categories c ON p.category_id = c.id
                   ORDER BY p.name";
$query_movements = "SELECT sm.id, sm.product_id, sm.type, sm.quantity, sm.date, p.name as product_name, u.username
                    FROM stock_movements sm
                    JOIN products p ON sm.product_id = p.id
                    JOIN users u ON sm.user_id = u.id
                    ORDER BY sm.date DESC";
$query_users = "SELECT id, username, email, role, created_at FROM users ORDER BY username";

// Execute queries
$categories = mysqli_query($conn, $query_categories);
$products = mysqli_query($conn, $query_products);
$movements = mysqli_query($conn, $query_movements);
$users = mysqli_query($conn, $query_users);

// Export to CSV function
if (isset($_GET['export'])) {
    $type = $_GET['export'];
    if ($type == 'categories') {
        exportToCSV($categories, ['ID', 'Category Name', 'Description', 'Created At']);
    } elseif ($type == 'products') {
        exportToCSV($products, ['ID', 'Product Name', 'Category Name', 'Quantity', 'Unit Price', 'Description', 'Created At']);
    } elseif ($type == 'movements') {
        exportToCSV($movements, ['ID', 'Product Name', 'Movement Type', 'Quantity', 'Date', 'Username']);
    } elseif ($type == 'users') {
        exportToCSV($users, ['ID', 'Username', 'Email', 'Role', 'Created At']);
    }
}

function exportToCSV($result, $headers) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="report.csv"');
    $output = fopen('php://output', 'w');
    fputcsv($output, $headers);

    while ($row = mysqli_fetch_assoc($result)) {
        fputcsv($output, $row);
    }

    fclose($output);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { display: flex; background-color: #f8f9fa; }
        .sidebar { width: 250px; background: #fff; padding: 20px; height: 100vh; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); }
        .content { flex: 1; padding: 20px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>

    <div class="content">
        <h1 class="mb-4">Reports</h1>
        
        <!-- Print Button -->
        <button class="btn btn-success mb-3" onclick="window.print();">
            <i class="bi bi-printer"></i> Print Report
        </button>

        <!-- Export to CSV Buttons -->
        <div class="mb-3">
            <a href="reports.php?export=categories" class="btn btn-primary">
                <i class="bi bi-file-earmark-csv"></i> Export Categories to CSV
            </a>
            <a href="reports.php?export=products" class="btn btn-primary">
                <i class="bi bi-file-earmark-csv"></i> Export Products to CSV
            </a>
            <a href="reports.php?export=movements" class="btn btn-primary">
                <i class="bi bi-file-earmark-csv"></i> Export Movements to CSV
            </a>
            <a href="reports.php?export=users" class="btn btn-primary">
                <i class="bi bi-file-earmark-csv"></i> Export Users to CSV
            </a>
        </div>

        <!-- Charts Section -->
        <div class="row">
            <div class="col-md-6">
                <canvas id="categoriesChart" height="300"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="productsChart" height="300"></canvas>
            </div>
        </div>

        <!-- Tables Section -->
        <h3>Categories</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Category Name</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($categories)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Products</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Category Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Description</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($products)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['name']) ?></td>
                        <td><?= htmlspecialchars($row['category_name']) ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= number_format($row['unit_price'], 2) ?></td>
                        <td><?= htmlspecialchars($row['description']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Movements</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Product Name</th>
                    <th>Movement Type</th>
                    <th>Quantity</th>
                    <th>Date</th>
                    <th>Username</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($movements)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['product_name']) ?></td>
                        <td><?= $row['type'] ?></td>
                        <td><?= $row['quantity'] ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['date'])) ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h3>Users</h3>
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($users)): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['username']) ?></td>
                        <td><?= htmlspecialchars($row['email']) ?></td>
                        <td><?= htmlspecialchars($row['role']) ?></td>
                        <td><?= date('Y-m-d H:i', strtotime($row['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script>
        const categoriesData = <?= json_encode(array_column(mysqli_fetch_all($categories, MYSQLI_ASSOC), 'name')); ?>;
        const categoriesCount = <?= json_encode(array_column(mysqli_fetch_all($categories, MYSQLI_ASSOC), 'name')); ?>;
        
        const categoriesCtx = document.getElementById('categoriesChart').getContext('2d');
        const categoriesChart = new Chart(categoriesCtx, {
            type: 'pie',
            data: {
                labels: categoriesData,
                datasets: [{
                    data: categoriesCount,
                    backgroundColor: ['#4CAF50', '#FFC107', '#F44336'],
                }]
            }
        });

        const productsData = <?= json_encode(array_column(mysqli_fetch_all($products, MYSQLI_ASSOC), 'name')); ?>;
        const productsQuantity = <?= json_encode(array_column(mysqli_fetch_all($products, MYSQLI_ASSOC), 'quantity')); ?>;
        
        const productsCtx = document.getElementById('productsChart').getContext('2d');
        const productsChart = new Chart(productsCtx, {
            type: 'bar',
            data: {
                labels: productsData,
                datasets: [{
                    label: 'Product Quantity',
                    data: productsQuantity,
                    backgroundColor: ['#2196F3', '#8BC34A', '#FF5722'],
                }]
            }
        });
    </script>
</body>
</html>
