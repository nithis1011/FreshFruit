<?php

session_start();

require_once "config/database.php";


// Customer must be logged in
if (!isset($_SESSION["user_id"])) {

    header("Location: login.php");

    exit;
}


// Cart must not be empty
if (empty($_SESSION["cart"])) {

    header("Location: fruits.php");

    exit;
}


$user_id = $_SESSION["user_id"];


// Get customer information
$stmt = $conn->prepare(
    "SELECT name, email, phone, address
     FROM users
     WHERE id = ?"
);

$stmt->bind_param("i", $user_id);

$stmt->execute();

$user = $stmt->get_result()->fetch_assoc();

$stmt->close();


$message = "";


// ================= PLACE ORDER =================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $address = trim($_POST["address"]);
    $phone = trim($_POST["phone"]);

    if (empty($address) || empty($phone)) {

        $message = "Please enter your phone number and address.";

    } else {

        $total = 0;

        $cart_items = [];


        // Get all cart products
        foreach ($_SESSION["cart"] as $fruit_id => $quantity) {

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

                // Check stock
                if ($quantity > $fruit["stock"]) {

                    $message =
                        "Not enough stock for "
                        . $fruit["name"];

                    $stmt->close();

                    break;
                }


                $subtotal =
                    $fruit["price"] * $quantity;

                $total += $subtotal;

                $cart_items[] = [

                    "id" => $fruit["id"],

                    "quantity" => $quantity,

                    "price" => $fruit["price"]

                ];
            }

            $stmt->close();
        }


        // If no error, create order
        if (empty($message) && $total > 0) {

            // Start database transaction
            $conn->begin_transaction();

            try {

                // Create order
                $stmt = $conn->prepare(
                    "INSERT INTO orders
                    (user_id, total_amount, status)
                    VALUES (?, ?, 'Pending')"
                );

                $stmt->bind_param(
                    "id",
                    $user_id,
                    $total
                );

                $stmt->execute();

                $order_id = $conn->insert_id;

                $stmt->close();


                // Insert order items
                foreach ($cart_items as $item) {

                    $stmt = $conn->prepare(
                        "INSERT INTO order_items
                        (order_id, fruit_id, quantity, price)
                        VALUES (?, ?, ?, ?)"
                    );

                    $stmt->bind_param(
                        "iiid",
                        $order_id,
                        $item["id"],
                        $item["quantity"],
                        $item["price"]
                    );

                    $stmt->execute();

                    $stmt->close();


                    // Reduce stock
                    $stmt = $conn->prepare(
                        "UPDATE fruits
                         SET stock = stock - ?
                         WHERE id = ?"
                    );

                    $stmt->bind_param(
                        "ii",
                        $item["quantity"],
                        $item["id"]
                    );

                    $stmt->execute();

                    $stmt->close();
                }


                // Update customer's address/phone
                $stmt = $conn->prepare(
                    "UPDATE users
                     SET phone = ?, address = ?
                     WHERE id = ?"
                );

                $stmt->bind_param(
                    "ssi",
                    $phone,
                    $address,
                    $user_id
                );

                $stmt->execute();

                $stmt->close();


                // Everything successful
                $conn->commit();


                // Empty cart
                $_SESSION["cart"] = [];


                // Store order ID
                $_SESSION["last_order_id"] = $order_id;


                header(
                    "Location: order-success.php"
                );

                exit;


            } catch (Exception $e) {

                $conn->rollback();

                $message =
                    "Order could not be placed. Please try again.";
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Checkout</title>

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

        <a href="index.php">
            Home
        </a>

        <a href="fruits.php">
            Fruits
        </a>

        <a href="cart.php">
            Cart 🛒
        </a>

    </nav>

</header>


<!-- ================= TITLE ================= -->

<section class="page-title">

    <h1>Checkout 📦</h1>

    <p>
        Confirm your delivery details.
    </p>

</section>


<!-- ================= CHECKOUT ================= -->

<section class="checkout-section">

    <div class="checkout-box">

        <h2>Delivery Information</h2>


        <?php if (!empty($message)) { ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <form method="POST">

            <label>
                Name
            </label>

            <input
                type="text"
                value="<?php
                    echo htmlspecialchars($user["name"]);
                ?>"
                readonly
            >


            <label>
                Email
            </label>

            <input
                type="email"
                value="<?php
                    echo htmlspecialchars($user["email"]);
                ?>"
                readonly
            >


            <label>
                Phone Number
            </label>

            <input
                type="text"
                name="phone"
                value="<?php
                    echo htmlspecialchars(
                        $user["phone"] ?? ""
                    );
                ?>"
                required
            >


            <label>
                Delivery Address
            </label>

            <textarea
                name="address"
                required
            ><?php
                echo htmlspecialchars(
                    $user["address"] ?? ""
                );
            ?></textarea>


            <button
                type="submit"
                class="checkout-btn"
            >
                Place Order ✅
            </button>

        </form>

    </div>

</section>


</body>
</html>