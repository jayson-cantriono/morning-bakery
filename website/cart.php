<?php
include 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';

if (isset($_POST['update'])) {

    foreach ($_POST['qty'] as $cart_id => $qty) {
        $cart_id = (int)$cart_id;
        $qty = max(1, (int)$qty);

        $cartItem = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT 
                    cart.id,
                    products.stock
                 FROM cart
                 JOIN products 
                    ON products.id = cart.product_id
                 WHERE cart.id = $cart_id
                 AND cart.user_id = $user_id"
            )
        );

        if ($cartItem) {
            if ($qty > $cartItem['stock']) {
                $qty = $cartItem['stock'];
                $error = 'Some quantities were adjusted because they exceeded available stock.';
            }

            if ($qty <= 0) {
                mysqli_query(
                    $conn,
                    "DELETE FROM cart 
                     WHERE id = $cart_id 
                     AND user_id = $user_id"
                );
            } else {
                mysqli_query(
                    $conn,
                    "UPDATE cart 
                     SET qty = $qty 
                     WHERE id = $cart_id 
                     AND user_id = $user_id"
                );
            }
        }
    }
}

if (isset($_GET['delete'])) {
    $cart_id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM cart 
         WHERE id = $cart_id 
         AND user_id = $user_id"
    );

    header('Location: cart.php');
    exit;
}

$items = mysqli_query(
    $conn,
    "SELECT 
        cart.id AS cart_id,
        cart.qty,
        products.*
     FROM cart
     JOIN products 
        ON products.id = cart.product_id
     WHERE cart.user_id = $user_id"
);

$total = 0;
$can_checkout = true;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>Cart</title>

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
        Shopping Cart
    </h1>

    <?php if ($error): ?>
        <div class="alert alert-warning">
            <?= e($error); ?>
        </div>
    <?php endif; ?>

    <form method="POST">

        <div class="table-card table-responsive">

            <table class="table align-middle">

                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Available Stock</th>
                        <th>Qty</th>
                        <th>Subtotal</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($items) > 0): ?>

                        <?php while ($row = mysqli_fetch_assoc($items)): ?>

                            <?php
                            if ($row['stock'] <= 0 || $row['qty'] > $row['stock']) {
                                $can_checkout = false;
                            }

                            $subtotal = $row['price'] * $row['qty'];
                            $total += $subtotal;
                            ?>

                            <tr>
                                <td>
                                    <b><?= e($row['product_name']); ?></b>

                                    <?php if ($row['stock'] <= 0): ?>
                                        <br>
                                        <span class="badge bg-danger">
                                            Out of Stock
                                        </span>
                                    <?php endif; ?>
                                </td>

                                <td>
                                    <?= rupiah($row['price']); ?>
                                </td>

                                <td>
                                    <?= e($row['stock']); ?>
                                </td>

                                <td>
                                    <input 
                                        type="number"
                                        name="qty[<?= $row['cart_id']; ?>]"
                                        value="<?= $row['qty']; ?>"
                                        min="1"
                                        max="<?= $row['stock']; ?>"
                                        class="form-control qty-input"
                                        style="width:90px"
                                        <?= $row['stock'] <= 0 ? 'disabled' : ''; ?>
                                    >
                                </td>

                                <td>
                                    <?= rupiah($subtotal); ?>
                                </td>

                                <td>
                                    <a 
                                        href="cart.php?delete=<?= $row['cart_id']; ?>"
                                        class="btn btn-sm btn-outline-danger"
                                    >
                                        Delete
                                    </a>
                                </td>
                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center text-muted">
                                Your cart is empty.
                            </td>
                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

            <div class="d-flex justify-content-between align-items-center">

                <h4>
                    Total: <?= rupiah($total); ?>
                </h4>

                    <?php if ($can_checkout && $total > 0): ?>

                        <a 
                            href="checkout.php"
                            class="btn btn-maroon"
                        >
                            Checkout
                        </a>

                    <?php else: ?>

                        <button 
                            class="btn btn-secondary"
                            disabled
                        >
                            Checkout
                        </button>

                    <?php endif; ?>
                </div>

            </div>

            <?php if (!$can_checkout && $total > 0): ?>
                <div class="alert alert-danger mt-3 mb-0">
                    Some products are out of stock or quantity exceeds available stock.
                    Please update your cart before checkout.
                </div>
            <?php endif; ?>

        </div>

    </form>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>