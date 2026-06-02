<?php
include 'admin_auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id = (int)$_POST['id'];

    $payment_status = $_POST['payment_status'];
    $delivery_status = $_POST['delivery_status'];

    $old = mysqli_fetch_assoc(
        mysqli_query(
            $conn,
            "SELECT payment_status
             FROM orders
             WHERE id = $id"
        )
    );

    $old_payment = $old['payment_status'];

    $update = mysqli_prepare(
        $conn,
        "UPDATE orders
         SET payment_status = ?,
             delivery_status = ?
         WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $update,
        "ssi",
        $payment_status,
        $delivery_status,
        $id
    );

    mysqli_stmt_execute($update);

    /*
    AUTO STOCK REDUCTION
    Hanya saat Pending -> Verified
    */

    if (
        $old_payment != 'Verified' &&
        $payment_status == 'Verified'
    ) {

        $items = mysqli_query(
            $conn,
            "SELECT *
             FROM order_items
             WHERE order_id = $id"
        );

        while ($item = mysqli_fetch_assoc($items)) {

            mysqli_query(
                $conn,
                "UPDATE products
                 SET stock = stock - {$item['qty']}
                 WHERE id = {$item['product_id']}"
            );

        }

    }

    header("Location: order_detail.php?id=".$id);
    exit;
}

header("Location: orders.php");
exit;