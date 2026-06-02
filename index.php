<?php
include 'includes/config.php';

$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories LIMIT 8"
);

$bestSellers = mysqli_query(
    $conn,
    "SELECT 
        p.*,
        c.category_name
    FROM products p
    JOIN categories c 
        ON c.id = p.category_id
    WHERE p.best_seller = 1
    ORDER BY p.id DESC
    LIMIT 8"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta 
        name="viewport" 
        content="width=device-width, initial-scale=1"
    >

    <title>MORNING BAKERY</title>

    <link 
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" 
        rel="stylesheet"
    >

    <link 
        rel="stylesheet" 
        href="assets/css/style.css"
    >

    <style>
        .hero-modern {
            min-height: 88vh;
            display: flex;
            align-items: center;
            background:
                linear-gradient(
                    90deg,
                    rgba(255,247,231,.96) 0%,
                    rgba(255,247,231,.88) 45%,
                    rgba(255,247,231,.45) 100%
                ),
                url('https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=1600&auto=format&fit=crop');
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
        }

        .hero-modern::after {
            content: "";
            position: absolute;
            width: 420px;
            height: 420px;
            border-radius: 50%;
            background: rgba(123, 17, 32, .08);
            right: -120px;
            bottom: -100px;
        }

        .hero-content {
            position: relative;
            z-index: 2;
        }

        .hero-title {
            font-size: clamp(48px, 7vw, 96px);
            line-height: .95;
            color: #7b1020;
            font-weight: 900;
            letter-spacing: 2px;
        }

        .hero-title span {
            color: #d8a625;
        }

        .hero-desc {
            max-width: 620px;
            font-size: 18px;
            color: #3b2a21;
        }

        .hero-image-box {
            position: relative;
            z-index: 2;
            animation: floatBread 4s ease-in-out infinite;
        }

        .hero-image-box img {
            width: 100%;
            max-width: 520px;
            border-radius: 36px;
            box-shadow: 0 25px 60px rgba(0,0,0,.18);
        }

        @keyframes floatBread {
            0% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-16px);
            }

            100% {
                transform: translateY(0);
            }
        }

        .mini-stat {
            background: #fff;
            border-radius: 22px;
            padding: 18px 22px;
            box-shadow: 0 12px 30px rgba(0,0,0,.08);
        }

        .mini-stat h4 {
            color: #7b1020;
            font-weight: 800;
            margin-bottom: 0;
        }

        .section-soft {
            background: #fffaf0;
        }

        .category-card,
        .why-card,
        .product-card {
            transition: .3s ease;
        }

        .category-card:hover,
        .why-card:hover,
        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 18px 40px rgba(0,0,0,.12);
        }

        .why-card {
            background: #fff;
            border-radius: 28px;
            padding: 35px 25px;
            text-align: center;
            height: 100%;
            box-shadow: 0 10px 30px rgba(0,0,0,.06);
        }

        .why-icon {
            font-size: 46px;
            margin-bottom: 18px;
        }

        .best-ribbon {
            position: absolute;
            top: 18px;
            right: 18px;
            background: #dc3545;
            color: white;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            z-index: 10;
        }

        .product-card {
            position: relative;
            overflow: hidden;
        }

        .product-img img {
            transition: .4s ease;
        }

        .product-card:hover .product-img img {
            transform: scale(1.05);
        }

        .promo-modern {
            border-radius: 36px;
            padding: 55px;
            background:
                linear-gradient(
                    135deg,
                    #7b1020 0%,
                    #9c1d2f 55%,
                    #d8a625 100%
                );
            color: white;
            box-shadow: 0 20px 55px rgba(123,16,32,.25);
        }

        .promo-modern h2 {
            font-weight: 900;
            font-size: clamp(32px, 5vw, 54px);
        }

        @media (max-width: 768px) {
            .hero-modern {
                min-height: auto;
                padding: 80px 0;
                text-align: center;
            }

            .hero-desc {
                margin: auto;
            }

            .promo-modern {
                padding: 35px 25px;
                text-align: center;
            }
        }
    </style>
</head>

<body class="bakery-body">

<?php include 'includes/navbar.php'; ?>

<section class="hero-modern">

    <div class="container hero-content">

        <div class="row align-items-center g-5">

            <div class="col-lg-6">

                <span class="badge badge-gold mb-3">
                    Freshly Baked Every Morning
                </span>

                <h1 class="hero-title">
                    MORNING
                    <br>
                    <span>BAKERY</span>
                </h1>

                <p class="hero-desc mt-4">
                    Enjoy soft breads, cakes, pastries, muffins, and classic bakery
                    products made fresh every morning with premium taste.
                </p>

                <div class="mt-4">

                    <a 
                        href="products.php" 
                        class="btn btn-maroon btn-lg me-2"
                    >
                        Shop Now
                    </a>

                    <a 
                        href="#best-seller" 
                        class="btn btn-outline-maroon btn-lg"
                    >
                        Best Seller
                    </a>

                </div>

                <div class="row g-3 mt-4">

                    <div class="col-4">
                        <div class="mini-stat">
                            <h4>Fresh</h4>
                            <small>Daily Bake</small>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="mini-stat">
                            <h4>100%</h4>
                            <small>Quality</small>
                        </div>
                    </div>

                    <div class="col-4">
                        <div class="mini-stat">
                            <h4>Fast</h4>
                            <small>Order</small>
                        </div>
                    </div>

                </div>

            </div>

            <div class="col-lg-6 text-center">

                <div class="hero-image-box">

                    <img 
                        src="https://images.unsplash.com/photo-1549931319-a545dcf3bc73?q=80&w=900&auto=format&fit=crop"
                        alt="Morning Bakery"
                    >

                </div>

            </div>

        </div>

    </div>

