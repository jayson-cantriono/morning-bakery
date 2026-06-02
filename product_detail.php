<?php
include 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = mysqli_prepare(
    $conn,
    "SELECT 
        p.*,
        c.category_name
    FROM products p
    JOIN categories c 
        ON c.id = p.category_id
    WHERE p.id = ?"
);

mysqli_stmt_bind_param($stmt, "i", $id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    die("Product not found");
}

$error = '';

if (isset($_POST['add'])) {

    if (!isLoggedIn()) {
        header("Location: login.php");
        exit;
    }

    $user_id = (int)$_SESSION['user_id'];
    $qty = max(1, (int)$_POST['qty']);

    $checkUser = mysqli_prepare(
        $conn,
        "SELECT id FROM users WHERE id = ?"
    );

    mysqli_stmt_bind_param($checkUser, "i", $user_id);
    mysqli_stmt_execute($checkUser);

    $userData = mysqli_fetch_assoc(
        mysqli_stmt_get_result($checkUser)
    );

    if (!$userData) {
        session_destroy();
        header("Location: login.php");
        exit;
    }

    if ($product['stock'] <= 0) {
        $error = "Product is out of stock.";
    } elseif ($qty > $product['stock']) {
        $error = "Quantity cannot be more than available stock.";
    } else {

        $checkCart = mysqli_prepare(
            $conn,
            "SELECT id, qty 
             FROM cart 
             WHERE user_id = ? 
             AND product_id = ?"
        );

        mysqli_stmt_bind_param(
            $checkCart,
            "ii",
            $user_id,
            $id
        );

        mysqli_stmt_execute($checkCart);

        $cart = mysqli_fetch_assoc(
            mysqli_stmt_get_result($checkCart)
        );

        if ($cart) {
            $newQty = $cart['qty'] + $qty;

            if ($newQty > $product['stock']) {
                $error = "Cart quantity cannot be more than available stock.";
            } else {
                $update = mysqli_prepare(
                    $conn,
                    "UPDATE cart 
                     SET qty = ? 
                     WHERE id = ?"
                );

                mysqli_stmt_bind_param(
                    $update,
                    "ii",
                    $newQty,
                    $cart['id']
                );

                mysqli_stmt_execute($update);

                header("Location: cart.php");
                exit;
            }
        } else {
            $insert = mysqli_prepare(
                $conn,
                "INSERT INTO cart (
                    user_id,
                    product_id,
                    qty
                ) VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $insert,
                "iii",
                $user_id,
                $id,
                $qty
            );

            mysqli_stmt_execute($insert);

            header("Location: cart.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title><?= e($product['product_name']); ?></title>

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

    <div class="row g-5 align-items-center">

        <div class="col-md-6">

            <div class="product-card">

                <div 
                    class="product-img"
                    style="height:420px"
                >

                    <img 
                        src="assets/products/<?= e($product['image']); ?>"
                        alt="<?= e($product['product_name']); ?>"
                        onerror="this.src='https://images.unsplash.com/photo-1549931319-a545dcf3bc73?q=80&w=900&auto=format&fit=crop'"
                    >

                </div>

            </div>

        </div>

        <div class="col-md-6">

            <?php if ($product['best_seller'] == 1): ?>
                <span class="badge bg-danger mb-3">
                    Best Seller
                </span>
            <?php endif; ?>

            <h1 class="section-title mt-2">
                <?= e($product['product_name']); ?>
            </h1>

            <p class="text-muted">
                <?= e($product['category_name']); ?>
                •
                <?= e($product['weight']); ?>
            </p>

            <h3 class="price">
                <?= rupiah($product['price']); ?>
            </h3>

            <p>
                <?= e($product['description']); ?>
            </p>

            <p>
                Stock:
                <?php if ($product['stock'] > 0): ?>
                    <b><?= e($product['stock']); ?></b>
                <?php else: ?>
                    <span class="badge bg-danger">
                        Out of Stock
                    </span>
                <?php endif; ?>
            </p>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <?= e($error); ?>
                </div>
            <?php endif; ?>

            <?php if ($product['stock'] > 0): ?>

                <form 
                    method="POST"
                    class="d-flex gap-3 mt-4"
                >

                    <input 
                        type="number"
                        name="qty"
                        value="1"
                        min="1"
                        max="<?= e($product['stock']); ?>"
                        class="form-control"
                        style="max-width:120px"
                    >

                    <button 
                        type="submit"
                        name="add"
                        class="btn btn-maroon"
                    >
                        Add To Cart
                    </button>

                </form>

            <?php else: ?>

                <button 
                    class="btn btn-secondary mt-3"
                    disabled
                >
                    Out of Stock
                </button>

            <?php endif; ?>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>