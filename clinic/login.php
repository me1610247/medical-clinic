<?php
session_start();
include("../database.php");

$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();

if (isset($_SESSION['auth'])) {
    $email = $_SESSION['auth'];
    $query = $connection->read('users', "email = '$email'");
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

// Handle login form submission
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    // Validate user credentials
    $query = "SELECT * FROM users WHERE email = '$email' AND password = '$password'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        // User credentials are valid
        $_SESSION['auth'] = $email;

        $row = mysqli_fetch_assoc($query_run);
        $userName = $row['name'];

        if ($row['role'] == 1) {
            $_SESSION['admin'] = "Go To Dashboard";
        } elseif ($row['role'] == 0) {
            unset($_SESSION['admin']);
        }

        header("location: index.php");
        exit;
    } else {
        // User credentials are invalid
        $_SESSION['error'] = "Invalid email or password";
        header("location: login.php");
        exit;
    }
}

// Rest of your HTML code
?>
<!DOCTYPE html>
<html lang="en">

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
                    ?>                          <a type="button" class="btn btn-outline-light navigation--button" href="./index.php">Home</a>
                        <?php
                        if(isset($_SESSION['admin'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href=""><?=$_SESSION['admin']?></a>
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
                        <a type="button" class="btn btn-outline-light navigation--button" href="./login.php">login</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">login</li>
                </ol>
            </nav>
            <div class="d-flex flex-column gap-3 account-form  mx-auto mt-5">
                <form method="POST" class="form">
                            <?php
                            if(isset($_SESSION['error'])){
                            ?>
                            <div class="alert alert-danger">
                                <?=$_SESSION['error']?>
                            </div>
                            <?php
                            }
                            unset($_SESSION['error']);
                            ?>
                    <div class="mb-3">
                        <label class="form-label required-label" for="email">Email</label>
                        <input type="email" name="email" class="form-control" id="email" >
                    </div>
                    <div class="mb-3">
                        <label class="form-label required-label" for="password">password</label>
                        <input type="password" name="password" class="form-control" id="password" >
                    </div>
                    <button type="submit" name="login" class="btn btn-primary">Login</button>
                </form>
                <div class="d-flex justify-content-center gap-2 flex-column flex-lg-row flex-md-row flex-sm-column">
                    <span>don't have an account?</span><a class="link" href="./register.php">create account</a>
                </div>
            </div>

        </div>
    </div>
    <footer class="container-fluid bg-blue text-white py-3">
        <div class="row gap-2">

            <div class="col-sm order-sm-1">
                <h1 class="h1">About Us</h1>
                <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Ipsa nesciunt repellendus itaque,
                    laborum
                    saepe
                    enim maxime, delectus, dicta cumque error cupiditate nobis officia quam perferendis consequatur
                    cum
                    iure
                    quod facere.</p>
            </div>
            <div class="col-sm order-sm-2">
                <h1 class="h1">Links</h1>
                <div class="links d-flex gap-2 flex-wrap">
                    <a href="./index.php" class="link text-white">Home</a>
                    <a href="./majors.php" class="link text-white">Majors</a>
                    <?php
                    if(isset($_SESSION['auth'])){
                    ?>
                    <a href="./booking.php" class="link text-white">Booking</a>
                    <a href="./contact.php" class="link text-white">Contact</a>
                    <?php
                    }
                    ?>
                    <?php
                    if(!isset($_SESSION['auth'])){
                    ?>
                    <a href="./login.php" class="link text-white">Login</a>
                    <a href="./register.php" class="link text-white">Register</a>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"
        integrity="sha512-fHY2UiQlipUq0dEabSM4s+phmn+bcxSYzXP4vAXItBvBHU7zAM/mkhCZjtBEIJexhOMzZbgFlPLuErlJF2b+0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>