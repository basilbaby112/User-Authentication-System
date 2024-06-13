<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

echo "Welcome, " . htmlspecialchars($_SESSION['username']) . "!";

include 'header.php';
?>

<div class="container mt-4">
    <h1 class="mt-2">Dashboard</h1>
</div>

<?php
// Include the footer file
include 'footer.php';
?>
