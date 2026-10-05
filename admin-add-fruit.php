<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"]);
    $price = $_POST["price"];
    $stock = $_POST["stock"];

    // Basic validation
    if ($name === "" || $price === "" || $stock === "") {
        $message = "Please fill all fields.";
    } elseif (!isset($_FILES["image"]) || $_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $message = "Please select a fruit image.";
    } else {

        $image = $_FILES["image"];

        // Allowed image types
        $allowed_types = [
            "image/jpeg",
            "image/png",
            "image/webp"
        ];

        // Check image type
        if (!in_array($image["type"], $allowed_types)) {

            $message = "Only JPG, PNG and WEBP images are allowed.";

        } else {

            // Maximum file size: 5 MB
            if ($image["size"] > 5 * 1024 * 1024) {

                $message = "Image size must be less than 5 MB.";

            } else {

                // Create unique filename
                $extension = pathinfo($image["name"], PATHINFO_EXTENSION);

                $filename = uniqid("fruit_", true) . "." . strtolower($extension);

                $upload_path = "uploads/fruits/" . $filename;

                // Move uploaded image
                if (move_uploaded_file($image["tmp_name"], $upload_path)) {

                    // Insert fruit into database
                    $stmt = $conn->prepare("
                        INSERT INTO fruits (name, price, stock, image)
                        VALUES (?, ?, ?, ?)
                    ");

                    $stmt->bind_param(
                        "sdis",
                        $name,
                        $price,
                        $stock,
                        $filename
                    );

                    if ($stmt->execute()) {

                        header("Location: admin-fruits.php");
                        exit;

                    } else {

                        // Delete uploaded image if database insert fails
                        if (file_exists($upload_path)) {
                            unlink($upload_path);
                        }

                        $message = "Failed to add fruit.";
                    }

                    $stmt->close();

                } else {

                    $message = "Failed to upload image.";
                }
            }
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Add Fruit - FreshFruit</title>

    <link rel="stylesheet" href="css/style.css">

</head>

<body>

<header class="modern-header">

    <div class="modern-nav">

        <a href="admin-dashboard.php" class="modern-logo">
            <span class="logo-icon">🍎</span>
            <span>Fresh<span class="logo-highlight">Fruit</span></span>
        </a>

        <div class="modern-actions">

            <a href="admin-dashboard.php" class="account-btn">
                Dashboard
            </a>

        </div>

    </div>

</header>


<div class="admin-container">

    <h1>➕ Add New Fruit</h1>

    <?php if ($message !== ""): ?>

        <p style="
            color: red;
            background: #ffecec;
            padding: 12px;
            border-radius: 8px;
        ">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <form
        method="POST"
        enctype="multipart/form-data"
        style="max-width: 600px;"
    >

        <div class="form-group">

            <label>Fruit Name</label>

            <input
                type="text"
                name="name"
                placeholder="Example: Apple"
                required
            >

        </div>


        <div class="form-group">

            <label>Price</label>

            <input
                type="number"
                name="price"
                step="0.01"
                min="0"
                placeholder="Example: 120"
                required
            >

        </div>


        <div class="form-group">

            <label>Stock</label>

            <input
                type="number"
                name="stock"
                min="0"
                placeholder="Example: 50"
                required
            >

        </div>


        <div class="form-group">

            <label>Fruit Image</label>

            <input
                type="file"
                name="image"
                accept="image/jpeg,image/png,image/webp"
                required
            >

            <small>
                JPG, PNG or WEBP — Maximum 5 MB
            </small>

        </div>


        <button type="submit" class="btn">
            🍎 Add Fruit
        </button>

        <a
            href="admin-fruits.php"
            class="btn"
            style="background:#777; margin-left:10px;"
        >
            Cancel
        </a>

    </form>

</div>

</body>

</html>