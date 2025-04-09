<?php
session_start();
require_once 'config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management - Products</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

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
    <?php include 'includes/sidebar.php'; ?>

    <div class="content">
        <?php include 'includes/header.php'; ?>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <!-- Title Section -->
            <h5><i class="bi bi-box-seam"></i> Product Management</h5>

            <!-- Button Section (aligned on the same line) -->
            <div class="d-flex align-items-center">
                <!-- Add Product Button -->
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                    <i class="bi bi-plus"></i> Add Product
                </button>

                <!-- Get Products Button -->
                <form id="getProductsForm" action="getproducts.php" method="POST" class="ms-3">
                    <button type="submit" class="btn btn-info">
                        <i class="bi bi-cart"></i> Sale
                    </button>
                </form>
            </div>
        </div>



        <div class="card shadow-sm">
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>

                <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php endif; ?>
                <table id="productTable" class="table table-striped table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Total Price</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
       $query = "
    SELECT products.id, products.name, products.quantity, products.unit_price, products.total_price, categories.name AS category_name
    FROM products
    JOIN categories ON products.category_id = categories.id
";
$result = mysqli_query($conn, $query);
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                <td>{$row['id']}</td>
                <td>{$row['name']}</td>
                <td>{$row['category_name']}</td>
                <td>Kg {$row['quantity']}</td>
                <td>Rwf {$row['unit_price']}</td>
                <td>Rwf {$row['total_price']}</td>
                <td>
                    <a href='edit_product.php?id={$row['id']}' class='btn btn-sm btn-warning'>
                        <i class='bi bi-pencil-square'></i>
                    </a>
                    <a href='delete_product.php?id={$row['id']}' class='btn btn-sm btn-danger' onclick='return confirm(\"Are you sure you want to delete this product?\")'>
                        <i class='bi bi-trash'></i>
                    </a>
                </td>
            </tr>";
        }
        ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <!-- Add Product Modal -->
    <div class="modal fade" id="addProductModal" tabindex="-1" aria-labelledby="addProductModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addProductModalLabel"><i class="bi bi-plus-circle"></i> Add New Product
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form id="addProductForm" action="add_product.php" method="POST">
                        <div class="mb-3">
                            <label for="productName" class="form-label">Product Name</label>
                            <input type="text" class="form-control" id="productName" name="name" required>
                        </div>

                        <div class="mb-3">
                            <label for="categoryId" class="form-label">Category</label>
                            <select class="form-control" id="categoryId" name="category_id" required>
                                <option value="">Select Category</option>
                                <?php
            $category_query = "SELECT id, name FROM categories";
            $category_result = mysqli_query($conn, $category_query);
            while ($category = mysqli_fetch_assoc($category_result)) {
                echo "<option value='{$category['id']}'>{$category['name']}</option>";
            }
            ?>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required>
                        </div>

                        <div class="mb-3">
                            <label for="unitPrice" class="form-label">Unit Price</label>
                            <input type="text" class="form-control" id="unitPrice" name="unit_price" required>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description (optional)</label>
                            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- PDF Preview Modal -->
    <div class="modal fade pdf-preview-modal" id="pdfPreviewModal" tabindex="-1" aria-labelledby="pdfPreviewModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="pdfPreviewModalLabel">
                        <i class="bi bi-file-pdf me-2"></i>Print Preview
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="pdf-frame-container">
                        <iframe id="pdfFrame"></iframe>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-2"></i>Close
                    </button>
                    <button type="button" class="btn btn-primary" onclick="printPDF()">
                        <i class="bi bi-printer me-2"></i>Print
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

    <script>
    $(document).ready(function() {
        var table = $('#productTable').DataTable({
            dom: 'Bfrtip',
            buttons: [{
                    extend: 'excelHtml5',
                    text: '<i class="bi bi-file-earmark-excel"></i> Excel',
                    className: 'btn btn-success'
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="bi bi-file-earmark-pdf"></i> PDF',
                    className: 'btn btn-danger'
                },
                {
                    extend: 'print',
                    text: '<i class="bi bi-printer"></i> Print',
                    className: 'btn btn-secondary',
                    action: function(e, dt, button, config) {
                        // Generate PDF content excluding the "Actions" column
                        var pdfContent = table.buttons.exportData({
                            format: {
                                header: function(data, columnIdx) {
                                    return data;
                                }
                            },
                            columns: [0, 1, 2, 3,
                                4
                            ] // Exclude the "Actions" column (index 5)
                        });

                        // Create PDF document
                        var doc = new pdfMake.createPdf({
                            content: [{
                                table: {
                                    headerRows: 1,
                                    body: [pdfContent.header].concat(pdfContent
                                        .body)
                                }
                            }]
                        });

                        // Generate PDF blob and show in modal
                        doc.getBlob((blob) => {
                            const url = URL.createObjectURL(blob);
                            $('#pdfFrame').attr('src', url);
                            $('#pdfPreviewModal').modal('show');
                        });
                    }
                }
            ]
        });

        $('#exportExcel').click(() => table.button('.buttons-excel').trigger());
        $('#exportPdf').click(() => table.button('.buttons-pdf').trigger());
    });

    function printPDF() {
        const iframe = document.getElementById('pdfFrame');
        iframe.contentWindow.print();
    }
    </script>
</body>

</html>