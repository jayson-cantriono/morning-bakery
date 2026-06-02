<?php
include 'includes/config.php';

$error = '';

if (isset($_POST['login'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM users WHERE email = ? LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "s",
        $email
    );

    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);
    $user = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['fullname'] = $user['fullname'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        if ($user['role'] == 'admin') {
            header("Location: admin/dashboard.php");
        } else {
            header("Location: index.php");
        }

        exit;
    } else {
        $error = "Email atau password salah";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1.0"
    >

    <title>Login - Morning Bakery</title>

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

<div class="container py-5" style="max-width:520px;">

    <div class="table-card">

        <h2 class="section-title mb-3">
            Login
        </h2>

        <?php if ($error): ?>
            <div class="alert alert-danger">
                <?= e($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST">

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
                name="login" 
                class="btn btn-maroon w-100"
            >
                Login
            </button>

        </form>

        <p class="mt-3 mb-1">
            Don't have an account?
            <a href="register.php">Register</a>
        </p>

        <p class="small text-muted">
            Admin: admin@morningbakery.com / 123456
        </p>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>