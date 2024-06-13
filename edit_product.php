<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = $conn->query("SELECT * FROM products WHERE id = $id");
    $product = $result->fetch_assoc();
}
//post data
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $name = trim($_POST['name']);
    $price = trim($_POST['price']);

    // Validation
    if (empty($name) || empty($price)) {
        echo "Please fill all fields.";
    } else if (!is_numeric($price)) {
        echo "Invalid price.";
    } else {
        // Update database
        $stmt = $conn->prepare("UPDATE products SET name = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sdi", $name, $price, $id);

        if ($stmt->execute()) {
            echo "Product updated successfully.";
            header("Location: products.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}

include 'header.php';
?>
<div class="container mt-4">
    <h2 class="mt-2">Edit Product</h2>
    <form action="edit_product.php?id=<?php echo $id; ?>" method="post">
        <label class="form-label" for="name">Name:</label>
        <input class="form-control" type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required><br>
        
        <label class="form-label" for="price">Price:</label>
        <input class="form-control" type="number" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required><br>
        
        <button class="btn btn-info" type="submit">Update Product</button>
    </form>
</div>
<?php
include 'footer.php';
?>
