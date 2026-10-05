<?php

session_start();

require_once "config/database.php";

// Admin must be logged in
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

$message = "";

// ================= UPDATE ORDER STATUS =================

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $order_id = intval($_POST["order_id"]);
    $status = $_POST["status"];

    $allowed_statuses = [
        "Pending",
        "Confirmed",
        "Preparing",
        "Out for Delivery",
        "Delivered"
    ];

    if (in_array($status, $allowed_statuses)) {

        $stmt = $conn->prepare(
            "UPDATE orders
             SET status = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "si",
            $status,
            $order_id
        );

        if ($stmt->execute()) {
            $message = "Order status updated successfully.";
        } else {
            $message = "Could not update order status.";
        }

        $stmt->close();
    }
}


// ================= GET ALL ORDERS =================

$orders = $conn->query(
    "SELECT
        o.id,
        u.name AS customer_name,
        u.email,
        o.total_amount,
        o.status,
        o.order_date
     FROM orders o
     JOIN users u
       ON o.user_id = u.id
     ORDER BY o.order_date DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Manage Orders</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<!-- ================= HEADER ================= -->

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar">

        <a href="admin-dashboard.php">
            Dashboard
        </a>

        <a href="admin-orders.php">
            Orders
        </a>

        <a href="fruits.php">
            Fruits
        </a>

        <a href="admin-logout.php">
            Logout 🚪
        </a>

    </nav>

</header>


<!-- ================= TITLE ================= -->

<section class="page-title">

    <h1>Manage Orders 📦</h1>

    <p>
        View and update customer orders.
    </p>

</section>


<!-- ================= MESSAGE ================= -->

<?php if (!empty($message)) { ?>

    <div class="message">

        <?php
        echo htmlspecialchars($message);
        ?>

    </div>

<?php } ?>


<!-- ================= ORDERS ================= -->

<section class="admin-orders">

<?php if ($orders->num_rows > 0) { ?>

    <?php while ($order = $orders->fetch_assoc()) { ?>

        <div class="admin-order-card">

            <!-- ORDER HEADER -->

            <div class="admin-order-header">

                <div>

                    <h2>
                        Order #<?php
                        echo htmlspecialchars(
                            $order["id"]
                        );
                        ?>
                    </h2>

                    <p>
                        👤
                        <?php
                        echo htmlspecialchars(
                            $order["customer_name"]
                        );
                        ?>
                    </p>

                    <p>
                        📧
                        <?php
                        echo htmlspecialchars(
                            $order["email"]
                        );
                        ?>
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
                        📅
                        <?php
                        echo htmlspecialchars(
                            $order["order_date"]
                        );
                        ?>
                    </p>

                </div>

            </div>


            <!-- ORDER ITEMS -->

            <h3>🍎 Ordered Fruits</h3>

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


            <!-- STATUS UPDATE -->

            <div class="status-update">

                <form method="POST">

                    <input
                        type="hidden"
                        name="order_id"
                        value="<?php
                        echo htmlspecialchars(
                            $order["id"]
                        );
                        ?>"
                    >

                    <label>
                        Order Status
                    </label>

                    <select name="status">

                        <?php

                        $statuses = [
                            "Pending",
                            "Confirmed",
                            "Preparing",
                            "Out for Delivery",
                            "Delivered"
                        ];

                        foreach (
                            $statuses as $status
                        ) {

                            $selected =
                                ($order["status"] ==
                                 $status)
                                ? "selected"
                                : "";

                        ?>

                            <option
                                value="<?php
                                echo htmlspecialchars(
                                    $status
                                );
                                ?>"
                                <?php echo $selected; ?>
                            >
                                <?php
                                echo htmlspecialchars(
                                    $status
                                );
                                ?>
                            </option>

                        <?php } ?>

                    </select>

                    <button
                        type="submit"
                        class="checkout-btn"
                    >
                        Update Status 🔄
                    </button>

                </form>

            </div>

        </div>

    <?php } ?>

<?php } else { ?>

    <div class="no-orders">

        <h2>📦 No Orders</h2>

        <p>
            There are currently no customer orders.
        </p>

    </div>

<?php } ?>

</section>

</body>
</html>