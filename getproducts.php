<?php
session_start();
require_once 'config/db_connect.php';

// Fake session user (replace this with real session user ID)
$_SESSION['user_id'] = 1; // Example user ID

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = $_POST['product_id'];
    $quantity = (int)$_POST['quantity'];
    $payment = (float)$_POST['payment'];
    $user_id = $_SESSION['user_id'];

    if ($payment <= 0) {
        $error = "Payment must be more than 0 Rwf.";
    } else {
        // Fetch current product info
        $query = "SELECT p.name AS product_name, p.unit_price, p.quantity, c.name AS category_name
                  FROM products p
                  JOIN categories c ON p.category_id = c.id
                  WHERE p.id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $stmt->bind_result($product_name, $unit_price, $current_qty, $category_name);
        $stmt->fetch();
        $stmt->close();

        if ($quantity > $current_qty) {
            $error = "Not enough stock. Only $current_qty Kg left.";
        } else {
            // Subtract from stock
            $new_qty = $current_qty - $quantity;
            $update = "UPDATE products SET quantity = ? WHERE id = ?";
            $stmt = $conn->prepare($update);
            $stmt->bind_param("ii", $new_qty, $product_id);
            $stmt->execute();
            $stmt->close();

            // Log into stock_movements
            $insert = "INSERT INTO stock_movements 
                       (product_id, product_name, category_name, quantity_removed, unit_price, payment, removed_by) 
                       VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($insert);
            $stmt->bind_param("issiddi", $product_id, $product_name, $category_name, $quantity, $unit_price, $payment, $user_id);
            $stmt->execute();
            $stmt->close();

            $success = "$quantity Kg removed successfully. Movement recorded.";
        }
    }
}

// Fetch products with categories
$query = "SELECT p.id, p.name AS product_name, p.unit_price, p.quantity, c.name AS category_name
          FROM products p
          JOIN categories c ON p.category_id = c.id";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Remove Product Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <style>
    body {
        display: flex;
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .sidebar {
        width: 250px;

        color: white;
        padding: 20px;
        height: 100vh;
        position: fixed;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
    }

    .content {
        margin-left: 270px;
        padding: 20px;
        width: 100%;
    }

    /* PDF Preview Modal Styles */
    .pdf-preview-modal .modal-dialog {
        max-width: 850px;
        margin: 1.75rem auto;
    }

    .pdf-preview-modal .modal-content {
        border-radius: 8px;
        box-shadow: 0 0 20px rgba(0, 0, 0, 0.15);
    }

    .pdf-preview-modal .modal-body {
        background: #f0f0f0;
        padding: 20px;
    }

    .pdf-frame-container {
        background: white;
        padding: 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin: 0 auto;
        width: 100%;
        max-width: 780px;
    }

    #pdfFrame {
        width: 100%;
        height: 80vh;
        border: none;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
    }
    </style>
</head>

<body>
    <?php include 'includes/sidebar.php';?>
    <div class="content"><?php include 'includes/header.php';
    ?><div class="card shadow">
            <div class="card-header bg-success text-white">
                <h5><i class="bi bi-dash-circle"></i>Remove Product from Stock</h5>
            </div>
            <div class="card-body"><?php if ( !empty($success)): ?><div class="alert alert-success"><?=$success ?></div><?php endif;
    ?><?php if ( !empty($error)): ?><div class="alert alert-danger"><?=$error ?></div><?php endif;

    ?><form method="POST">
                    <div class="row g-3">

                        <div class="col-md-6"><label for="product_id" class="form-label">Product</label><select
                                id="product_id" name="product_id" class="form-select" required>
                                <option value="">Select product</option><?php while ($row=mysqli_fetch_assoc($result)) {
        ?><option value="<?= $row['id'] ?>" data-name="<?= htmlspecialchars($row['product_name']) ?>"
                                    data-category="<?= htmlspecialchars($row['category_name']) ?>"
                                    data-price="<?= $row['unit_price'] ?>" data-quantity="<?= $row['quantity'] ?>">
                                    <?=htmlspecialchars($row['product_name']) ?>- <?=$row['category_name'] ?>
                                </option><?php
    }

    ?>
                            </select></div>

                        <div class="col-md-6"><label class="form-label">Category</label><input type="text"
                                id="category_display" class="form-control" readonly></div>

                        <div class="col-md-6"><label class="form-label">Unit Price</label><input type="text"
                                id="price_display" class="form-control"></div>

                        <div class="col-md-6"><label class="form-label">Quantity to Remove
                                (Kg)</label><select id="quantity_to_remove" name="quantity" class="form-select"
                                required></select></div>

                        <div class="col-md-6"><label class="form-label">Payment (Rwf)</label><input type="number"
                                name="payment" id="payment" class="form-control" min="1" required>
                        </div>
                    </div>
                    <div class="mt-4"><button type="submit" class="btn btn-success"><i
                                class="bi bi-check-circle"></i>Confirm </button><a href="javascript:history.back();"
                            class="btn btn-secondary"><i class="bi bi-arrow-left"></i>Back </a></div>
                </form>
            </div>
        </div>
    </div>

    <script>
    document.getElementById("product_id").addEventListener("change", function() {
            const selected = this.options[this.selectedIndex];

            const price = selected.getAttribute("data-price") || '';
            const category = selected.getAttribute("data-category") || '';
            const quantity = selected.getAttribute("data-quantity") || 0;

            document.getElementById("price_display").value = price;
            document.getElementById("category_display").value = category;

            // Populate quantity select
            const qtySelect = document.getElementById("quantity_to_remove");
            qtySelect.innerHTML = "";

            for (let i = 1; i <= quantity; i++) {
                let option = document.createElement("option");
                option.value = i;
                option.textContent = i + " Kg";
                qtySelect.appendChild(option);
            }
        }

    );
    </script>
</body>

</html>