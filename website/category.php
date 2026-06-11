<?php
include 'includes/config.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$category = mysqli_fetch_assoc(
    mysqli_query(
        $conn,
        "SELECT * FROM categories WHERE id=$id"
    )
);

if (!$category) {
    die("Category not found");
}

$products = mysqli_query(
    $conn,
    "SELECT *
     FROM products
     WHERE category_id=$id
     ORDER BY product_name ASC"
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

    <title>
        <?= e($category['category_name']) ?>
    </title>

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
        <?= e($category['category_name']) ?>
    </h1>

    <div class="row g-4">

        <?php while($p = mysqli_fetch_assoc($products)): ?>

            <div class="col-md-3 col-sm-6">

                <div class="product-card h-100">

                    <div class="product-img">
                        <img
                            src="assets/products/<?= e($p['image']) ?>"
                            alt="<?= e($p['product_name']) ?>"
                        >
                    </div>

                    <div class="product-body">

                        <?php if($p['best_seller']) : ?>
                            <span class="badge bg-danger">
                                Best Seller
                            </span>
                        <?php endif; ?>

                        <h5 class="fw-bold mt-2">
                            <?= e($p['product_name']) ?>
                        </h5>

                        <p class="small text-muted">
                            <?= e($p['weight']) ?>
                        </p>

                        <p class="price">
                            <?= rupiah($p['price']) ?>
                        </p>

                        <p>
                            Stock:
                            <b><?= $p['stock'] ?></b>
                        </p>

                        <a
                            href="product_detail.php?id=<?= $p['id'] ?>"
                            class="btn btn-maroon w-100"
                        >
                            View Detail
                        </a>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>