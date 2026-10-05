<?php

session_start();

require_once "config/database.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST["email"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare(
        "SELECT id, name, email, password, role
         FROM users
         WHERE email = ?"
    );

    $stmt->bind_param("s", $email);

    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows == 1) {

        $user = $result->fetch_assoc();

        if (password_verify(
            $password,
            $user["password"]
        )) {

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_role"] = $user["role"];

            header("Location: fruits.php");
            exit;

        } else {

            $message = "Incorrect password.";

        }

    } else {

        $message = "Account not found.";

    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>FreshFruit | Login</title>

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

        <a href="register.php">
            Register
        </a>

    </nav>

</header>


<section class="auth-section">

    <div class="auth-box">

        <h1>Welcome Back 👋</h1>

        <p>Login to your FreshFruit account</p>


        <?php if (!empty($message)) { ?>

            <div class="message">
                <?php echo htmlspecialchars($message); ?>
            </div>

        <?php } ?>


        <form method="POST">

            <input
                type="email"
                name="email"
                placeholder="Email Address"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Password"
                required
            >

            <button type="submit">
                Login
            </button>

        </form>


        <p class="auth-link">

            Don't have an account?

            <a href="register.php">
                Create Account
            </a>

        </p>

    </div>

</section>

</body>
</html>