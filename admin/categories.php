<?php
include 'admin_auth.php';

if (isset($_POST['add'])) {
    $category_name = mysqli_real_escape_string($conn, $_POST['category_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);

    mysqli_query(
        $conn,
        "INSERT INTO categories (
            category_name,
            description
        ) VALUES (
            '$category_name',
            '$description'
        )"
    );

    header('Location: categories.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM categories
         WHERE id = $id"
    );

    header('Location: categories.php');
    exit;
}

$categories = mysqli_query(
    $conn,
    "SELECT *
     FROM categories
     ORDER BY id DESC"
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

    <title>Manage Categories</title>

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
                Manage Categories
            </h1>

            <div class="table-card mb-4">

                <form 
                    method="POST" 
                    class="row g-3"
                >

                    <div class="col-md-4">
                        <input 
                            type="text"
                            name="category_name"
                            class="form-control"
                            placeholder="Category Name"
                            required
                        >
                    </div>

                    <div class="col-md-6">
                        <input 
                            type="text"
                            name="description"
                            class="form-control"
                            placeholder="Description"
                        >
                    </div>

                    <div class="col-md-2">
                        <button 
                            type="submit"
                            name="add"
                            class="btn btn-maroon w-100"
                        >
                            Add
                        </button>
                    </div>

                </form>

            </div>

            <div class="table-card table-responsive">

                <table class="table align-middle">

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Description</th>
                            <th width="120">Action</th>
                        </tr>
                    </thead>

                    <tbody>

                        <?php while ($category = mysqli_fetch_assoc($categories)): ?>

                            <tr>
                                <td>
                                    <b>
                                        <?= e($category['category_name']); ?>
                                    </b>
                                </td>

                                <td>
                                    <?= e($category['description']); ?>
                                </td>

                                <td>
                                    <a 
                                        href="categories.php?delete=<?= $category['id']; ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Delete this category?')"
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