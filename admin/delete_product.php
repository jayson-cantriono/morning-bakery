<?php include 'admin_auth.php'; $id=(int)($_GET['id']??0); mysqli_query($conn,"DELETE FROM products WHERE id=$id"); header('Location: products.php'); exit; ?>
