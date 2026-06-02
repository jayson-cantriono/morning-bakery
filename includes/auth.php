<?php
require_once __DIR__ . '/config.php';
if (!isLoggedIn()) { header('Location: login.php'); exit; }
?>
