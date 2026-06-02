<?php
include 'admin_auth.php';

$orders = mysqli_query(
    $conn,
    "SELECT
        o.*,
        u.fullname
    FROM orders o
    JOIN users u ON u.id = o.user_id
    ORDER BY o.id DESC"
);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Orders</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body class="bakery-body">

<div class="container-fluid">
    <div class="row">

        <?php include 'sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="section-title mb-4">Orders</h1>

            <div class="table-card">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>User</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Delivery</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while($o = mysqli_fetch_assoc($orders)): ?>

                        <tr>
                            <td>#<?= $o['id']; ?></td>

                            <td><?= e($o['fullname']); ?></td>

                            <td><?= rupiah($o['total_price']); ?></td>

                            <td>
                                <span class="badge bg-warning text-dark">
                                    <?= e($o['payment_status']); ?>
                                </span>
                            </td>

                            <td>
                                <span class="badge bg-secondary">
                                    <?= e($o['delivery_status']); ?>
                                </span>
                            </td>

                            <td><?= e($o['order_date']); ?></td>

                            <td>
                                <a 
                                    href="order_detail.php?id=<?= $o['id']; ?>" 
                                    class="btn btn-maroon btn-sm"
                                >
                                    Detail
                                </a>
                            </td>
                        </tr>

                    <?php endwhile; ?>

                    </tbody>

                </table>

            </div>

        </main>

    </div>
</div>

</body>
</html>