<?php
include 'includes/config.php';

if (!isLoggedIn()) {
    header('Location: login.php');
    exit;
}

$user_id = $_SESSION['user_id'];
$error = '';

$items = mysqli_query(
    $conn,
    "SELECT 
        cart.qty,
        products.*
    FROM cart
    JOIN products 
        ON products.id = cart.product_id
    WHERE cart.user_id = $user_id"
);

$cart = [];
$total = 0;

while ($item = mysqli_fetch_assoc($items)) {
    $item['subtotal'] = $item['price'] * $item['qty'];
    $total += $item['subtotal'];
    $cart[] = $item;
}

if (isset($_POST['checkout']) && count($cart) > 0) {

    foreach ($cart as $item) {
        $checkStock = mysqli_fetch_assoc(
            mysqli_query(
                $conn,
                "SELECT stock
                 FROM products
                 WHERE id = {$item['id']}"
            )
        );

        if (!$checkStock || $checkStock['stock'] < $item['qty']) {
            $error = "Stock produk " . $item['product_name'] . " tidak mencukupi.";
            break;
        }
    }

    if (empty($error)) {
        $receiver_name = mysqli_real_escape_string($conn, $_POST['receiver_name']);
        $phone = mysqli_real_escape_string($conn, $_POST['phone']);
        $address = mysqli_real_escape_string($conn, $_POST['address']);
        $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);

        $order = mysqli_prepare(
            $conn,
            "INSERT INTO orders (
                user_id,
                receiver_name,
                phone,
                address,
                payment_method,
                total_price
            ) VALUES (?, ?, ?, ?, ?, ?)"
        );

        mysqli_stmt_bind_param(
            $order,
            'issssd',
            $user_id,
            $receiver_name,
            $phone,
            $address,
            $payment_method,
            $total
        );

        mysqli_stmt_execute($order);

        $order_id = mysqli_insert_id($conn);

        foreach ($cart as $item) {
            $order_item = mysqli_prepare(
                $conn,
                "INSERT INTO order_items (
                    order_id,
                    product_id,
                    qty,
                    subtotal
                ) VALUES (?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $order_item,
                'iiid',
                $order_id,
                $item['id'],
                $item['qty'],
                $item['subtotal']
            );

            mysqli_stmt_execute($order_item);
        }

        mysqli_query(
            $conn,
            "DELETE FROM cart WHERE user_id = $user_id"
        );

        header('Location: my_orders.php');
        exit;
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

    <title>Checkout - Morning Bakery</title>

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

<div class="container py-5">

    <h1 class="section-title mb-4">
        Checkout
    </h1>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?= e($error); ?>
        </div>
    <?php endif; ?>

    <div class="row g-4">

        <div class="col-md-7">
            <div class="table-card">

                <form method="POST">

                    <input 
                        type="text"
                        name="receiver_name"
                        class="form-control mb-3"
                        placeholder="Receiver Name"
                        required
                    >

                    <input 
                        type="text"
                        name="phone"
                        class="form-control mb-3"
                        placeholder="Phone Number"
                        required
                    >

                    <textarea 
                        name="address"
                        class="form-control mb-3"
                        placeholder="Full Address"
                        required
                    ></textarea>

                    <select 
                        name="payment_method"
                        class="form-select mb-3"
                        id="paymentMethod"
                        required
                    >
                        <option value="Transfer Bank">
                            Transfer Bank
                        </option>

                        <option value="QRIS">
                            QRIS
                        </option>

                        <option value="COD">
                            COD
                        </option>
                    </select>

                    <div class="payment-info mb-4">

                        <h5>Payment Information</h5>

                        <div 
                            class="payment-box"
                            id="bankInfo"
                        >
                            <p class="mb-1">
                                <b>Bank Transfer</b>
                            </p>

                            <p class="mb-1">
                                BCA : <b>1234567890</b>
                            </p>

                            <p class="mb-0">
                                Atas Nama : <b>MORNING BAKERY</b>
                            </p>
                        </div>

                        <div 
                            class="payment-box mt-3 d-none"
                            id="qrisInfo"
                        >
                            <p class="mb-2">
                                <b>QRIS Payment</b>
                            </p>

                            <img 
                                src="assets/images/qris.png"
                                alt="QRIS Morning Bakery"
                                class="qris-img"
                            >

                            <p class="text-muted mt-2 mb-0">
                                Scan QRIS untuk melakukan pembayaran.
                            </p>
                        </div>

                        <div 
                            class="payment-box mt-3 d-none"
                            id="codInfo"
                        >
                            <p class="mb-1">
                                <b>Cash On Delivery</b>
                            </p>

                            <p class="mb-0">
                                Pembayaran dilakukan saat pesanan diterima.
                            </p>
                        </div>

                        <div
    class="alert alert-info mt-3 mb-0"
    id="paymentInstruction"
