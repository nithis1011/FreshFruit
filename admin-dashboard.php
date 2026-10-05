<?php

session_start();

require_once "config/database.php";

// Admin must be logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// ================= DASHBOARD COUNTS =================

// Total orders
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders"
);

$total_orders = $result->fetch_assoc()["total"];


// Pending orders
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status = 'Pending'"
);

$pending_orders = $result->fetch_assoc()["total"];


// Delivered orders
$result = $conn->query(
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status = 'Delivered'"
);

$delivered_orders = $result->fetch_assoc()["total"];


// Total sales
$result = $conn->query(
    "SELECT COALESCE(SUM(total_amount), 0) AS total
     FROM orders
     WHERE status = 'Delivered'"
);

$total_sales = $result->fetch_assoc()["total"];

$total_fruits_result = $conn->query(
    "SELECT COUNT(*) AS total FROM fruits"
);

$total_fruits = $total_fruits_result->fetch_assoc()["total"];

$total_customers_result = $conn->query(
    "SELECT COUNT(*) AS total FROM users"
);

$total_customers = $total_customers_result->fetch_assoc()["total"];

// Recent orders
$recent_orders = $conn->query(
    "SELECT
        o.id,
        u.name AS customer_name,
        o.total_amount,
        o.status,
        o.order_date
     FROM orders o
     JOIN users u
       ON o.user_id = u.id
     ORDER BY o.order_date DESC
     LIMIT 10"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Admin Dashboard</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<!-- ================= ADMIN HEADER ================= -->

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar">

       <nav class="navbar">
    <a href="admin-dashboard.php">Dashboard</a>
    <a href="admin-orders.php">Orders</a>
    <a href="admin-fruits.php">Fruits</a>
    <a href="admin-customers.php">Customers</a>
    <a href="admin-logout.php">Logout</a>
</nav>

    </nav>

</header>


<!-- ================= TITLE ================= -->

<section class="page-title">

    <h1>Admin Dashboard 📊</h1>

    <p>
        Welcome,
        <?php
        echo htmlspecialchars(
            $_SESSION["admin_name"]
        );
        ?>!
    </p>

</section>


<!-- ================= STATISTICS ================= -->

<section class="dashboard-stats">

<a href="admin-fruits.php" class="stat-card dashboard-link">

    <h3>🍎 Total Fruits</h3>

    <p>
        <?php echo $total_fruits; ?>
    </p>

</a>


<a href="admin-customers.php" class="stat-card dashboard-link">

    <h3>👥 Customers</h3>

    <p>
        <?php echo $total_customers; ?>
    </p>

</a>
    <div class="stat-card">

        <h3>📦 Total Orders</h3>

        <p>
            <?php echo $total_orders; ?>
        </p>

    </div>


    <div class="stat-card">

        <h3>⏳ Pending Orders</h3>

        <p>
            <?php echo $pending_orders; ?>
        </p>

    </div>


    <div class="stat-card">

        <h3>✅ Delivered Orders</h3>

        <p>
            <?php echo $delivered_orders; ?>
        </p>

    </div>


    <div class="stat-card">

        <h3>💰 Total Sales</h3>

        <p>
            ₹<?php
            echo number_format(
                $total_sales,
                2
            );
            ?>
        </p>

    </div>


</section>
<div class="admin-quick-actions">

    <h2>⚡ Quick Actions</h2>

    <div class="quick-action-buttons">

        <a href="admin-fruits.php" class="btn">
            🍎 Manage Fruits
        </a>

        <a href="admin-add-fruit.php" class="btn">
            ➕ Add Fruit
        </a>

        <a href="admin-orders.php" class="btn">
            📦 Manage Orders
        </a>

        <a href="admin-customers.php" class="btn">
            👥 Customers
        </a>

    </div>

</div>


<!-- ================= RECENT ORDERS ================= -->

<section class="admin-orders">

    <h2>Recent Orders 📦</h2>

    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Order ID</th>

                    <th>Customer</th>

                    <th>Total</th>

                    <th>Status</th>

                    <th>Date</th>

                </tr>

            </thead>


            <tbody>

                <?php

                if ($recent_orders->num_rows > 0) {

                    while (
                        $order =
                        $recent_orders->fetch_assoc()
                    ) {

                ?>

                    <tr>

                        <td>
                            #<?php
                            echo htmlspecialchars(
                                $order["id"]
                            );
                            ?>
                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $order["customer_name"]
                            );
                            ?>
                        </td>


                        <td>
                            ₹<?php
                            echo number_format(
                                $order["total_amount"],
                                2
                            );
                            ?>
                        </td>


                        <td>

                            <span class="admin-status">

                                <?php
                                echo htmlspecialchars(
                                    $order["status"]
                                );
                                ?>

                            </span>

                        </td>


                        <td>
                            <?php
                            echo htmlspecialchars(
                                $order["order_date"]
                            );
                            ?>
                        </td>

                    </tr>

                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td colspan="5">
                            No orders found.
                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    </div>

</section>

</body>

</html>