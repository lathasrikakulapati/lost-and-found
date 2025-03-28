<?php
session_start();
include 'config.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get found items only for the logged-in user (the original owner)
$query = "SELECT fi.*, li.item_name, u.username AS found_by_user 
          FROM found_items fi
          JOIN lost_items li ON fi.lost_item_id = li.id
          JOIN users u ON fi.found_by = u.id
          WHERE li.user_id = '$user_id'";

$result = mysqli_query($conn, $query);
?>
<!DOCTYPE html>
<html>
<head>
    <title>Found Items</title>
</head>
<body>
    <h1>Your Found Items</h1>
    <table border="1">
        <tr>
            <th>Item Name</th>
            <th>Found By</th>
            <th>Meet Location</th>
            <th>Meet Time</th>
            <th>Contact Info</th>
        </tr>
        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= htmlspecialchars($row['found_by_user']) ?></td>
            <td><?= htmlspecialchars($row['meet_location']) ?></td>
            <td><?= htmlspecialchars($row['meet_time']) ?></td>
            <td><?= htmlspecialchars($row['contact_info']) ?></td>
        </tr>
        <?php } ?>
    </table>
    <br>
    <a href="dashboard.php">Go Back</a>
</body>
</html>
