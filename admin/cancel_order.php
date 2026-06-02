<?php
include 'admin_auth.php';

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$id = (int)$_GET['id'];

$update = mysqli_prepare(
    $conn,
    "UPDATE orders
     SET delivery_status = 'Cancelled'
     WHERE id = ?"
);

mysqli_stmt_bind_param(
    $update,
    "i",
    $id
);

mysqli_stmt_execute($update);

header("Location: orders.php");
exit;