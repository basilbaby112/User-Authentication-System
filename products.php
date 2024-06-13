<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$result = $conn->query("SELECT id, name, price FROM products");

include 'header.php';
?>
<div class="container mt-4">
<a class="btn btn-primary" href="add_product.php">Add New Product</a>
    <h2 class="mt-2">Product Listing</h2>
    <table class="table">
        <tr>
            <th>Name</th>
            <th>Price</th>
            <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['name']); ?></td>
                <td><?php echo htmlspecialchars($row['price']); ?></td>
                <td>
                    <a href="edit_product.php?id=<?php echo $row['id']; ?>">Edit</a>
                    <a href="delete_product.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</div>
<?php
    include 'footer.php';
?>
