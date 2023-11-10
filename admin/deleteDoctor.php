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
        if(isset($_GET['id'])){
            $id = $_GET['id'];
            $result=$connection->delete("doctors" , "id=$id");
            if($result){
                header("location:doctor.php");
            }

}
?>