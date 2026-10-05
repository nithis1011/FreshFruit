<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// Check customer ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: admin-customers.php");
    exit;
}

$user_id = intval($_GET["id"]);

// Get customer details
$stmt = $conn->prepare("
    SELECT id, name, email, phone, address
    FROM users
    WHERE id = ?
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$customer_result = $stmt->get_result();

if ($customer_result->num_rows === 0) {
    header("Location: admin-customers.php");
    exit;
}

$customer = $customer_result->fetch_assoc();

$stmt->close();


// Get customer's orders
$stmt = $conn->prepare("
    SELECT id, total_amount, status, order_date
    FROM orders
    WHERE user_id = ?
    ORDER BY order_date DESC
");

$stmt->bind_param("i", $user_id);
$stmt->execute();

$orders_result = $stmt->get_result();

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customer Orders - FreshFruit</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar">

        <a href="admin-dashboard.php">Dashboard</a>

        <a href="admin-orders.php">Orders</a>

        <a href="admin-fruits.php">Fruits</a>

        <a href="admin-customers.php">Customers</a>

        <a href="admin-logout.php">Logout</a>

    </nav>

</header>


<main class="container">

    <h1>👤 Customer Details</h1>


    <div class="admin-order-card">

        <h2>
            <?php echo htmlspecialchars($customer["name"]); ?>
        </h2>

        <p>
            📧
            <?php echo htmlspecialchars($customer["email"]); ?>
        </p>

        <p>
            📞
            <?php
            echo htmlspecialchars(
                $customer["phone"] ?? "Not provided"
            );
            ?>
        </p>

        <p>
            🏠
            <?php
            echo htmlspecialchars(
                $customer["address"] ?? "Not provided"
            );
            ?>
        </p>

    </div>


    <h2>🛒 Customer Orders</h2>


    <?php if ($orders_result->num_rows > 0): ?>

        <?php while ($order = $orders_result->fetch_assoc()): ?>

            <div class="admin-order-card">

                <div class="admin-order-header">

                    <div>

                        <h3>
                            Order #<?php echo $order["id"]; ?>
                        </h3>

                        <p>
                            📅
                            <?php echo $order["order_date"]; ?>
                        </p>

                    </div>

                    <div>

                        <strong>
                            ₹<?php
                            echo number_format(
                                $order["total_amount"],
                                2
                            );
                            ?>
                        </strong>

                        <p>
                            📦
                            <?php echo htmlspecialchars($order["status"]); ?>
                        </p>

                    </div>

                </div>


                <h4>🍎 Ordered Fruits</h4>


                <?php

                $item_stmt = $conn->prepare("
                    SELECT
                        f.name,
                        oi.quantity,
                        oi.price
                    FROM order_items oi
                    JOIN fruits f
                        ON oi.fruit_id = f.id
                    WHERE oi.order_id = ?
                ");

                $item_stmt->bind_param(
                    "i",
                    $order["id"]
                );

                $item_stmt->execute();

                $items_result = $item_stmt->get_result();

                ?>


                <ul>

                    <?php while ($item = $items_result->fetch_assoc()): ?>

                        <li>

                            <?php
                            echo htmlspecialchars($item["name"]);
                            ?>

                            ×

                            <?php
                            echo $item["quantity"];
                            ?>

                            — ₹<?php
                            echo number_format(
                                $item["price"],
                                2
                            );
                            ?>

                        </li>

                    <?php endwhile; ?>

                </ul>


                <?php $item_stmt->close(); ?>

            </div>

        <?php endwhile; ?>

    <?php else: ?>

        <div class="admin-order-card">

            <p>
                This customer has not placed any orders yet.
            </p>

        </div>

    <?php endif; ?>


    <br>

    <a href="admin-customers.php" class="btn">
        ← Back to Customers
    </a>

</main>

</body>

</html>