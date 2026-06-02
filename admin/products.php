<?php
include 'admin_auth.php';

$products = mysqli_query(
    $conn,
    "SELECT 
        p.*,
        c.category_name
    FROM products p
    JOIN categories c 
        ON c.id = p.category_id
    ORDER BY p.id DESC"
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

    <title>Manage Products</title>

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

            <div class="d-flex justify-content-between align-items-center mb-4">

                <h1 class="section-title mb-0">
                    Manage Products
                </h1>

                <a 
                    href="add_product.php" 
                    class="btn btn-maroon"
                >
                    Add Product
                </a>

            </div>

            <div class="table-card table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Weight</th>
                            <th>Best Seller</th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php while ($product = mysqli_fetch_assoc($products)): ?>

                        <tr>

                            <td>
                                <img 
                                    src="../assets/products/<?= e($product['image']); ?>"
                                    alt="<?= e($product['product_name']); ?>"
                                    style="
                                        width:70px;
                                        height:70px;
                                        object-fit:cover;
                                        border-radius:12px;
                                    "
                                    onerror="this.src='../assets/products/placeholder.jpg'"
                                >
                            </td>

                            <td>
                                <b>
                                    <?= e($product['product_name']); ?>
                                </b>
                            </td>

                            <td>
                                <?= e($product['category_name']); ?>
                            </td>

                            <td>
                                <?= rupiah($product['price']); ?>
                            </td>

                            <td>
                                <?php if ($product['stock'] <= 0): ?>
                                    <span class="badge bg-danger">
                                        Out of Stock
                                    </span>
                                <?php elseif ($product['stock'] <= 5): ?>
                                    <span class="badge bg-warning text-dark">
                                        <?= e($product['stock']); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-success">
                                        <?= e($product['stock']); ?>
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <?= e($product['weight']); ?>
                            </td>

                            <td>
                                <?php if ($product['best_seller'] == 1): ?>
                                    <span class="badge bg-danger">
                                        Best Seller
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">
                                        No
                                    </span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <a 
                                    href="edit_product.php?id=<?= $product['id']; ?>" 
                                    class="btn btn-sm btn-warning"
                                >
                                    Edit
                                </a>

                                <a 
                                    href="delete_product.php?id=<?= $product['id']; ?>" 
                                    class="btn btn-sm btn-danger"
                                    onclick="return confirm('Are you sure want to delete this product?')"
                                >
                                    Delete
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