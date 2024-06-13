<?php
session_start();
require 'config.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Delete from database
    $stmt = $conn->prepare("DELETE FROM products WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo "Product deleted successfully.";
        header("Location: products.php");
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
}
?>
