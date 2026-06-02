<?php
include 'includes/config.php';

$keyword = isset($_GET['q']) ? trim($_GET['q']) : '';
$category_id = isset($_GET['category']) ? (int)$_GET['category'] : 0;

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
);

$sql = "
    SELECT 
        p.*,
        c.category_name
    FROM products p
    JOIN categories c ON c.id = p.category_id
    WHERE 1
";

$params = [];
$types = "";

if ($keyword != '') {
    $sql .= " AND (
        p.product_name LIKE ?
        OR c.category_name LIKE ?
        OR p.description LIKE ?
    )";

    $search = "%" . $keyword . "%";

    $params[] = $search;
    $params[] = $search;
    $params[] = $search;

    $types .= "sss";
}

if ($category_id > 0) {
    $sql .= " AND p.category_id = ?";
    $params[] = $category_id;
    $types .= "i";
}

$sql .= " ORDER BY p.id DESC";

$stmt = mysqli_prepare($conn, $sql);

if (!empty($params)) {
    mysqli_stmt_bind_param($stmt, $types, ...$params);
}

mysqli_stmt_execute($stmt);
$products = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Products - Morning Bakery</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .product-card {
            position: relative;
            overflow: hidden;
            transition: .3s ease;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,.12);
        }

        .product-img img {
            transition: .4s ease;
        }

        .product-card:hover .product-img img {
            transform: scale(1.05);
        }

        .best-ribbon {
            position: absolute;
            top: 18px;
            right: 18px;
            background: #dc3545;
            color: #fff;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            z-index: 10;
        }

        .product-body {
            min-height: 290px;
        }
    </style>
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <div class="mb-4">
        <h1 class="section-title mb-2">
            Product Catalogue
        </h1>

        <p class="text-muted">
            Find your favorite breads, cakes, pastries, and bakery products.
        </p>
    </div>

    <form method="GET" class="row g-3 mb-5">

        <div class="col-md-6">
            <input 
                type="text"
                name="q"
                class="form-control"
                placeholder="Search bread, cake, pastry..."
                value="<?= e($keyword); ?>"
            >
        </div>

        <div class="col-md-4">
            <select name="category" class="form-select">
                <option value="0">All Categories</option>

                <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                    <option 
                        value="<?= $cat['id']; ?>"
                        <?= $category_id == $cat['id'] ? 'selected' : ''; ?>
                    >
                        <?= e($cat['category_name']); ?>
                    </option>
                <?php endwhile; ?>

            </select>
        </div>

        <div class="col-md-2">
            <button class="btn btn-maroon w-100">
                Search
            </button>
        </div>

    </form>

    <div class="row g-4">

        <?php if (mysqli_num_rows($products) > 0): ?>

            <?php while ($product = mysqli_fetch_assoc($products)): ?>

                <div class="col-md-3 col-sm-6">

                    <div class="product-card h-100 d-flex flex-column">

                        <?php if ($product['best_seller'] == 1): ?>
                            <div class="best-ribbon">
                                Best Seller
                            </div>
                        <?php endif; ?>

                        <div class="product-img">
                            <img 
                                src="assets/products/<?= e($product['image']); ?>"
                                alt="<?= e($product['product_name']); ?>"
                                onerror="this.src='https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=600&auto=format&fit=crop'"
                            >
                        </div>

                        <div class="product-body d-flex flex-column flex-grow-1">

                            <h5 class="fw-bold">
                                <?= e($product['product_name']); ?>
                            </h5>

                            <p class="small text-muted mb-2">
                                <?= e($product['category_name']); ?>
                                •
                                <?= e($product['weight']); ?>
                            </p>

                            <p class="price mb-2">
                                <?= rupiah($product['price']); ?>
                            </p>

                            <p class="small text-muted">
                                Stock:
                                <b><?= e($product['stock']); ?></b>
                            </p>

                            <div class="mt-auto">

                                <?php if ($product['stock'] > 0): ?>

                                    <a 
                                        href="product_detail.php?id=<?= $product['id']; ?>" 
                                        class="btn btn-maroon w-100"
                                    >
                                        Order
                                    </a>

                                <?php else: ?>

                                    <button 
                                        class="btn btn-secondary w-100"
                                        disabled
                                    >
                                        Out of Stock
                                    </button>

                                <?php endif; ?>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        <?php else: ?>

            <div class="col-12">
                <div class="alert alert-warning">
                    Product not found.
                </div>
            </div>

        <?php endif; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>