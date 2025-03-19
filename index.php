<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Stock Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <style>
    body {
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
    }

    .container {
        max-width: 900px;
        background: #fff;
        border-radius: 10px;
        overflow: hidden;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .left-box {
        padding: 40px;
    }

    .right-box {
        background-color: #f4f4f4;
        padding: 40px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .input-group-text {
        background: none;
        border: none;
        color: #6c757d;
    }
    </style>
</head>

<body>
    <div class="container row">
        <div class="col-md-6 left-box">
            <h2 class="mb-4 text-center">Login to Your Account</h2>

            <?php
            session_start();
            if (isset($_SESSION['error'])) {
                echo '<div class="alert alert-danger">' . $_SESSION['error'] . '</div>';
                unset($_SESSION['error']);
            }
            if (isset($_SESSION['success'])) {
                echo '<div class="alert alert-success">' . $_SESSION['success'] . '</div>';
                unset($_SESSION['success']);
            }
            ?>

            <form method="POST" action="process_login.php">
                <!-- Email Field -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" id="email" name="email" class="form-control" placeholder="Enter your email"
                            required>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="mb-3">
                    <label for="password" class="form-label">Password:</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-lock"></i></span>
                        <input type="password" id="password" name="password" class="form-control"
                            placeholder="Enter your password" required>
                    </div>
                </div>

                <!-- CSRF Token (Optional for Security) -->
                <?php
                $token = bin2hex(random_bytes(32));
                $_SESSION['csrf_token'] = $token;
                ?>
                <input type="hidden" name="csrf_token" value="<?php echo $token; ?>">

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-100">Login</button>
            </form>

            <div class="mt-3 text-center">
                <a href="forgot_password.php">Forgot Password?</a>
            </div>
        </div>

        <!-- Right Side Information Section -->
        <div class="col-md-6 right-box text-center">
            <h3>Stock Management</h3>
            <p>Efficient solutions to optimize your inventory.</p>
            <ul class="list-unstyled text-start mx-auto" style="max-width: 300px;">
                <li><i class="bi bi-check-circle"></i> Keep track of stock levels in real-time</li>
                <li><i class="bi bi-check-circle"></i> Prevent shortages and overstocking</li>
                <li><i class="bi bi-check-circle"></i> Streamline your supply chain</li>
                <li><i class="bi bi-check-circle"></i> Get insights with detailed analytics</li>
            </ul>
            <a href="register.php" class="btn btn-dark">Register Now</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>