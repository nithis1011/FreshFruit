<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// Get fruit ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: admin-fruits.php");
    exit;
}

$id = intval($_GET["id"]);

// Get fruit details
$stmt = $conn->prepare(
    "SELECT id, name, price, stock
     FROM fruits
     WHERE id = ?"
);

$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 0) {
    header("Location: admin-fruits.php");
    exit;
}

$fruit = $result->fetch_assoc();

$stmt->close();

$message = "";

// Update fruit
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $price = floatval($_POST["price"]);
    $stock = intval($_POST["stock"]);

    if ($name === "" || $price < 0 || $stock < 0) {

        $message = "Please enter valid fruit details.";

    } else {

        $stmt = $conn->prepare(
            "UPDATE fruits
             SET name = ?, price = ?, stock = ?
             WHERE id = ?"
        );

        $stmt->bind_param(
            "sdii",
            $name,
            $price,
            $stock,
            $id
        );

        if ($stmt->execute()) {

            header("Location: admin-fruits.php");
            exit;

        } else {

            $message = "Error updating fruit: " . $stmt->error;

        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Edit Fruit - FreshFruit</title>

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

    <h1>✏️ Edit Fruit</h1>

    <?php if ($message !== ""): ?>

        <p style="color:red;">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form method="POST" class="admin-form">

        <label>Fruit Name</label>

        <input
            type="text"
            name="name"
            value="<?php echo htmlspecialchars($fruit["name"]); ?>"
            required
        >


        <label>Price (₹)</label>

        <input
            type="number"
            name="price"
            step="0.01"
            min="0"
            value="<?php echo $fruit["price"]; ?>"
            required
        >


        <label>Stock</label>

        <input
            type="number"
            name="stock"
            min="0"
            value="<?php echo $fruit["stock"]; ?>"
            required
        >


        <button type="submit" class="btn">
            💾 Update Fruit
        </button>

        <a href="admin-fruits.php" class="btn">
            ← Back
        </a>

    </form>

</main>

</body>
</html>