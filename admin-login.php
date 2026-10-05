<?php

session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    if (empty($email) || empty($password)) {

        $message = "Please enter email and password.";

    } else {

        $stmt = $conn->prepare(
            "SELECT id, name, email, password
             FROM admins
             WHERE email = ?"
        );

        $stmt->bind_param("s", $email);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 1) {

            $admin = $result->fetch_assoc();

            if (password_verify(
                $password,
                $admin["password"]
            )) {

                $_SESSION["admin_id"] = $admin["id"];
                $_SESSION["admin_name"] = $admin["name"];

                header("Location: admin-dashboard.php");
                exit;

            } else {

                $message = "Invalid email or password.";
            }

        } else {

            $message = "Invalid email or password.";
        }

        $stmt->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Admin Login</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

</header>


<section class="page-title">

    <h1>Admin Login 🔐</h1>

    <p>
        Login to manage FreshFruit.
    </p>

</section>


<section class="checkout-section">

    <div class="checkout-box">

        <h2>Administrator Login</h2>

        <?php if (!empty($message)) { ?>

            <div class="message">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <form method="POST">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                required
            >


            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                required
            >


            <button
                type="submit"
                class="checkout-btn"
            >
                Login 🔐
            </button>

        </form>

    </div>

</section>

</body>

</html>