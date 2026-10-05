<?php

session_start();

if (!isset($_SESSION["last_order_id"])) {

    header("Location: index.php");

    exit;
}

$order_id = $_SESSION["last_order_id"];

unset($_SESSION["last_order_id"]);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Order Successful</title>

    <link rel="stylesheet"
          href="css/style.css">

</head>

<body>


<section class="success-section">

    <div class="success-box">

        <div class="success-icon">
            ✅
        </div>

        <h1>Order Placed Successfully!</h1>

        <p>
            Thank you for shopping with FreshFruit.
        </p>

        <p>
            Your Order ID is:
            <strong>
                #<?php echo $order_id; ?>
            </strong>
        </p>

        <div class="success-buttons">

            <a
                href="fruits.php"
                class="btn primary-btn"
            >
                Continue Shopping
            </a>

            <a
                href="index.php"
                class="btn secondary-btn"
            >
                Go Home
            </a>

        </div>

    </div>

</section>


</body>
</html>