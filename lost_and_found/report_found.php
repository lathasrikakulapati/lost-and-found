<?php
session_start();
include 'config.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $lost_item_id = $_POST['lost_item_id'];
    $found_description = $_POST['found_description'];
    $contact_info = $_POST['contact_info'];
    $finder_id = $_SESSION['user_id'];
    $date_found = date("Y-m-d");
    
    $sql = "INSERT INTO found_items (lost_item_id, finder_id, found_description, contact_info, date_found) VALUES ('$lost_item_id', '$finder_id', '$found_description', '$contact_info', '$date_found')";
    mysqli_query($conn, $sql);
    
    mysqli_query($conn, "UPDATE lost_items SET found = 1 WHERE id = '$lost_item_id'");
    echo "Item found report submitted!";
}
?>
<form method="POST">
    Lost Item ID: <input type="text" name="lost_item_id" required><br>
    Found Description: <textarea name="found_description" required></textarea><br>
    Contact Info: <input type="text" name="contact_info" required><br>
    <input type="submit" value="Report Found Item">
</form>