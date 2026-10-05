<?php

session_start();

require_once "config/database.php";

// Customer must be logged in
if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION["user_id"];

// Get customer's orders
$stmt = $conn->prepare(
    "SELECT id, total_amount, status, order_date
     FROM orders
     WHERE user_id = ?
     ORDER BY order_date DESC"
);

$stmt->bind_param("i", $user_id);
$stmt->execute();

$orders = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | My Orders</title>

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

        <a href="my-orders.php">
            My Orders 📦
        </a>

    </nav>

</header>


<!-- ================= TITLE ================= -->

<section class="page-title">

    <h1>My Orders 📦</h1>

    <p>
        View your previous orders and track their status.
    </p>

</section>


<!-- ================= ORDERS ================= -->

<section class="orders-section">

    <?php if ($orders->num_rows > 0) { ?>

        <?php while ($order = $orders->fetch_assoc()) { ?>

            <div class="order-card">

                <div class="order-header">

                    <h2>
                        Order #<?php
                        echo htmlspecialchars($order["id"]);
                        ?>
                    </h2>
<div class="order-tracking">

    <?php

    $statuses = [
        "Pending",
        "Confirmed",
        "Preparing",
        "Out for Delivery",
        "Delivered"
    ];

    $current_status = $order["status"];

    $current_index = array_search(
        $current_status,
        $statuses
    );

    foreach ($statuses as $index => $status) {

        $class = "";

        if ($index < $current_index) {
            $class = "completed";
        }

        if ($index == $current_index) {
            $class = "active";
        }

    ?>

        <div class="tracking-step <?php echo $class; ?>">

            <div class="tracking-circle">
                <?php echo $index + 1; ?>
            </div>

            <div class="tracking-label">
                <?php echo htmlspecialchars($status); ?>
            </div>

        </div>

    <?php } ?>

</div>

                                   </div>


                <div class="order-info">

                    <p>
                        💰 <strong>Total:</strong>
                        ₹<?php
                        echo number_format(
                            $order["total_amount"],
                            2
                        );
                        ?>
                    </p>

                    <p>
                        📅 <strong>Date:</strong>
                        <?php
                        echo htmlspecialchars(
                            $order["order_date"]
                        );
                        ?>
                    </p>

                </div>


                <!-- ORDER ITEMS -->

                <h3>🍎 Fruits Ordered</h3>

                <?php

                $item_stmt = $conn->prepare(
                    "SELECT
                        f.name,
                        oi.quantity,
                        oi.price
                     FROM order_items oi
                     JOIN fruits f
                       ON oi.fruit_id = f.id
                     WHERE oi.order_id = ?"
                );

                $item_stmt->bind_param(
                    "i",
                    $order["id"]
                );

                $item_stmt->execute();

                $items = $item_stmt->get_result();

                ?>

                <div class="order-items">

                    <?php while ($item = $items->fetch_assoc()) { ?>

                        <div class="order-item">

                            <span>
                                🍎
                                <?php
                                echo htmlspecialchars(
                                    $item["name"]
                                );
                                ?>
                            </span>

                            <span>
                                ×
                                <?php
                                echo htmlspecialchars(
                                    $item["quantity"]
                                );
                                ?>
                            </span>

                            <span>
                                ₹<?php
                                echo number_format(
                                    $item["price"],
                                    2
                                );
                                ?>
                            </span>

                        </div>

                    <?php } ?>

                </div>

                <?php $item_stmt->close(); ?>

            </div>

        <?php } ?>

    <?php } else { ?>

        <div class="no-orders">

            <h2>📦 No Orders Yet</h2>

            <p>
                You haven't placed any orders yet.
            </p>

            <a href="fruits.php" class="checkout-btn">
                Start Shopping 🍎
            </a>

        </div>

    <?php } ?>

</section>

</body>

</html>

<?php
$stmt->close();
?>