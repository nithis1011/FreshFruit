<?php

session_start();

require_once "config/database.php";

// Check admin login
if (!isset($_SESSION["admin_id"])) {
    header("Location: admin-login.php");
    exit;
}

// Check fruit ID
if (!isset($_GET["id"]) || !is_numeric($_GET["id"])) {
    header("Location: admin-fruits.php");
    exit;
}

$id = intval($_GET["id"]);

// Delete fruit
$stmt = $conn->prepare(
    "DELETE FROM fruits WHERE id = ?"
);

$stmt->bind_param("i", $id);

if ($stmt->execute()) {

    header("Location: admin-fruits.php");
    exit;

} else {

    echo "Error deleting fruit: " . $stmt->error;

}

$stmt->close();
$conn->close();

?>