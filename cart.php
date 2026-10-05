<?php

session_start();

require_once "config/database.php";

if (!isset($_SESSION["cart"])) {
    $_SESSION["cart"] = [];
}


/* ================= ADD TO CART ================= */

if (isset($_GET["add"])) {

    $fruit_id = intval($_GET["add"]);

    $stmt = $conn->prepare(
        "SELECT id, name, price, stock
         FROM fruits
         WHERE id = ?"
    );

    $stmt->bind_param("i", $fruit_id);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $fruit = $result->fetch_assoc();

        if ($fruit["stock"] > 0) {

            if (isset($_SESSION["cart"][$fruit_id])) {

                if (
                    $_SESSION["cart"][$fruit_id]
                    < $fruit["stock"]
                ) {
                    $_SESSION["cart"][$fruit_id]++;
                }

            } else {

                $_SESSION["cart"][$fruit_id] = 1;

            }
        }
    }

    $stmt->close();

    header("Location: cart.php");
    exit;
}


/* ================= REMOVE ITEM ================= */

if (isset($_GET["remove"])) {

    $fruit_id = intval($_GET["remove"]);

    unset($_SESSION["cart"][$fruit_id]);

    header("Location: cart.php");
    exit;
}


/* ================= CLEAR CART ================= */

if (isset($_GET["clear"])) {

    $_SESSION["cart"] = [];

    header("Location: cart.php");
    exit;
}


/* ================= UPDATE CART ================= */

if (isset($_POST["update_cart"])) {

    foreach ($_POST["quantity"] as $fruit_id => $quantity) {

        $fruit_id = intval($fruit_id);
        $quantity = intval($quantity);

        if ($quantity <= 0) {

            unset($_SESSION["cart"][$fruit_id]);

        } else {

            $_SESSION["cart"][$fruit_id] = $quantity;
        }
    }

    header("Location: cart.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Cart</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<!-- ================= NAVBAR ================= -->

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar">

        <a href="index.php">Home</a>

        <a href="fruits.php">Fruits</a>

        <a href="cart.php" class="active">
            Cart 🛒
        </a>

        <?php if (isset($_SESSION["user_id"])) { ?>

            <a href="logout.php">
                Logout
            </a>

        <?php } else { ?>

            <a href="login.php">
                Login
            </a>

        <?php } ?>

    </nav>

</header>


<!-- ================= PAGE TITLE ================= -->

<section class="page-title">

    <h1>Your Shopping Cart 🛒</h1>

    <p>Review your selected fruits.</p>

</section>


<!-- ================= CART ================= -->

<section class="cart-section">

<?php

if (empty($_SESSION["cart"])) {

?>

    <div class="empty-cart">

        <div class="empty-icon">
            🛒
        </div>

        <h2>Your cart is empty</h2>

        <p>
            Add some fresh fruits to your cart.
        </p>

        <a href="fruits.php"
           class="btn primary-btn">

            Browse Fruits

        </a>

    </div>

<?php

} else {

    $total = 0;

?>

<form method="POST">

    <div class="cart-container">

        <?php

        foreach ($_SESSION["cart"] as $fruit_id => $quantity) {

            $stmt = $conn->prepare(
                "SELECT *
                 FROM fruits
                 WHERE id = ?"
            );

            $stmt->bind_param("i", $fruit_id);

            $stmt->execute();

            $result = $stmt->get_result();

            if ($result->num_rows == 1) {

                $fruit = $result->fetch_assoc();

                $subtotal =
                    $fruit["price"] * $quantity;

                $total += $subtotal;

        ?>

            <div class="cart-item">

                <div class="cart-item-info">

                    <div class="cart-placeholder">
                        🍎
                    </div>

                    <div>

                        <h3>
                            <?php
                            echo htmlspecialchars(
                                $fruit["name"]
                            );
                            ?>
                        </h3>

                        <p>
                            ₹<?php
                            echo number_format(
                                $fruit["price"],
                                2
                            );
                            ?> each
                        </p>

                    </div>

                </div>


                <div class="quantity-box">

                    <input
                        type="number"
                        name="quantity[
                            <?php echo $fruit_id; ?>
                        ]"
                        value="<?php echo $quantity; ?>"
                        min="1"
                    >

                </div>


                <strong>

                    ₹<?php
                    echo number_format(
                        $subtotal,
                        2
                    );
                    ?>

                </strong>


                <a
                    href="cart.php?remove=<?php echo $fruit_id; ?>"
                    class="remove-btn"
                >
                    Remove
                </a>

            </div>

        <?php

            }

            $stmt->close();

        }

        ?>

    </div>


    <div class="cart-summary">

        <h2>
            Total:
            ₹<?php echo number_format($total, 2); ?>
        </h2>

        <div class="cart-actions">

            <button
                type="submit"
                name="update_cart"
                class="btn secondary-btn"
            >
                Update Cart
            </button>

            <a
                href="cart.php?clear=1"
                class="btn secondary-btn"
            >
                Clear Cart
            </a>

            <a
                href="checkout.php"
                class="btn primary-btn"
            >
                Proceed to Checkout
            </a>

        </div>

    </div>

</form>

<?php } ?>

</section>


<script src="js/script.js"></script>

</body>
</html>