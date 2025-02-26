<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
        body { height: 100vh; display: flex; align-items: center; justify-content: center; background-color: #f8f9fa; }
        .container { max-width: 900px; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1); }
        .left-box { padding: 40px; }
        .right-box { background-color: #f4f4f4; padding: 40px; display: flex; flex-direction: column; justify-content: center; }
        .form-control { padding-left: 2.5rem; }
        .input-group-text { position: absolute; padding-left: 5px;}
        .form-control::placeholder { color: #adb5bd;padding-left: 5px;}
        .btn-google { background-color: #fff; border: 1px solid #ddd; }
        .btn-google img { width: 20px; margin-right: 10px; }
    </style>
</head>
<body>
    <div class="container row">
        <div class="col-md-6 left-box">
            <h2 class="mb-4 text-center">Create Your Account</h2>
            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            ?>
            <form action="process_register.php" method="POST">
                <div class="mb-3 position-relative">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="fullname" class="form-control" placeholder="Full Name" required>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-group-text"><i class="bi bi-person-circle"></i></span>
                    <input type="text" name="username" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                </div>
                <div class="mb-3 position-relative">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="confirm_password" class="form-control" placeholder="Confirm Password" required>
                </div>
                <button type="submit" class="btn btn-dark w-100">Register</button>
                <div class="text-center mt-3">
                    <button type="button" class="btn btn-google w-100"><i class="bi bi-google"></i> Continue with Google</button>
                </div>
            </form>
        </div>
        <div class="col-md-6 right-box text-center">
            <h3>Stock Management</h3>
            <p>Efficient solutions to optimize your inventory.</p>
            <ul class="list-unstyled text-start mx-auto" style="max-width: 300px;">
                <li><i class="bi bi-check-circle"></i> Keep track of stock levels in real-time</li>
                <li><i class="bi bi-check-circle"></i> Prevent shortages and overstocking</li>
                <li><i class="bi bi-check-circle"></i> Streamline your supply chain</li>
                <li><i class="bi bi-check-circle"></i> Get insights with detailed analytics</li>
            </ul>
            <a href="login.php" class="btn btn-dark">Login Now</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
