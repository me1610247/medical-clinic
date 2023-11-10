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

    // Retrieve the major's data from the database
    $major = $connection->read('major',"id = $id");

    if (!empty($major)) {
        // The major exists; retrieve its data
        $majorData = $major[0];
        // You can access the major's data like $majorData['name'] and $majorData['image']
    } else {
        // The major does not exist; handle the error
        echo "Major not found.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<form action="updateMajor.php" method="POST" enctype="multipart/form-data">
    <!-- Include hidden input field for the major's ID -->
    <input type="hidden" name="major_id" value="<?php echo $id; ?>">

    <input type="file" value="<?=$majorData['image']?>" name="majorimg" id="">
    <div class="card-body d-flex flex-column gap-1 justify-content-center">
        <input type="text" name="majorname" id="" placeholder="Edit Major" value="<?php echo $majorData['name']; ?>">
        <input type="submit" name="Update" value="Update" class="btn btn-primary" id="">
    </div>
</form>
</body>
</html>