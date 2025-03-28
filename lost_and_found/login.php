<?php
// Start session
session_start();

// Include database connection
require 'config.php'; // Make sure this file exists and contains the correct DB credentials

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email exists in the database
    $query = $conn->prepare("SELECT id, email, password FROM users WHERE email = ?");
    $query->bind_param("s", $email);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if (password_verify($password, $user['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];

            // Redirect to dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            $errorMessage = "Invalid email or password!";
        }
    } else {
        $errorMessage = "User not found!";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background: #f9f9f9;
        }
        .main-container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #d8e5d1;
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 350px;
        }
        .login-box h1 {
            text-align: center;
            margin-bottom: 1rem;
            color: #333;
        }
        .login-box label {
            display: block;
            margin: 0.5rem 0 0.2rem;
            font-weight: bold;
        }
        .login-box input {
            width: 100%;
            padding: 0.8rem;
            margin-bottom: 1rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-box button {
            width: 100%;
            padding: 0.8rem;
            background: #232f3e;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .login-box button:hover {
            background: #016064;
        }
        .error-message {
            color: red;
            margin-bottom: 1rem;
            text-align: center;
        }
    </style>
</head>
<body>
    <main class="main-container">
        <div class="login-box">
            <h1>Login</h1>
            <?php if (!empty($errorMessage)) { echo "<p class='error-message'>$errorMessage</p>"; } ?>
            <form method="POST" action="login.php">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                <button type="submit">Login</button>
            </form>
            <p style="text-align:center;">Don't have an account? <a href="register.php">Register here</a></p>
        </div>
    </main>
</body>
</html>
