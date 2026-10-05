<?php

session_start();

require_once "config/database.php";

// Get featured fruits
$featured_result = $conn->query("
    SELECT id, name, price, stock
    FROM fruits
    WHERE stock > 0
    ORDER BY id DESC
    LIMIT 6
");

?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>FreshFruit - Fresh Fruits Delivered</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>


<!-- HEADER -->

<header class="modern-header">

    <div class="modern-nav">

        <!-- Logo -->
        <a href="index.php" class="modern-logo">
            <span class="logo-icon">🍎</span>
            <span>Fresh<span class="logo-highlight">Fruit</span></span>
        </a>


        <!-- Navigation -->
        <nav class="modern-menu">

            <a href="index.php" class="active">
                Home
            </a>

            <a href="fruits.php">
                Shop
            </a>

            <a href="my-orders.php">
                Orders
            </a>

        </nav>


        <!-- Right Side -->
        <div class="modern-actions">

            <a href="fruits.php" class="nav-search">
                🔍
            </a>

            <a href="fruits.php" class="nav-cart">
                🛒
                <span class="cart-count">0</span>
            </a>

            <?php if (isset($_SESSION["user_id"])): ?>

                <a href="logout.php" class="account-btn">
                    Logout
                </a>

            <?php else: ?>

                <a href="login.php" class="account-btn">
                    Login
                </a>

            <?php endif; ?>

        </div>

    </div>

</header><!-- HERO SECTION -->

<section class="hero">

    <div class="hero-content">

        <h1>
            Fresh Fruits.
            <span>Fresh Life.</span>
        </h1>

        <p>
            Quality fruits delivered fresh to your doorstep.
        </p>

        <a href="fruits.php" class="btn">
            🛒 Shop Now
        </a>

    </div>

</section>


<!-- FEATURES -->
<!-- FEATURED FRUITS -->

<section class="featured-section">

    <div class="section-heading">

        <span>Fresh From Our Store</span>

        <h2>Popular Fruits 🍎</h2>

        <p>
            Fresh and delicious fruits available today.
        </p>

    </div>


    <div class="fruit-grid">

        <?php while ($fruit = $featured_result->fetch_assoc()): ?>

            <div class="fruit-card">

                <div class="fruit-image">
                    🍎
                </div>

                <div class="fruit-info">

                    <h3>
                        <?php echo htmlspecialchars($fruit["name"]); ?>
                    </h3>

                    <p class="fruit-price">
                        ₹<?php echo number_format($fruit["price"], 2); ?>
                    </p>

                    <p class="fruit-stock">
                        <?php echo $fruit["stock"]; ?> available
                    </p>

                    <a
                        href="fruits.php"
                        class="fruit-btn"
                    >
                        🛒 Shop Now
                    </a>

                </div>

            </div>

        <?php endwhile; ?>

    </div>


    <div class="view-all">

        <a href="fruits.php" class="btn">
            View All Fruits →
        </a>

    </div>

</section>

<section class="features">

    <div class="feature-card">

        <div class="feature-icon">🍎</div>

        <h3>Fresh Fruits</h3>

        <p>
            Hand-picked quality fruits for your family.
        </p>

    </div>


    <div class="feature-card">

        <div class="feature-icon">🚚</div>

        <h3>Fast Delivery</h3>

        <p>
            Get your fruits delivered directly to your doorstep.
        </p>

    </div>


    <div class="feature-card">

        <div class="feature-icon">💯</div>

        <h3>Quality Guaranteed</h3>

        <p>
            We focus on freshness and quality in every order.
        </p>

    </div>

</section>


<!-- CALL TO ACTION -->

<section class="home-cta">

    <h2>
        Ready to enjoy fresh fruits?
    </h2>

    <p>
        Browse our collection and place your order today.
    </p>

    <a href="fruits.php" class="btn">
        🍊 Explore Fruits
    </a>

</section>


<!-- FOOTER -->

<footer class="footer">

    <p>
        © <?php echo date("Y"); ?> FreshFruit.
        All Rights Reserved.
    </p>

</footer>


</body>

</html>