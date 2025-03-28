<?php 
// Include database connection
require 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: url('background.png') no-repeat center center fixed;
            background-size: cover;
        }
        .navbar {
            background: rgba(0, 0, 0, 0.7);
            padding: 1rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .navbar h1 {
            color: white;
            margin: 0;
        }
        .navbar ul {
            list-style: none;
            display: flex;
            gap: 1rem;
            margin: 0;
            padding: 0;
        }
        .navbar ul li {
            display: inline;
        }
        .navbar ul li a {
            color: white;
            text-decoration: none;
            padding: 0.5rem 1rem;
            transition: background 0.3s;
        }
        .navbar ul li a:hover {
            background: #016064;
            border-radius: 5px;
        }
        .content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 90vh;
            color: white;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 1rem;
            background: rgba(0, 0, 0, 0.7);
            color: white;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Lost and Found</h1>
        <ul>
            <li><a href="#">Home</a></li>
            <li><a href="#">About Us</a></li>
            <li><a href="#">Contact</a></li>
            <li><a href="login.php">Login</a></li>
            <li><a href="register.php">Register</a></li>
        </ul>
    </nav>
    <div class="content">
        <h2>Welcome to Lost and Found System</h2>
    </div>
    <div class="footer">
        <p>&copy; <?php echo date("Y"); ?> Lost and Found. All rights reserved.</p>
    </div>
</body>
</html>