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

// Retrieve messages from the database

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $result = $connection->delete("users", "id=$id");
    if ($result) {
        header("location:users.php");
    }
}
?>