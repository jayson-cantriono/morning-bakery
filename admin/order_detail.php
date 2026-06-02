<?php
include 'admin_auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$orderQuery = mysqli_query(
    $conn,
    "SELECT 
        o.*,
        u.fullname
    FROM orders o
    JOIN users u ON u.id = o.user_id
    WHERE o.id = $id"
);

$order = mysqli_fetch_assoc($orderQuery);

if (!$order) {
    die("Order not found");
}

$items = mysqli_query(
    $conn,
    "SELECT 
        oi.*,
        p.product_name
    FROM order_items oi
    JOIN products p ON p.id = oi.product_id
    WHERE oi.order_id = $id"
);

function badgePayment($status)
{
    if ($status == 'Verified') {
        return 'bg-success';
    } elseif ($status == 'Rejected') {
        return 'bg-danger';
    } else {
        return 'bg-warning text-dark';
    }
}

function badgeDelivery($status)
{
    if ($status == 'Delivered') {

        return 'bg-success';

    } elseif ($status == 'Cancelled') {

        return 'bg-danger';

    } elseif ($status == 'Processing') {

        return 'bg-warning text-dark';

    } else {

        return 'bg-secondary';

    }
}

$paymentStatus = $order['payment_status'] ?? 'Pending';
$deliveryStatus = $order['delivery_status'] ?? 'Waiting';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Order Detail</title>

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
                Order #<?= $id; ?>
            </h1>

            <div class="table-card mb-4">

                <h4 class="mb-3">
                    Customer Information
                </h4>

                <p>
                    <b>Customer:</b>
                    <?= e($order['fullname']); ?>
                </p>

                <p>
                    <b>Receiver:</b>
                    <?= e($order['receiver_name']); ?>
                </p>

                <p>
                    <b>Phone:</b>
                    <?= e($order['phone']); ?>
                </p>

                <p>
                    <b>Address:</b>
                    <?= e($order['address']); ?>
                </p>

                <p>
                    <b>Payment Method:</b>
                    <?= e($order['payment_method']); ?>
                </p>

                <p>
                    <b>Payment Status:</b>
                    <span class="badge <?= badgePayment($paymentStatus); ?>">
                        <?= e($paymentStatus); ?>
                    </span>
                </p>

                <p>
                    <b>Delivery Status:</b>
                    <span class="badge <?= badgeDelivery($deliveryStatus); ?>">
                        <?= e($deliveryStatus); ?>
                    </span>
                </p>

                <hr>

                <form method="POST" action="update_status.php">

    <input
        type="hidden"
        name="id"
        value="<?= $id ?>"
    >

    <div class="row">

        <div class="col-md-6">

            <label class="form-label">
                Payment Status
            </label>

            <select
                name="payment_status"
                class="form-select"
            >

                <option value="Pending"
                    <?= ($order['payment_status'] == 'Pending') ? 'selected' : '' ?>>
                    Pending
                </option>

                <option value="Verified"
                    <?= ($order['payment_status'] == 'Verified') ? 'selected' : '' ?>>
                    Verified
                </option>

                <option value="Rejected"
                    <?= ($order['payment_status'] == 'Rejected') ? 'selected' : '' ?>>
                    Rejected
                </option>

            </select>

        </div>

        <div class="col-md-6">

            <label class="form-label">
                Delivery Status
            </label>

            <select
                name="delivery_status"
                class="form-select"
            >

                <option value="Waiting"
                    <?= ($order['delivery_status'] == 'Waiting') ? 'selected' : '' ?>>
                    Waiting
                </option>

                <option value="Processing"
                    <?= ($order['delivery_status'] == 'Processing') ? 'selected' : '' ?>>
                    Processing
                </option>

                <option value="Delivered"
                    <?= ($order['delivery_status'] == 'Delivered') ? 'selected' : '' ?>>
                    Delivered
                </option>

                <option value="Cancelled"
                    <?= ($order['delivery_status'] == 'Cancelled') ? 'selected' : '' ?>>
                    Cancelled
                </option>

            </select>

        </div>

    </div>

    <button
        type="submit"
        class="btn btn-maroon mt-3"
    >
        Update Status
    </button>

</form>

            </div>

            <div class="table-card mb-4">

                <h4 class="mb-3">
                    Payment Proof
                </h4>

                <?php if (!empty($order['payment_proof'])): ?>

                    <a 
                        href="../assets/payment/<?= e($order['payment_proof']); ?>" 
                        target="_blank"
                    >
                        <img 
                            src="../assets/payment/<?= e($order['payment_proof']); ?>" 
                            alt="Payment Proof"
                            style="
                                max-width:350px;
                                width:100%;
                                border-radius:18px;
                                border:1px solid #ddd;
                                box-shadow:0 8px 25px rgba(0,0,0,.12);
                            "
                        >
                    </a>

                    <p class="text-muted mt-2">
                        Klik gambar untuk melihat ukuran penuh.
                    </p>

                <?php else: ?>

                    <div class="alert alert-warning mb-0">
                        User belum upload bukti pembayaran.
                    </div>

                <?php endif; ?>

            </div>

            <div class="table-card">

                <h4 class="mb-3">
                    Ordered Products
                </h4>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Qty</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($item = mysqli_fetch_assoc($items)): ?>

                            <tr>
                                <td>
                                    <?= e($item['product_name']); ?>
                                </td>

                                <td>
                                    <?= $item['qty']; ?>
                                </td>

                                <td>
                                    <?= rupiah($item['subtotal']); ?>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    </tbody>

                </table>

                <h4 class="mt-3">
                    Total:
                    <?= rupiah($order['total_price']); ?>
                </h4>

            </div>

        </main>

    </div>
</div>

</body>
</html>