>

    <b>Payment Instruction</b>

    <br><br>

    1. Lakukan pembayaran sesuai metode yang dipilih.

    <br>

    2. Klik <b>Place Order</b>.

    <br>

    3. Buka menu <b>My Orders</b>.

    <br>

    4. Upload bukti pembayaran.

    <br>

    5. Admin akan memverifikasi pesanan.

</div>
                    </div>

                    <button 
                        type="submit"
                        name="checkout"
                        class="btn btn-maroon w-100"
                        <?= count($cart) == 0 ? 'disabled' : ''; ?>
                    >
                        Place Order
                    </button>

                </form>

            </div>
        </div>

        <div class="col-md-5">
            <div class="table-card">

                <h4 class="mb-3">
                    Order Summary
                </h4>

                <?php if (count($cart) > 0): ?>

                    <?php foreach ($cart as $item): ?>

                        <div class="d-flex justify-content-between mb-2">
                            <span>
                                <?= e($item['product_name']); ?>
                                x<?= $item['qty']; ?>
                            </span>

                            <b>
                                <?= rupiah($item['subtotal']); ?>
                            </b>
                        </div>

                    <?php endforeach; ?>

                    <hr>

                    <h4>
                        Total: <?= rupiah($total); ?>
                    </h4>

                <?php else: ?>

                    <p class="text-muted">
                        Your cart is empty.
                    </p>

                <?php endif; ?>

            </div>
        </div>

    </div>
</div>

<?php include 'includes/footer.php'; ?>

<script>
const paymentMethod = document.getElementById('paymentMethod');
const bankInfo = document.getElementById('bankInfo');
const qrisInfo = document.getElementById('qrisInfo');
const codInfo = document.getElementById('codInfo');
const paymentInstruction = document.getElementById('paymentInstruction');

function updatePaymentInfo() {

    bankInfo.classList.add('d-none');
    qrisInfo.classList.add('d-none');
    codInfo.classList.add('d-none');

    if (paymentMethod.value === 'Transfer Bank') {

        bankInfo.classList.remove('d-none');

        paymentInstruction.className =
            'alert alert-info mt-3 mb-0';

        paymentInstruction.innerHTML = `
            <b>Payment Instruction</b>
            <br><br>
            1. Lakukan transfer ke rekening yang tersedia.
            <br>
            2. Klik <b>Place Order</b>.
            <br>
            3. Buka menu <b>My Orders</b>.
            <br>
            4. Upload bukti pembayaran.
            <br>
            5. Admin akan memverifikasi pesanan.
        `;
    }

    if (paymentMethod.value === 'QRIS') {

        qrisInfo.classList.remove('d-none');

        paymentInstruction.className =
            'alert alert-info mt-3 mb-0';

        paymentInstruction.innerHTML = `
            <b>Payment Instruction</b>
            <br><br>
            1. Scan QRIS yang tersedia.
            <br>
            2. Klik <b>Place Order</b>.
            <br>
            3. Buka menu <b>My Orders</b>.
            <br>
            4. Upload bukti pembayaran.
            <br>
            5. Admin akan memverifikasi pesanan.
        `;
    }

    if (paymentMethod.value === 'COD') {

        codInfo.classList.remove('d-none');

        paymentInstruction.className =
            'alert alert-success mt-3 mb-0';

        paymentInstruction.innerHTML = `
            <b>COD Information</b>
            <br><br>
            1. Klik <b>Place Order</b>.
            <br>
            2. Admin akan memproses pesanan Anda.
            <br>
            3. Pantau status pesanan melalui menu <b>My Orders</b>.
            <br>
            4. Pembayaran dilakukan saat pesanan diterima.
            <br>
            5. Pastikan alamat dan nomor telepon sudah benar.
        `;
    }
}

paymentMethod.addEventListener('change', updatePaymentInfo);

updatePaymentInfo();
</script>

</body>
</html>