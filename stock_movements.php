<?php
session_start();
require_once 'config/db_connect.php';

// Fetch all stock movements with related product and user details
$query = "SELECT sm.id, sm.product_id, sm.type, sm.quantity, sm.date, sm.user_id, 
                 p.name as product_name, u.username 
          FROM stock_movements sm 
          JOIN products p ON sm.product_id = p.id 
          JOIN users u ON sm.user_id = u.id 
          ORDER BY sm.date DESC";

$movements = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Movements - Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body { display: flex; background-color: #f8f9fa; }
        .sidebar { width: 250px; background: #fff; padding: 20px; height: 100vh; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); }
        .content { flex: 1; padding: 20px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Stock Movements</h1>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#moveProductModal">
                <i class="bi bi-arrow-right-circle"></i> Move Product
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Product</th>
                            <th>Movement Type</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($movements)): ?>
                            <tr>
                                <td><?= $row['id'] ?></td>
                                <td><?= htmlspecialchars($row['product_name']) ?></td>
                                <td>
                                    <span class="badge bg-<?= $row['type'] == 'IN' ? 'success' : 'danger' ?>">
                                        <?= $row['type'] ?>
                                    </span>
                                </td>
                                <td><?= $row['quantity'] ?></td>
                                <td><?= date('Y-m-d H:i', strtotime($row['date'])) ?></td>
                                <td><?= htmlspecialchars($row['username']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-info view-movement" data-id="<?= $row['id'] ?>">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Move Product Modal -->
    <div class="modal fade" id="moveProductModal" tabindex="-1" aria-labelledby="moveProductModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="moveProductModalLabel">Move Product</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="process_move.php" method="POST" id="moveProductForm">
                    <div class="modal-body">
                        <input type="hidden" name="product_id" id="product_id">
                        <div class="mb-3">
                            <label for="product_name" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="product_name" name="product_name" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="movement_type" class="form-label">Movement Type</label>
                            <select class="form-select" name="movement_type" id="movement_type" required>
                                <option value="IN">Stock In</option>
                                <option value="OUT">Stock Out</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" name="quantity" id="quantity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="reference" class="form-label">Reference</label>
                            <input type="text" class="form-control" name="reference" id="reference" placeholder="PO-123">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Move Product</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set the product ID and name in the modal when the move button is clicked
        document.querySelectorAll('.move-product').forEach(function(button) {
            button.addEventListener('click', function() {
                document.getElementById('product_id').value = this.dataset.id;
                document.getElementById('product_name').value = this.dataset.name;
            });
        });
    </script>
</body>
</html>
