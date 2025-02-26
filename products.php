<?php
session_start();
require_once 'config/db_connect.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Stock Management</title>
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">

    <style>
        body { display: flex; background-color: #f8f9fa; }
        .sidebar { width: 250px; background: #fff; padding: 20px; height: 100vh; box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1); }
        .content { flex: 1; padding: 20px; }
    </style>
</head>
<body>
    <?php include 'includes/sidebar.php'; ?>
    
    <div class="content">
    <?php include 'includes/header.php'; ?>   
      
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h5>Dashboard / Products</h5>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addProductModal">
                <i class="bi bi-plus"></i> Add Product
            </button>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-end mb-3">
                    <button class="btn btn-success me-2" id="exportExcel">
                        <i class="bi bi-file-earmark-excel"></i> Excel
                    </button>
                    <button class="btn btn-danger me-2" id="exportPdf">
                        <i class="bi bi-file-earmark-pdf"></i> PDF
                    </button>
                    <button class="btn btn-secondary me-2" id="printTable">
                        <i class="bi bi-printer"></i> Print
                    </button>
                </div>

                <table id="productTable" class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Quantity</th>
                            <th>Unit Price</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- PHP code to fetch and display products -->
                        <?php
                        $query = "SELECT * FROM products"; // Adjust according to your DB
                        $result = mysqli_query($conn, $query);
                        while ($row = mysqli_fetch_assoc($result)) {
                            echo "<tr>";
                            echo "<td>{$row['id']}</td>";
                            echo "<td>{$row['name']}</td>";
                            echo "<td>{$row['category']}</td>";
                            echo "<td>{$row['quantity']}</td>";
                            echo "<td>\${$row['price']}</td>";
                            echo "<td>
                                    <button class='btn btn-warning btn-sm'><i class='bi bi-pencil'></i></button>
                                    <button class='btn btn-danger btn-sm'><i class='bi bi-trash'></i></button>
                                  </td>";
                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- jQuery, Bootstrap JS & DataTables -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    <!-- DataTables Export Buttons -->
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
                dom: 'Bfrtip', // Add export buttons
                buttons: [
                    { extend: 'excelHtml5', text: '<i class="bi bi-file-earmark-excel"></i> Excel', className: 'btn btn-success' },
                    { extend: 'pdfHtml5', text: '<i class="bi bi-file-earmark-pdf"></i> PDF', className: 'btn btn-danger' },
                    { extend: 'print', text: '<i class="bi bi-printer"></i> Print', className: 'btn btn-secondary' }
                ]
            });

            // Trigger export buttons
            $('#exportExcel').click(() => table.button('.buttons-excel').trigger());
            $('#exportPdf').click(() => table.button('.buttons-pdf').trigger());
            $('#printTable').click(() => table.button('.buttons-print').trigger());
        });
    </script>

</body>
</html>
