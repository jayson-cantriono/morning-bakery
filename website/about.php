<?php
include 'includes/config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>About - Morning Bakery</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .about-title {
            color: #7A0F1C;
            font-weight: 900;
            font-size: 38px;
            margin-bottom: 20px;
        }

        .about-text {
            color: #2B160F;
            line-height: 1.8;
            font-size: 16px;
            text-align: justify;
        }

        .about-img-box {
            background: white;
            padding: 14px;
            border-radius: 18px;
            box-shadow: 0 10px 25px rgba(0,0,0,.08);
        }

        .about-img-box img {
            width: 100%;
            height: 360px;
            object-fit: cover;
            border-radius: 12px;
        }

        .mission-list li {
            margin-bottom: 12px;
            line-height: 1.7;
        }

        @media (max-width: 768px) {
            .about-title {
                font-size: 32px;
            }

            .about-img-box img {
                height: 260px;
            }
        }
    </style>
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <h1 class="section-title mb-4">
        About Us
    </h1>

    <div class="table-card mb-5">

        <div class="row align-items-center g-4">

            <div class="col-lg-6">

                <h2 class="about-title">
                    One Stop Bakery Store
                </h2>

                <p class="about-text">
                    Morning Bakery is a modern bakery store that provides a variety
                    of fresh breads, cakes, pastries, and classic bakery products.
                    Every product is prepared with selected ingredients to deliver
                    a soft texture, delicious taste, and consistent quality.
                </p>

                <p class="about-text">
                    We believe that bakery products are not only food, but also part
                    of daily moments with family, friends, and loved ones. Through
                    our online catalogue system, customers can browse products,
                    place orders, and track their order status more easily.
                </p>

            </div>

            <div class="col-lg-5 offset-lg-1">

                <div class="about-img-box">
                    <img 
                        src="https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=900&auto=format&fit=crop"
                        alt="Morning Bakery Store"
                    >
                </div>

            </div>

        </div>

    </div>

    <div class="table-card">

        <div class="row align-items-center g-4">

            <div class="col-lg-5">

                <div class="about-img-box">
                    <img 
                        src="https://images.unsplash.com/photo-1608198093002-ad4e005484ec?q=80&w=900&auto=format&fit=crop"
                        alt="Bakery Product"
                    >
                </div>

            </div>

            <div class="col-lg-6 offset-lg-1">

                <h2 class="about-title">
                    Our Vision and Mission
                </h2>

                <p class="about-text">
                    Our vision is to become a trusted bakery store that provides
                    fresh, high quality, and affordable bakery products for every
                    customer.
                </p>

                <p class="about-text mb-2">
                    Our missions are:
                </p>

                <ul class="mission-list">
                    <li>
                        Continuously provide fresh bakery products with consistent quality.
                    </li>

                    <li>
                        Make the ordering process easier through a modern website system.
                    </li>

                    <li>
                        Maintain good service by providing order status and payment verification.
                    </li>

                    <li>
                        Improve product variety to meet customer needs and preferences.
                    </li>
                </ul>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>