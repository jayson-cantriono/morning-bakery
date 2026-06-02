<?php
include 'admin_auth.php';

$orders = mysqli_query(
    $conn,
    "SELECT
        orders.*,
        users.fullname
     FROM orders
     JOIN users
        ON users.id = orders.user_id
     ORDER BY orders.id DESC"
);
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
    >

    <title>Orders</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body class="bakery-body">

<div class="container-fluid">

    <div class="row">

        <?php include 'sidebar.php'; ?>

        <main class="col-md-9 col-lg-10 p-4">

            <h1 class="section-title mb-4">
                Orders
            </h1>

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

                    <?php while ($o = mysqli_fetch_assoc($orders)) : ?>

                        <tr>

                            <td>
                                #<?= $o['id'] ?>
                            </td>

                            <td>
                                <?= e($o['fullname']) ?>
                            </td>

                            <td>
                                <?= rupiah($o['total_price']) ?>
                            </td>

                            <td>

                                <?php
                                $payment = $o['payment_status'];

                                if ($payment == 'Verified') {
                                    echo '<span class="badge bg-success">Verified</span>';
                                } elseif ($payment == 'Rejected') {
                                    echo '<span class="badge bg-danger">Rejected</span>';
                                } else {
                                    echo '<span class="badge bg-warning text-dark">Pending</span>';
                                }
                                ?>

                            </td>

                            <td>

                                <?php
                                        $delivery = $o['delivery_status'];

                                        if ($delivery == 'Delivered') {
                                            echo '<span class="badge bg-success">Delivered</span>';
                                        } elseif ($delivery == 'Processing') {
                                            echo '<span class="badge bg-warning text-dark">Processing</span>';
                                        } elseif ($delivery == 'Cancelled') {
                                            echo '<span class="badge bg-danger">Cancelled</span>';
                                        } else {
                                            echo '<span class="badge bg-secondary">Waiting</span>';
                                        }
                                        ?>

                            </td>

                            <td>
                                <?= $o['order_date'] ?>
                            </td>

                            <td>

                                <a
                                    href="order_detail.php?id=<?= $o['id'] ?>"
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