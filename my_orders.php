<?php
include 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];

if (isset($_POST['upload'])) {
    $order_id = (int)$_POST['order_id'];

    if (!empty($_FILES['payment']['name'])) {
        $file_name = time() . "_" . basename($_FILES['payment']['name']);
        $tmp = $_FILES['payment']['tmp_name'];

        move_uploaded_file(
            $tmp,
            "assets/payment/" . $file_name
        );

        $update = mysqli_prepare(
            $conn,
            "UPDATE orders
             SET payment_proof = ?,
                 payment_status = 'Pending'
             WHERE id = ?
             AND user_id = ?
             AND payment_status != 'Verified'"
        );

        mysqli_stmt_bind_param(
            $update,
            "sii",
            $file_name,
            $order_id,
            $user_id
        );

        mysqli_stmt_execute($update);
    }
}

$orders = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     WHERE user_id = $user_id
     ORDER BY id DESC"
);

function badgePayment($status)
{
    if ($status == 'Verified') {
        return 'bg-success';
    }

    if ($status == 'Rejected') {
        return 'bg-danger';
    }

    return 'bg-warning text-dark';
}

function badgeDelivery($status)
{
    if ($status == 'Delivered') {
        return 'bg-success';
    }

    if ($status == 'Cancelled') {
        return 'bg-danger';
    }

    if ($status == 'Processing') {
        return 'bg-warning text-dark';
    }

    return 'bg-secondary';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>My Orders</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link 
        rel="stylesheet" 
        href="assets/css/style.css"
    >
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <h1 class="section-title mb-4">
        My Orders
    </h1>

    <div class="table-card table-responsive">

        <table class="table align-middle">

            <thead>
                <tr>
                    <th>Products</th>
                    <th>Total</th>
                    <th>Payment Status</th>
                    <th>Delivery Status</th>
                    <th>Payment Proof</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>

                <?php while ($order = mysqli_fetch_assoc($orders)): ?>

                    <?php
                    $order_id = $order['id'];

                    $items = mysqli_query(
                        $conn,
                        "SELECT 
                            oi.qty,
                            oi.subtotal,
                            p.product_name
                         FROM order_items oi
                         JOIN products p 
                            ON p.id = oi.product_id
                         WHERE oi.order_id = $order_id"
                    );
                    ?>

                    <tr>
                        <td>
                            <?php while ($item = mysqli_fetch_assoc($items)): ?>
                                <div class="mb-1">
                                    <b><?= e($item['product_name']); ?></b>
                                    x<?= $item['qty']; ?>
                                    <br>
                                    <small class="text-muted">
                                        <?= rupiah($item['subtotal']); ?>
                                    </small>
                                </div>
                            <?php endwhile; ?>
                        </td>

                        <td>
                            <?= rupiah($order['total_price']); ?>
                        </td>

                        <td>
                            <span class="badge <?= badgePayment($order['payment_status']); ?>">
                                <?= e($order['payment_status']); ?>
                            </span>
                        </td>

                        <td>
                            <span class="badge <?= badgeDelivery($order['delivery_status']); ?>">
                                <?= e($order['delivery_status']); ?>
                            </span>
                        </td>

                        <td>
                            <?php if (!empty($order['payment_proof'])): ?>

                                <a 
                                    href="assets/payment/<?= e($order['payment_proof']); ?>" 
                                    target="_blank"
                                    class="btn btn-sm btn-outline-dark"
                                >
                                    View Proof
                                </a>

                            <?php else: ?>

                                -

                            <?php endif; ?>
                        </td>

                        <td>

                            <?php if ($order['payment_status'] == 'Verified'): ?>

                                <span class="text-success fw-semibold">
                                    Verified
                                </span>

                            <?php else: ?>

                                <form 
                                    method="POST"
                                    enctype="multipart/form-data"
                                >
                                    <input 
                                        type="hidden"
                                        name="order_id"
                                        value="<?= $order['id']; ?>"
                                    >

                                    <input 
                                        type="file"
                                        name="payment"
                                        class="form-control mb-2"
                                        accept="image/*"
                                        required
                                    >

                                    <button 
                                        type="submit"
                                        name="upload"
                                        class="btn btn-maroon btn-sm"
                                    >
                                        <?= empty($order['payment_proof']) ? 'Upload Proof' : 'Upload Ulang'; ?>
                                    </button>
                                </form>

                                <?php if ($order['payment_status'] == 'Rejected'): ?>
                                    <small class="text-danger d-block mt-1">
                                        Payment rejected. Please upload again.
                                    </small>
                                <?php endif; ?>

                            <?php endif; ?>

                        </td>
                    </tr>

                <?php endwhile; ?>

            </tbody>

        </table>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>