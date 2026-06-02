<?php require_once __DIR__ . '/../includes/config.php'; 
if(!isAdmin()){ header('Location: ../login.php'); exit; } ?>
