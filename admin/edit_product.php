<?php
include 'image_helper.php';
include 'admin_auth.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$productQuery = mysqli_query(
    $conn,
    "SELECT * FROM products WHERE id = $id"
);

$product = mysqli_fetch_assoc($productQuery);

if (!$product) {
    die("Product not found");
}

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
);

if (isset($_POST['update'])) {
    $category_id = (int)$_POST['category_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $best_seller = (int)$_POST['best_seller'];

    $image = $product['image'];

    $image = $product['image'];

if (!empty($_FILES['image']['name'])) {

    $image = uploadProductImage(
        $_FILES['image'],
        $product['image']
    );

    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE products
         SET category_id = ?,
             product_name = ?,
             description = ?,
             price = ?,
             stock = ?,
             weight = ?,
             image = ?,
             best_seller = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "issdissii",
        $category_id,
        $product_name,
        $description,
        $price,
        $stock,
        $weight,
        $image,
        $best_seller,
        $id
    );

    mysqli_stmt_execute($stmt);

    header("Location: products.php");
    exit;
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

    <title>Edit Product</title>

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
                Edit Product
            </h1>

            <div class="table-card">

                <form 
                    method="POST" 
                    enctype="multipart/form-data"
                >

                    <select 
                        name="category_id" 
                        class="form-select mb-3"
                        required
                    >
                        <?php while ($cat = mysqli_fetch_assoc($categories)): ?>
                            <option 
                                value="<?= $cat['id']; ?>"
                                <?= $cat['id'] == $product['category_id'] ? 'selected' : ''; ?>
                            >
                                <?= e($cat['category_name']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <input 
                        type="text"
                        name="product_name"
                        class="form-control mb-3"
                        value="<?= e($product['product_name']); ?>"
                        placeholder="Product Name"
                        required
                    >

                    <textarea 
                        name="description"
                        class="form-control mb-3"
                        placeholder="Description"
                        required
                    ><?= e($product['description']); ?></textarea>

                    <input 
                        type="number"
                        name="price"
                        class="form-control mb-3"
                        value="<?= e($product['price']); ?>"
                        placeholder="Price"
                        required
                    >

                    <input 
                        type="number"
                        name="stock"
                        class="form-control mb-3"
                        value="<?= e($product['stock']); ?>"
                        placeholder="Stock"
                        required
                    >

                    <input 
                        type="text"
                        name="weight"
                        class="form-control mb-3"
                        value="<?= e($product['weight']); ?>"
                        placeholder="Weight"
                    >

                    <select 
                        name="best_seller"
                        class="form-select mb-3"
                    >
                        <option 
                            value="0"
                            <?= $product['best_seller'] == 0 ? 'selected' : ''; ?>
                        >
                            Not Best Seller
                        </option>

                        <option 
                            value="1"
                            <?= $product['best_seller'] == 1 ? 'selected' : ''; ?>
                        >
                            Best Seller
                        </option>
                    </select>

                    <?php if (!empty($product['image'])): ?>
                        <div class="mb-3">
                            <p class="mb-2">Current Image:</p>

                            <img 
                                src="../assets/products/<?= e($product['image']); ?>"
                                style="
                                    width:120px;
                                    height:120px;
                                    object-fit:cover;
                                    border-radius:12px;
                                "
                            >
                        </div>
                    <?php endif; ?>

                    <input 
                        type="file"
                        name="image"
                        class="form-control mb-3"
                        accept="image/*"
                    >

                    <button 
                        type="submit"
                        name="update"
                        class="btn btn-maroon"
                    >
                        Update
                    </button>

                    <a 
                        href="products.php"
                        class="btn btn-secondary"
                    >
                        Back
                    </a>

                </form>

            </div>

        </main>

    </div>
</div>

</body>
</html>