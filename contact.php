<?php include 'includes/config.php'; ?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>Contact Us</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" 
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .contact-icon {
            width: 52px;
            height: 52px;
            border-radius: 50%;
            background: #fff3d9;
            color: #7A0F1C;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            flex-shrink: 0;
        }

        .contact-item {
            display: flex;
            gap: 18px;
            align-items: flex-start;
            margin-bottom: 28px;
        }

        .contact-item h5 {
            color: #7A0F1C;
            font-weight: 800;
            margin-bottom: 5px;
        }

        .contact-item p {
            margin-bottom: 0;
            color: #4b3a30;
        }

        .hours-box {
            background: #fff8ec;
            border-radius: 22px;
            padding: 28px;
            height: 100%;
            border: 1px solid #f1dec3;
        }

        .hours-box h4 {
            color: #7A0F1C;
            font-weight: 900;
            margin-bottom: 20px;
        }
    </style>
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <h1 class="section-title mb-4">
        Contact Us
    </h1>

    <div class="table-card">

        <div class="row g-5">

            <div class="col-lg-7">

                <div class="contact-item">

                    <div class="contact-icon">
                        <i class="bi bi-instagram"></i>
                    </div>

                    <div>
                        <h5>Instagram</h5>
                        <p>@morningbakery</p>
                    </div>

                </div>

                <div class="contact-item">

                    <div class="contact-icon">
                        <i class="bi bi-envelope-fill"></i>
                    </div>

                    <div>
                        <h5>Email</h5>
                        <p>info@morningbakery.com</p>
                    </div>

                </div>

                <div class="contact-item">

                    <div class="contact-icon">
                        <i class="bi bi-telephone-fill"></i>
                    </div>

                    <div>
                        <h5>Phone</h5>
                        <p>+62 812 3456 7890</p>
                    </div>

                </div>

                <div class="contact-item mb-0">

                    <div class="contact-icon">
                        <i class="bi bi-geo-alt-fill"></i>
                    </div>

                    <div>
                        <h5>Address</h5>
                        <p>Morning Bakery Store, Batam, Indonesia</p>
                    </div>

                </div>

            </div>

            <div class="col-lg-5">

                <div class="hours-box">

                    <h4>
                        Opening Hours
                    </h4>

                    <p>
                        <b>Monday - Friday</b>
                        <br>
                        08:00 - 20:00
                    </p>

                    <hr>

                    <p>
                        <b>Saturday - Sunday</b>
                        <br>
                        08:00 - 22:00
                    </p>

                    <hr>

                    <p class="mb-0">
                        Fresh bread baked every morning.
                    </p>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>