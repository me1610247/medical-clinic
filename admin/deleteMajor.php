<?php
require_once '../database.php';

// Start the session
session_start();
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
// Check if the major ID is provided in the URL
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete the major from the database
    $result = $connection->delete('major', "id = $id");

    if ($result) {
        // Major deleted successfully
        header("Location: major.php");
        exit;
    } else {
        // Major deletion failed
        echo "Failed to delete major.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Include necessary styles and scripts -->
</head>
<body>
<div class="container text-center">
    <!-- Display a confirmation message and allow deletion -->
</div>
</body>
</html>