</section>

<section class="py-5 section-soft" id="categories">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge badge-gold mb-2">
                Our Menu
            </span>

            <h2 class="section-title">
                Bakery Categories
            </h2>

            <p class="text-muted">
                Choose your favorite bakery menu.
            </p>

        </div>

        <div class="row g-4">

    <?php while ($category = mysqli_fetch_assoc($categories)): ?>

        <div class="col-6 col-md-3">

            <a 
                href="category.php?id=<?= $category['id']; ?>"
                class="text-decoration-none"
            >

                <div class="category-card category-premium">
                    <?= e($category['category_name']); ?>
                </div>

            </a>

        </div>

    <?php endwhile; ?>

</div>

</section>

<section class="py-5" id="best-seller">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <span class="badge badge-gold mb-2">
                    Customer Favorite
                </span>

                <h2 class="section-title">
                    Best Seller Products
                </h2>

                <p class="mb-0 text-muted">
                    Fresh products selected for you.
                </p>

            </div>

            <a 
                href="products.php" 
                class="btn btn-maroon"
            >
                View All
            </a>

        </div>

        <div class="row g-4">

            <?php if (mysqli_num_rows($bestSellers) > 0): ?>

                <?php while ($product = mysqli_fetch_assoc($bestSellers)): ?>

                    <div class="col-md-3 col-sm-6">

                        <div class="product-card h-100 d-flex flex-column">

                            <div class="best-ribbon">
                                Best Seller
                            </div>

                            <div class="product-img">

                                <img 
                                    src="assets/products/<?= e($product['image']); ?>"
                                    alt="<?= e($product['product_name']); ?>"
                                    onerror="this.src='https://images.unsplash.com/photo-1509440159596-0249088772ff?q=80&w=600&auto=format&fit=crop'"
                                >

                            </div>

                            <div class="product-body d-flex flex-column flex-grow-1">

                                <h5 class="fw-bold">
                                    <?= e($product['product_name']); ?>
                                </h5>

                                <p class="small text-muted mb-2">
                                    <?= e($product['category_name']); ?>
                                    •
                                    <?= e($product['weight']); ?>
                                </p>

                                <p class="price mb-2">
                                    <?= rupiah($product['price']); ?>
                                </p>

                                <p class="small text-muted">
                                    Stock:
                                    <b><?= e($product['stock']); ?></b>
                                </p>

                                <div class="mt-auto">

                                    <?php if ($product['stock'] > 0): ?>

                                        <a 
                                            href="product_detail.php?id=<?= $product['id']; ?>"
                                            class="btn btn-maroon w-100"
                                        >
                                            Order
                                        </a>

                                    <?php else: ?>

                                        <button 
                                            class="btn btn-secondary w-100"
                                            disabled
                                        >
                                            Out of Stock
                                        </button>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                <?php endwhile; ?>

            <?php else: ?>

                <div class="col-12">
                    <div class="alert alert-warning">
                        No best seller products found.
                    </div>
                </div>

            <?php endif; ?>

        </div>

    </div>

</section>

<section class="py-5 section-soft">

    <div class="container">

        <div class="text-center mb-5">

            <span class="badge badge-gold mb-2">
                Why Choose Us
            </span>

            <h2 class="section-title">
                Fresh Taste, Better Experience
            </h2>

        </div>

        <div class="row g-4">

            <div class="col-md-3">
                <div class="why-card">
                    <div class="why-icon">🥐</div>
                    <h5 class="fw-bold">Fresh Daily</h5>
                    <p class="text-muted mb-0">
                        Baked every morning for fresh taste.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="why-card">
                    <div class="why-icon">⭐</div>
                    <h5 class="fw-bold">Premium Quality</h5>
                    <p class="text-muted mb-0">
                        Made with selected ingredients.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="why-card">
                    <div class="why-icon">🛒</div>
                    <h5 class="fw-bold">Easy Order</h5>
                    <p class="text-muted mb-0">
                        Order your favorite bakery online.
                    </p>
                </div>
            </div>

            <div class="col-md-3">
                <div class="why-card">
                    <div class="why-icon">🚚</div>
                    <h5 class="fw-bold">Fast Service</h5>
                    <p class="text-muted mb-0">
                        Simple payment and order process.
                    </p>
                </div>
            </div>

        </div>

    </div>

</section>

<section class="py-5">

    <div class="container">

        <div class="promo-modern">

            <div class="row align-items-center">

                <div class="col-md-8">

                    <h2>
                        Lembutnya Selalu Jadi Pilihan
                    </h2>

                    <p class="mb-0">
                        Morning Bakery hadir dengan katalog produk modern,
                        cocok untuk sarapan, camilan, dan acara keluarga.
                    </p>

                </div>

                <div class="col-md-4 text-md-end mt-4 mt-md-0">

                    <a 
                        href="products.php" 
                        class="btn btn-light btn-lg"
                    >
                        Order Now
                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

</body>
</html> 