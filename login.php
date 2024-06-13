<?php
session_start();
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username_or_email = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username_or_email) || empty($password)) {
        echo "Please fill all fields.";
    } else {
        // Fetch user from database
        $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE username = ? OR email = ?");
        $stmt->bind_param("ss", $username_or_email, $username_or_email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            // Verify password
            if (password_verify($password, $user['password'])) {
                // Create session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $user['email'];

                // Redirect to dashboard
                header("Location: dashboard.php");
            } else {
                echo "Invalid password.";
            }
        } else {
            echo "No user found.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <!-- <link href="https://fonts.googleapis.com/css2?family=Work+Sans:wght@200;300&display=swap" rel="stylesheet"> -->
    <link rel="stylesheet" href="style.min.css">
</head>
<body class="auth-page">
    <section>
        <div class="login-page">
            <div class="form">
            <h2>Login</h2>
                <form class="login-form" action="login.php" method="post">
                    <label for="username">Username or Email:</label>
                    <input class="form-text" type="text" id="username" name="username" required><br>
                    
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required><br>
                    
                    <button type="submit">Login</button>
                    <p class="message">Not registered? <a href="index.php">Create an account</a></p>
                </form>
            </div>
        </div>
    </section>
</body>
</html>
