<?php
session_start();
include("../database.php");
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
// to show only the booking that belong to the user that logged in
if (isset($_SESSION['auth'])) {
    $email = $_SESSION['auth'];
    $bookings = $connection->read('booking', "user_email = '$email'");
} else {
    // Handle the case when the user is not logged in
    $bookings = array(); // Empty array
}if (isset($_SESSION['auth'])) {
    $email = $_SESSION['auth'];
    $query = "SELECT name, email, role FROM users WHERE email = '$email'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $userName = $row['name'];
        if ($row['role'] == 1) {
            $_SESSION['admin'] = "Go To Dashboard";
        } elseif ($row['role'] == 0) {
            unset($_SESSION['admin']);
        }
    } else {
        $userName = "Unknown";
    }
} else {
    $userName = "Unknown";
}
// Rest of your HTML code
?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/js/splide.min.js"
        integrity="sha512-4TcjHXQMLM7Y6eqfiasrsnRCc8D/unDeY1UGKGgfwyLUCTsHYMxF7/UHayjItKQKIoP6TTQ6AMamb9w2GMAvNg=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/splidejs/4.1.4/css/themes/splide-default.min.css"
        integrity="sha512-KhFXpe+VJEu5HYbJyKQs9VvwGB+jQepqb4ZnlhUF/jQGxYJcjdxOTf6cr445hOc791FFLs18DKVpfrQnONOB1g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.min.css"
        integrity="sha512-Z/def5z5u2aR89OuzYcxmDJ0Bnd5V1cKqBEbvLOiUNWdg9PQeXVvXLI90SE4QOHGlfLqUnDNVAYyZi8UwUTmWQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/css/bootstrap.rtl.min.css"
        integrity="sha512-wO8UDakauoJxzvyadv1Fm/9x/9nsaNyoTmtsv7vt3/xGsug25X7fCUWEyBh1kop5fLjlcrK3GMVg8V+unYmrVA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
    <link rel="stylesheet" href="assets/styles/pages/main.css">

    <title>Document</title>
</head>

<body>
    <div class="page-wrapper">
    <nav class="navbar navbar-expand-lg navbar-expand-md bg-blue sticky-top">
            <div class="container">
                <div class="navbar-brand">
                    <a class="fw-bold text-white m-0 text-decoration-none h3" href="./index.php">
                        Vcare
                    </a>
                </div>
                
                <button class="navbar-toggler btn-outline-light border-0 shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <div class="d-flex gap-3 flex-wrap justify-content-center" role="group">
                        <?php
                        if(isset($_SESSION["auth"])) {
                        ?>
                    <a type="button" class="btn btn-outline-light navigation--button" href=""><?=ucwords($userName)?></a>
                    <?php
                        }
                    ?>  
                    <a type="button" class="btn btn-outline-light navigation--button" href="./index.php">Home</a>
                        <?php
                        if(isset($_SESSION['admin'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="../admin/admin.php"><?=$_SESSION['admin']?></a>
                        <?php
                        }
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button"
                            href="./majors.php">majors</a>
                            <?php
                          if  (isset($_SESSION['auth'])){
                            ?>
                        <a type="button" class="btn btn-outline-light navigation--button"
                            href="./booking.php">Booking</a>
                            <?php
                            }
                            ?>
                            <?php
                        if(!isset($_SESSION['auth'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="login.php">login</a>
                        <?php
                        }
                        ?>
                           <?php
                        if(isset($_SESSION['auth'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="logout.php">logout</a>
                        <?php
                        }
                        ?>  
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="../index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Booking</li>
                </ol>
            </nav>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Date</th>
                        <th scope="col">Doctor Name</th>
                        <th scope="col">Major</th>
                        <th scope="col">location</th>
                        <th scope="col">Status</th>
                        <th scope="col">Patient Name</th>
                    </tr>
                </thead>
                <tbody>
    <?php 
    if (empty($bookings)) {
        // Display an alert for no bookings within a table row
        echo '<tr><td colspan="7" class="alert alert-warning text-center">No bookings Found .</td></tr>';
    } else {
        foreach ($bookings as $key => $booking) {
            $doctorId = $booking['doctor_id'];
            $doctorQuery = "SELECT name, image, major_id FROM doctors where id='$doctorId'";
            $doctorQueryrun = mysqli_query($conn, $doctorQuery);
            $doctorResult = mysqli_fetch_assoc($doctorQueryrun);
            $doctorname = $doctorResult['name'];
            $doctorimg = $doctorResult['image'];
            $doctorMajorId = $doctorResult['major_id'];
            $doctorMajorQuery = "SELECT name from major where id='$doctorMajorId'";
            $doctorMajorQueryRun = mysqli_query($conn, $doctorMajorQuery);
            $doctorMajorResult = mysqli_fetch_assoc($doctorMajorQueryRun);
            $doctorMajor = $doctorMajorResult['name'];
    ?>
    <tr>
        <th scope="row"><?= $key + 1 ?></th>
        <td><?= $booking['date'] ?></td>
        <td class="d-flex align-items-center gap-2">
            <img src="../uploadsdoc/<?= $doctorimg ?>" alt="" width="25" height="25" class="rounded-circle">
            <a href="./doctors/doctor.html"><?= $doctorname ?></a>
        </td>
        <td><?= $doctorMajor ?></td>
        <td><a href="https://www.google.com/maps" target="_blank">eraasoft</a></td>
        <td><?= $booking['status'] == '0' ? "Pending" : ($booking['status'] == '1' ? "Accepted" : "Cancelled") ?></td>
        <td class=""><?= $booking['user_name'] ?></td>
    </tr>
    <?php }
    } ?>
</tbody>

            </table>
        </div>
    </div>