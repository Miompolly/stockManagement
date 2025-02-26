<div class="sidebar">
    <h2><i class="bi bi-box-seam"></i> Stock</h2>
    <ul class="nav flex-column">
        <?php
        $current_page = basename($_SERVER['PHP_SELF']);
        $menu_items = [
            'dashboard.php' => ['icon' => 'speedometer2', 'text' => 'Dashboard'],
            'products.php' => ['icon' => 'box', 'text' => 'Products'],
            'categories.php' => ['icon' => 'tags', 'text' => 'Categories'],
            'stock_movements.php' => ['icon' => 'arrow-left-right', 'text' => 'Stock Movements'],
            'users.php' => ['icon' => 'people', 'text' => 'Users'],
            'reports.php' => ['icon' => 'file-earmark-text', 'text' => 'Reports'],
            'settings.php' => ['icon' => 'gear', 'text' => 'Settings']
        ];

        foreach ($menu_items as $page => $item) {
            $active = ($current_page === $page) ? 'active' : '';
            echo "<li class='nav-item'>";
            echo "<a class='nav-link $active' href='$page'>";
            echo "<i class='bi bi-{$item['icon']}'></i> {$item['text']}";
            echo "</a></li>";
        }
        ?>
        <li class="nav-item">
            <a class="nav-link text-danger" href="logout.php">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </li>
    </ul>
</div>
