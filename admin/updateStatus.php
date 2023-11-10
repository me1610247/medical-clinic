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
// Assuming you have a database connection established ($conn)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve the booking ID and status from the POST request
    $bookingId = $_POST['bookingId'];
    $status = $_POST['status'];

    // Update the status in the database
    $updateQuery = "UPDATE booking SET status='$status' WHERE id='$bookingId'";
    $updateQueryRun = mysqli_query($conn, $updateQuery);
    header("location:booking.php");
    

}