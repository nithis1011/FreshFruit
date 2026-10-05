<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// Get all fruits
$result = $conn->query("
    SELECT id, name, price, stock, image
    FROM fruits
    ORDER BY id DESC
");

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Manage Fruits - FreshFruit</title>

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

        <a href="admin-logout.php">Logout</a>

    </nav>

</header>


<main class="container">

    <h1>🍎 Manage Fruits</h1>


    <div class="admin-fruit-actions">

        <a href="admin-add-fruit.php" class="btn">
            ➕ Add New Fruit
        </a>

    </div>


    <div class="table-container">

        <table>

            <thead>

                <tr>

                    <th>Image</th>

                    <th>ID</th>

                    <th>Fruit Name</th>

                    <th>Price</th>

                    <th>Stock</th>

                    <th>Actions</th>

                </tr>

            </thead>


            <tbody>

            <?php if ($result->num_rows > 0): ?>

                <?php while ($fruit = $result->fetch_assoc()): ?>

                    <tr>

                        <!-- Fruit Image -->
                        <td>

                            <?php if (!empty($fruit["image"])): ?>

                                <img
                                    src="uploads/fruits/<?php echo htmlspecialchars($fruit["image"]); ?>"
                                    alt="<?php echo htmlspecialchars($fruit["name"]); ?>"
                                    style="
                                        width:70px;
                                        height:70px;
                                        object-fit:cover;
                                        border-radius:10px;
                                    "
                                >

                            <?php else: ?>

                                🍎

                            <?php endif; ?>

                        </td>


                        <!-- ID -->
                        <td>

                            <?php echo $fruit["id"]; ?>

                        </td>


                        <!-- Fruit Name -->
                        <td>

                            <?php echo htmlspecialchars($fruit["name"]); ?>

                        </td>


                        <!-- Price -->
                        <td>

                            ₹<?php echo number_format($fruit["price"], 2); ?>

                        </td>


                        <!-- Stock -->
                        <td>

                            <?php echo $fruit["stock"]; ?>

                        </td>


                        <!-- Actions -->
                        <td>

                            <a
                                href="admin-edit-fruit.php?id=<?php echo $fruit["id"]; ?>"
                                class="btn-small"
                            >
                                ✏️ Edit
                            </a>


                            <a
                                href="admin-delete-fruit.php?id=<?php echo $fruit["id"]; ?>"
                                class="btn-small delete-btn"
                                onclick="return confirm('Are you sure you want to delete this fruit?');"
                            >
                                🗑️ Delete
                            </a>

                        </td>

                    </tr>

                <?php endwhile; ?>

            <?php else: ?>

                <tr>

                    <td colspan="6">

                        No fruits found.

                    </td>

                </tr>

            <?php endif; ?>

            </tbody>

        </table>

    </div>

</main>

</body>

</html>