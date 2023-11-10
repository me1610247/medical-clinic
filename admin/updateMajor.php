<?php
// updateMajor.php

require_once '../database.php';

// Start the session
session_start();
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Update'])) {
    // Get major data from the form
    $major_id = $_POST['major_id'];
    $major_name = $_POST['majorname'];
    $image_name = $_FILES['majorimg']['name'];
    $image_tmp = $_FILES['majorimg']['tmp_name'];
    $image_path = "../uploadsmajor/" . $image_name;

    move_uploaded_file($image_tmp, $image_path);
    // Update the major in the database
    $data = [
        'name' => $major_name,
        'image' => $image_name
        // Add other fields if needed
    ];
    $result = $connection->update('major', $data, "id = $major_id");

    if ($result) {
        // Major updated successfully
        header("Location:major.php");
        exit;
    } else {
        // Major update failed
        echo "Failed to update major.";
    }
}
