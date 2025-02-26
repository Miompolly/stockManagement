<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>
<div class="sidebar">
    <h2><i class="bi bi-box-seam"></i> Stock </h2>
    <ul class="nav flex-column">
        <?php
        $menu_items = [
            'dashboard.php' => ['icon' => 'bi-speedometer2', 'text' => 'Dashboard'],
            'products.php' => ['icon' => 'bi-box', 'text' => 'Products'],
            'categories.php' => ['icon' => 'bi-tags', 'text' => 'Categories'],
            'stock_movements.php' => ['icon' => 'bi-arrow-left-right', 'text' => 'Stock Movements'],
            'users.php' => ['icon' => 'bi-people', 'text' => 'Users'],
            'reports.php' => ['icon' => 'bi-file-earmark-text', 'text' => 'Reports'],
            'settings.php' => ['icon' => 'bi-gear', 'text' => 'Settings']
        ];

        foreach ($menu_items as $page => $item) {
            $active = ($current_page === $page) ? 'active' : '';
            echo "<li class='nav-item'><a class='nav-link {$active}' href='{$page}'>";
            echo "<i class='bi {$item['icon']}'></i> {$item['text']}</a></li>";
        }
        ?>
        <li class="nav-item"><a class="nav-link text-danger" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout</a>
        </li>
    </ul>
</div>
