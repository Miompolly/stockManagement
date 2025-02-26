        <div class="d-flex justify-content-between align-items-center mb-4 bg-white p-2 ps-2 pe-2 shadow">
            <h1>Dashboard</h1>
            <div class="user-info">
                Welcome, <?php echo htmlspecialchars($_SESSION['username'] ?? 'Guest'); ?>
            </div>
        </div>