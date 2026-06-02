<?php

$config1 = __DIR__ . '/config.php';
$config2 = __DIR__ . '/../config.php';

if (file_exists($config1)) {
    require_once $config1;
} elseif (file_exists($config2)) {
    require_once $config2;
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('isAdmin')) {
    function isAdmin() {
        return isset($_SESSION['role']) && $_SESSION['role'] == 'admin';
    }
}

?>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold" href="index.php">
            <span style="color:#7b1e2b;">MORNING</span>
            <span style="color:#d4a437;">BAKERY</span>
        </a>

        <button 
            class="navbar-toggler" 
            type="button" 
            data-bs-toggle="collapse" 
            data-bs-target="#navbarMenu"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarMenu">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="about.php">About</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="contact.php">Contact</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link fw-semibold" href="cart.php">Cart</a>
                </li>

                <?php if (isLoggedIn()) : ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="my_orders.php">My Orders</a>
                    </li>
                <?php endif; ?>

                <?php if (isAdmin()) : ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-danger" href="admin/orders.php">
                            Admin Panel
                        </a>
                    </li>
                <?php endif; ?>

                <?php if (isLoggedIn()) : ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold text-danger" href="logout.php">
                            Logout
                        </a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link fw-semibold" href="login.php">
                            Login
                        </a>
                    </li>
                <?php endif; ?>

            </ul>
        </div>

    </div>
</nav>