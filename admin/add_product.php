<?php
include 'admin_auth.php';

if (isset($_POST['save'])) {
    $category_id = (int)$_POST['category_id'];
    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $stock = (int)$_POST['stock'];
    $weight = mysqli_real_escape_string($conn, $_POST['weight']);
    $best_seller = (int)$_POST['best_seller'];

    $image = 'placeholder.jpg';

    if (!empty($_FILES['image']['name'])) {
        $image = time() . '_' . basename($_FILES['image']['name']);

        move_uploaded_file(
            $_FILES['image']['tmp_name'],
            '../assets/products/' . $image
        );
    }

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO products (
            category_id,
            product_name,
            description,
            price,
            stock,
            weight,
            image,
            best_seller
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?)"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'issdissi',
        $category_id,
        $product_name,
        $description,
        $price,
        $stock,
        $weight,
        $image,
        $best_seller
    );

    mysqli_stmt_execute($stmt);

    header('Location: products.php');
    exit;
}

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
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

    <title>Add Product</title>

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
                Add Product
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
                        <option value="">
                            Select Category
                        </option>

                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>

                            <option value="<?= $category['id']; ?>">
                                <?= e($category['category_name']); ?>
                            </option>

                        <?php endwhile; ?>
                    </select>

                    <input 
                        type="text"
                        name="product_name"
                        class="form-control mb-3"
                        placeholder="Product Name"
                        required
                    >

                    <textarea 
                        name="description"
                        class="form-control mb-3"
                        placeholder="Description"
                        rows="4"
                    ></textarea>

                    <input 
                        type="number"
                        name="price"
                        class="form-control mb-3"
                        placeholder="Price"
                        required
                    >

                    <input 
                        type="number"
                        name="stock"
                        class="form-control mb-3"
                        placeholder="Stock"
                        min="0"
                        value="0"
                    >

                    <input 
                        type="text"
                        name="weight"
                        class="form-control mb-3"
                        placeholder="Weight, example: 270 g / 1 pcs"
                    >

                    <select 
                        name="best_seller"
                        class="form-select mb-3"
                    >
                        <option value="0">
                            Not Best Seller
                        </option>

                        <option value="1">
                            Best Seller
                        </option>
                    </select>

                    <input 
                        type="file"
                        name="image"
                        class="form-control mb-3"
                        accept="image/*"
                    >

                    <button 
                        type="submit"
                        name="save"
                        class="btn btn-maroon"
                    >
                        Save
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