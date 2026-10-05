<?php

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"]);
    $email = trim($_POST["email"]);
    $phone = trim($_POST["phone"]);
    $address = trim($_POST["address"]);
    $password = $_POST["password"];

    if (empty($name) || empty($email) || empty($password)) {

        $message = "Please fill in all required fields.";

    } else {

        // Check if email already exists
        $check = $conn->prepare(
            "SELECT id FROM users WHERE email = ?"
        );

        $check->bind_param("s", $email);

        $check->execute();

        $result = $check->get_result();

        if ($result->num_rows > 0) {

            $message = "Email already registered.";

        } else {

            // Securely hash password
            $hashedPassword = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $stmt = $conn->prepare(
                "INSERT INTO users
                (name, email, password, phone, address)
                VALUES (?, ?, ?, ?, ?)"
            );

            $stmt->bind_param(
                "sssss",
                $name,
                $email,
                $hashedPassword,
                $phone,
                $address
            );

            if ($stmt->execute()) {

                $message =
                    "Registration successful! You can now login.";

            } else {

                $message =
                    "Something went wrong. Please try again.";
            }

            $stmt->close();
        }

        $check->close();
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Register</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>

<header class="header">

    <div class="logo">
        🍎 Fresh<span>Fruit</span>
    </div>

    <nav class="navbar">

        <a href="index.php">Home</a>

        <a href="fruits.php">Fruits</a>

        <a href="login.php">Login</a>

    </nav>

</header>


<section class="auth-section">

    <div class="auth-box">

        <h1>Create Account</h1>

        <p>Join FreshFruit today 🍎</p>


        <?php if (!empty($message)) { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <form method="POST">

            <input
                type="text"
                name="name"
                placeholder="Full Name"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="text"
                name="phone"
                placeholder="Phone Number"
            >

            <textarea
                name="address"
                placeholder="Delivery Address"
            ></textarea>

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">
                Create Account
            </button>

        </form>


        <p class="auth-link">

            Already have an account?

            <a href="login.php">
                Login
            </a>

        </p>

    </div>

</section>

</body>
</html>