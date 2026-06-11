<?php
include 'includes/config.php';

$message = '';
$alert_type = 'info';

if (isset($_POST['register'])) {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $stmt = mysqli_prepare(
        $conn,
        "INSERT INTO users (
            fullname,
            email,
            password,
            role
        ) VALUES (?, ?, ?, 'user')"
    );

    mysqli_stmt_bind_param(
        $stmt,
        'sss',
        $fullname,
        $email,
        $hash
    );

    if (mysqli_stmt_execute($stmt)) {
        $message = 'Register berhasil, silakan login.';
        $alert_type = 'success';
    } else {
        $message = 'Email sudah digunakan.';
        $alert_type = 'danger';
    }
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

    <title>Register</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link 
        rel="stylesheet" 
        href="assets/css/style.css"
    >
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<div 
    class="container py-5" 
    style="max-width:520px"
>

    <div class="table-card">

        <h2 class="section-title mb-4">
            Register
        </h2>

        <?php if ($message): ?>

            <div class="alert alert-<?= $alert_type; ?>">
                <?= e($message); ?>
            </div>

        <?php endif; ?>

        <form method="POST">

            <input 
                type="text"
                name="fullname"
                class="form-control mb-3"
                placeholder="Full Name"
                required
            >

            <input 
                type="email"
                name="email"
                class="form-control mb-3"
                placeholder="Email"
                required
            >

            <input 
                type="password"
                name="password"
                class="form-control mb-3"
                placeholder="Password"
                required
            >

            <button 
                type="submit"
                name="register"
                class="btn btn-maroon w-100"
            >
                Register
            </button>

        </form>

        <p class="mt-3 mb-0">
             Already Have an Account?
            <a href="login.php">
                Login
            </a>
        </p>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>