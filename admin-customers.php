<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// Get all customers
$result = $conn->query("
    SELECT
        u.id,
        u.name,
        u.email,
        u.phone,
        u.address,
        COUNT(o.id) AS total_orders
    FROM users u
    LEFT JOIN orders o ON u.id = o.user_id
    GROUP BY u.id, u.name, u.email, u.phone, u.address
    ORDER BY u.id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Customers - FreshFruit</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

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

        <a href="admin-fruits.php">
            Fruits
        </a>

        <a href="admin-customers.php">
            Customers
        </a>

        <a href="admin-logout.php">
            Logout
        </a>

    </nav>

</header>


<main class="container">

    <h1>👥 Customer Management</h1>

    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>ID</th>

                    <th>Name</th>

                    <th>Email</th>

                    <th>Phone</th>

                    <th>Address</th>

                    <th>Total Orders</th>

		    <th>Action</th>

                </tr>

            </thead>

            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($customer = $result->fetch_assoc()): ?>

                    <tr>

                        <td>
                            <?php echo $customer["id"]; ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer["name"]); ?>
                        </td>

                        <td>
                            <?php echo htmlspecialchars($customer["email"]); ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $customer["phone"] ?? "Not provided"
                            );
                            ?>
                        </td>

                        <td>
                            <?php
                            echo htmlspecialchars(
                                $customer["address"] ?? "Not provided"
                            );
                            ?>
                        </td>

                        <td>
                            🛒 <?php echo $customer["total_orders"]; ?>
                        </td>
			<td>
    <a
        href="admin-customer-orders.php?id=<?php echo $customer['id']; ?>"
        class="btn-small"
    >
        👁️ View Orders
    </a>
</td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">
                        No customers found.
                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>

</html>