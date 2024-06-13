<?php
session_start();
require 'config.php';
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
// Function to verify a CSRF token
function verifyToken($token) {
    if (!isset($_SESSION['token']) || $token !== $_SESSION['token']) {
        die("Invalid CSRF token");
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    verifyToken($_POST['token']); // Verify CSRF token

    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Validation
    if (empty($username) || empty($email) || empty($password)) {
        echo "Please fill all fields.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format.";
    } else {
        // Hash password
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Store in database
        $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $email, $hashed_password);

        if ($stmt->execute()) {
            echo "Registration successful.";
            header("Location: login.php");
        } else {
            echo "Error: " . $stmt->error;
        }
        $stmt->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link rel="stylesheet" href="style.min.css">
</head>
<body class="auth-page">
    <section>
        <div class="login-page">
            <div class="form">
            <h2>Register</h2>
            <form class="register-form" id="reg_form" method="post">
                <?php
                function generateToken() {
                    if (empty($_SESSION['token'])) {
                        $_SESSION['token'] = bin2hex(random_bytes(32));
                    }
                    echo '<input type="hidden" name="token" value="' . $_SESSION['token'] . '">';
                }
                generateToken();
                ?>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required><br>
                
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required><br>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required><br>
                
                <button type="submit">Register</button>
                <p class="message">Already registered? <a href="login.php">Sign In</a></p>
            </form>
            </div>
        </div>
    </section>
</body>
</html>

