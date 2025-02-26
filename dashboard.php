<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stock Management Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            background: #fff;
            padding: 20px;
            height: 100vh;
            box-shadow: 2px 0 5px rgba(0, 0, 0, 0.1);
        }
        .content {
            flex: 1;
            padding: 20px;
        }
        .card {
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border: none;
        }
    </style>
</head>
<body>
<?php include 'includes/sidebar.php'; ?>
    
    <div class="content">
    <?php include 'includes/header.php'; ?>   
       


        <div class="row">
            <div class="col-md-3">
                <div class="card p-3">
                    <h5><i class="bi bi-box-seam"></i> Total Products</h5>
                    <p class="h3">245</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5><i class="bi bi-exclamation-triangle"></i> Low Stock Items</h5>
                    <p class="h3">12</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5><i class="bi bi-graph-up"></i> Total Value</h5>
                    <p class="h3">$34,567</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card p-3">
                    <h5><i class="bi bi-arrow-left-right"></i> Today's Movements</h5>
                    <p class="h3">23</p>
                </div>
            </div>
        </div>
        
        <div class="row mt-4">
            <div class="col-md-8">
                <div class="card p-3">
                    <h5>Stock Movement History</h5>
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card p-3">
                    <h5>Low Stock Alerts</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Printer Paper A4
                            <span class="badge bg-danger">2 left</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            Ink Cartridge HP
                            <span class="badge bg-warning">5 left</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            USB Cables
                            <span class="badge bg-danger">3 left</span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">Recent Stock Movements</h5>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Type</th>
                            <th>Quantity</th>
                            <th>Date</th>
                            <th>User</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Laptop Dell XPS</td>
                            <td><span class="badge bg-success">IN</span></td>
                            <td>+10</td>
                            <td>2023-09-20 14:30</td>
                            <td>John Doe</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                        <tr>
                            <td>iPhone 13 Pro</td>
                            <td><span class="badge bg-danger">OUT</span></td>
                            <td>-5</td>
                            <td>2023-09-20 13:15</td>
                            <td>Jane Smith</td>
                            <td><span class="badge bg-success">Completed</span></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        var ctx = document.getElementById('stockChart').getContext('2d');
        var stockChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                datasets: [{
                    label: 'Stock In',
                    data: [12, 19, 13, 15, 20, 25, 18],
                    borderColor: '#198754',
                    fill: false
                }, {
                    label: 'Stock Out',
                    data: [8, 15, 9, 12, 17, 19, 14],
                    borderColor: '#dc3545',
                    fill: false
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</body>
</html>
