<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    // Validation
    if (empty($name) || empty($price)) {
        echo "Please fill all fields.";
    } else if (!is_numeric($price)) {
        echo "Invalid price.";
    } else {
        // Insert into database
        $stmt = $conn->prepare("INSERT INTO products (name, price) VALUES (?, ?)");
        $stmt->bind_param("sd", $name, $price);

        if ($stmt->execute()) {
            echo "Product added successfully.";
            header("Location: products.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Include the header file
include 'header.php';
?>
<div class="container mt-4">
    <h2>Add Product</h2>
    <form action="add_product.php" method="post">
        <label class="form-label" for="name">Name:</label>
        <input class="form-control" type="text" id="name" name="name" required><br>
        
        <label class="form-label" for="price">Price:</label>
        <input class="form-control" type="number" id="price" name="price" required><br>
        
        <button class="btn btn-info" type="submit">Add Product</button>
    </form>
</div>

<?php
// Include the footer file
include 'footer.php';
?>
