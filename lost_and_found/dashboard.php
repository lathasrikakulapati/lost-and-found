<?php 
// Include database connection
require 'config.php';
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle item removal
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_item'])) {
    $item_id = intval($_POST['remove_item']);

    // Delete related found item first
    $stmt = $conn->prepare("DELETE FROM found_items WHERE lost_item_id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();

    // Delete lost item
    $stmt = $conn->prepare("DELETE FROM lost_items WHERE id = ?");
    $stmt->bind_param("i", $item_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to avoid form resubmission
    header("Location: dashboard.php");
    exit();
}

// Fetch lost items with found status
$stmt = $conn->prepare("
    SELECT li.id, li.item_name, li.description, li.location, li.date_lost, 
           (SELECT COUNT(*) FROM found_items fi WHERE fi.lost_item_id = li.id) AS is_found
    FROM lost_items li
    WHERE li.user_id = ?
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$lost_items = $stmt->get_result();
$stmt->close();

// Fetch found items with meeting details
$stmt_found = $conn->prepare("
    SELECT fi.id, li.item_name, fi.meet_location, fi.meet_time, fi.contact_info 
    FROM found_items fi
    JOIN lost_items li ON fi.lost_item_id = li.id
    WHERE li.user_id = ?
");
$stmt_found->bind_param("i", $user_id);
$stmt_found->execute();
$found_items = $stmt_found->get_result();
$stmt_found->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Lost and Found</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
            background: #f4f4f4;
            display: flex;
        }
        .sidebar {
            width: 250px;
            background: #232f3e;
            height: 100vh;
            padding: 1rem;
            color: white;
            position: fixed;
        }
        .sidebar h2 {
            text-align: center;
            margin-bottom: 2rem;
        }
        .sidebar ul {
            list-style: none;
            padding: 0;
        }
        .sidebar ul li {
            padding: 1rem;
        }
        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
            transition: background 0.3s;
        }
        .sidebar ul li a:hover {
            background: #016064;
            border-radius: 5px;
        }
        .main-content {
            margin-left: 250px;
            padding: 2rem;
            flex-grow: 1;
        }
        .navbar {
            background: #232f3e;
            padding: 1rem;
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #016064;
        }
        .navbar a {
            color: white;
            text-decoration: none;
            margin-left: 1rem;
        }
        .dashboard-container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #232f3e;
            color: white;
        }
        .remove-btn {
            background-color: #d9534f;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .remove-btn:hover {
            background-color: #c9302c;
        }
    </style>
</head>
<body>
    <div class="sidebar">
        <h2>Dashboard</h2>
        <ul>
            <li><a href="dashboard.php">Home</a></li>
            <li><a href="view_lost.php">View Lost Items</a></li>
            <li><a href="report_lost.php">Report Lost Item</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </div>
    <div class="main-content">
        <div class="navbar">
            <h1>Lost and Found System</h1>
            <a href="logout.php">Logout</a>
        </div>
        <div class="dashboard-container">
            <h2>Welcome, <?php echo $_SESSION['user_email']; ?>!</h2>

            <h3>Your Lost Items</h3>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Description</th>
                    <th>Location</th>
                    <th>Date Lost</th>
                    <th>Action</th>
                </tr>
                <?php while ($row = $lost_items->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row['description']); ?></td>
                    <td><?php echo htmlspecialchars($row['location']); ?></td>
                    <td><?php echo htmlspecialchars($row['date_lost']); ?></td>
                    <td>
                        <?php if ($row['is_found']) { ?>
                            <form method="POST">
                                <input type="hidden" name="remove_item" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="remove-btn">Remove</button>
                            </form>
                        <?php } else { ?>
                            <span style="color: gray;">Not Found</span>
                        <?php } ?>
                    </td>
                </tr>
                <?php } ?>
            </table>

            <h3>Found Items & Meeting Details</h3>
            <table>
                <tr>
                    <th>Item Name</th>
                    <th>Meet Location</th>
                    <th>Meet Time</th>
                    <th>Contact Info</th>
                </tr>
                <?php while ($row_found = $found_items->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row_found['item_name']); ?></td>
                    <td><?php echo htmlspecialchars($row_found['meet_location']); ?></td>
                    <td><?php echo htmlspecialchars($row_found['meet_time']); ?></td>
                    <td><?php echo htmlspecialchars($row_found['contact_info']); ?></td>
                </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>
