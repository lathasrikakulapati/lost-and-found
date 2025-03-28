<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $lost_item_id = $_POST['lost_item_id'];
    $found_by = $_SESSION['user_id'];
    $meet_location = $_POST['meet_location'];
    $meet_time = $_POST['meet_time'];
    $contact_info = $_POST['contact_info'];

    // Use Prepared Statement to prevent SQL Injection
    $stmt = $conn->prepare("INSERT INTO found_items (lost_item_id, found_by, meet_location, meet_time, contact_info) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iisss", $lost_item_id, $found_by, $meet_location, $meet_time, $contact_info);

    if ($stmt->execute()) {
        $success_message = "Details submitted successfully!";
        // Redirect after 2 seconds
        header("refresh:2; url=dashboard.php");
    } else {
        $error_message = "Error submitting details.";
    }
    $stmt->close();
}

$lost_item_id = $_GET['lost_item_id'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Found Details</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: center;
        }
        h1 {
            color: #232f3e;
            margin-bottom: 20px;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin-top: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            font-size: 1rem;
        }
        button {
            background: #016064;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background: #014d4d;
        }
        .message {
            margin-top: 15px;
            font-size: 1rem;
            color: green;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Enter Meet-Up Details</h1>
        <?php if (isset($success_message)) { echo "<p class='message'>$success_message</p>"; } ?>
        <?php if (isset($error_message)) { echo "<p class='message error'>$error_message</p>"; } ?>
        <form method="POST">
            <input type="hidden" name="lost_item_id" value="<?= htmlspecialchars($lost_item_id) ?>">
            <input type="text" name="meet_location" placeholder="Meet Location" required>
            <input type="datetime-local" name="meet_time" required>
            <input type="text" name="contact_info" placeholder="Contact Info" required>
            <button type="submit">Submit</button>
        </form>
    </div>
</body>
</html>
