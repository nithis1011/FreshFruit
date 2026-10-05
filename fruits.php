<?php

require_once "config/database.php";

$sql = "SELECT * FROM fruits ORDER BY id DESC";

$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Fruits</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<!-- ================= NAVBAR ================= -->

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar" id="navMenu">

        <a href="index.php">Home</a>

        <a href="about.php">About</a>

        <a href="fruits.php" class="active">
            Fruits
        </a>

        <a href="offers.php">
            Offers
        </a>

        <a href="contact.php">
            Contact
        </a>

        <a href="login.php" class="login-btn">
            Login
        </a>

    </nav>

    <div class="menu-btn"
         onclick="toggleMenu()">
        ☰
    </div>

</header>


<!-- ================= PAGE TITLE ================= -->

<section class="page-title">

    <h1>Our Fresh Fruits 🍎</h1>

    <p>
        Choose from our collection of
        fresh and quality fruits.
    </p>

</section>


<!-- ================= FRUITS ================= -->

<section class="fruits-section">

    <div class="fruit-grid">

        <?php

        if ($result->num_rows > 0) {

            while ($fruit = $result->fetch_assoc()) {

        ?>

                <div class="fruit-card">

                    <div class="fruit-image">

                        <img
                            src="images/<?php echo htmlspecialchars($fruit['image']); ?>"
                            alt="<?php echo htmlspecialchars($fruit['name']); ?>"
                        >

                    </div>

                    <div class="fruit-info">

                        <span class="fruit-category">

                            <?php
                            echo htmlspecialchars(
                                $fruit['category']
                            );
                            ?>

                        </span>

                        <h3>

                            <?php
                            echo htmlspecialchars(
                                $fruit['name']
                            );
                            ?>

                        </h3>

                        <p>

                            <?php
                            echo htmlspecialchars(
                                $fruit['description']
                            );
                            ?>

                        </p>

                        <div class="fruit-bottom">

                            <strong>
                                ₹<?php
                                echo number_format(
                                    $fruit['price'],
                                    2
                                );
                                ?>
                            </strong>

                           <a
  				  href="cart.php?add=<?php echo $fruit['id']; ?>"
  				  class="add-cart-btn"
			    >
   				  Add to Cart 🛒
			   </a>

                        </div>

                    </div>

                </div>

        <?php

            }

        } else {

            echo "<p>No fruits available.</p>";

        }

        ?>

    </div>

</section>


<script src="js/script.js"></script>

</body>

</html>o