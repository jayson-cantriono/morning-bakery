<?php
include 'admin_auth.php';

$total_products = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM products")
)[0];

$total_categories = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM categories")
)[0];

$total_orders = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM orders")
)[0];

$total_users = mysqli_fetch_row(
    mysqli_query($conn, "SELECT COUNT(*) FROM users")
)[0];

$total_sales = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COALESCE(SUM(total_price), 0)
         FROM orders
         WHERE payment_status = 'Verified'"
    )
)[0];

$pending_payments = mysqli_fetch_row(
    mysqli_query(
        $conn,
        "SELECT COUNT(*)
         FROM orders
         WHERE payment_status = 'Pending'"
    )
)[0];

$low_stock = mysqli_query(
    $conn,
    "SELECT product_name, stock
     FROM products
     WHERE stock <= 5
     ORDER BY stock ASC
     LIMIT 5"
);

$latest_orders = mysqli_query(
    $conn,
    "SELECT 
        o.id,
        o.total_price,
        o.payment_status,
        o.delivery_status,
        o.order_date,
        u.fullname
     FROM orders o
     JOIN users u
        ON u.id = o.user_id
     ORDER BY o.id DESC
     LIMIT 5"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Admin Dashboard</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

    <style>
        .dashboard-card {
            border: none;
            border-radius: 22px;
            padding: 24px;
            background: #fff;
            box-shadow: 0 10px 30px rgba(0,0,0,.08);
            height: 100%;
        }

        .dashboard-card p {
            margin-bottom: 6px;
            color: #777;
            font-weight: 600;
        }

        .dashboard-card h2 {
            color: #7b1e2b;
            font-weight: 800;
            margin-bottom: 0;
        }

        .dashboard-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: #fff4df;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            margin-bottom: 14px;
        }
    </style>
</head>

<body class="bakery-body">

<div class="container-fluid">
    <div class="row">

        <?php include 'sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="section-title mb-4">
                Dashboard
            </h1>

            <div class="row g-4">

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">🍞</div>
                        <p>Total Products</p>
                        <h2><?= $total_products; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">📂</div>
                        <p>Total Categories</p>
                        <h2><?= $total_categories; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">🧾</div>
                        <p>Total Orders</p>
                        <h2><?= $total_orders; ?></h2>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">👤</div>
                        <p>Total Users</p>
                        <h2><?= $total_users; ?></h2>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">💰</div>
                        <p>Total Sales</p>
                        <h2><?= rupiah($total_sales); ?></h2>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="dashboard-card">
                        <div class="dashboard-icon">⏳</div>
                        <p>Pending Payments</p>
                        <h2><?= $pending_payments; ?></h2>
                    </div>
                </div>

            </div>

            <div class="row g-4 mt-2">

                <div class="col-lg-6">

                    <div class="table-card">

                        <h4 class="mb-3">
                            Latest Orders
                        </h4>

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Customer</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Delivery</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php while ($order = mysqli_fetch_assoc($latest_orders)): ?>

                                    <tr>
                                        <td><?= e($order['fullname']); ?></td>

                                        <td><?= rupiah($order['total_price']); ?></td>

                                        <td>
                                            <?php if ($order['payment_status'] == 'Verified'): ?>
                                                <span class="badge bg-success">Verified</span>
                                            <?php elseif ($order['payment_status'] == 'Rejected'): ?>
                                                <span class="badge bg-danger">Rejected</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <?php if ($order['delivery_status'] == 'Delivered'): ?>
                                                <span class="badge bg-success">Delivered</span>
                                            <?php elseif ($order['delivery_status'] == 'Cancelled'): ?>
                                                <span class="badge bg-danger">Cancelled</span>
                                            <?php elseif ($order['delivery_status'] == 'Processing'): ?>
                                                <span class="badge bg-primary">Processing</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">Waiting</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>

                                <?php endwhile; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

                <div class="col-lg-6">

                    <div class="table-card">

                        <h4 class="mb-3">
                            Low Stock Products
                        </h4>

                        <table class="table align-middle">

                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Stock</th>
                                </tr>
                            </thead>

                            <tbody>

                                <?php if (mysqli_num_rows($low_stock) > 0): ?>

                                    <?php while ($item = mysqli_fetch_assoc($low_stock)): ?>

                                        <tr>
                                            <td><?= e($item['product_name']); ?></td>

                                            <td>
                                                <span class="badge bg-danger">
                                                    <?= e($item['stock']); ?>
                                                </span>
                                            </td>
                                        </tr>

                                    <?php endwhile; ?>

                                <?php else: ?>

                                    <tr>
                                        <td colspan="2" class="text-muted">
                                            No low stock products.
                                        </td>
                                    </tr>

                                <?php endif; ?>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </main>

    </div>
</div>

</body>
</html>