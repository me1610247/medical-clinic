<?php
session_start();
require_once '../database.php';
require_once '../validation.php';
// Start the session
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
$query = "SELECT name, email, role FROM users";
$query_run = mysqli_query($conn, $query);
if (isset($_SESSION['auth'])) {
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
function validMail($input){
    if(filter_var($input,FILTER_VALIDATE_EMAIL)){
        return true;
    }
    return false;
}
// Check if there is an admin user
if ($query_run && mysqli_num_rows($query_run) > 0) {
    while ($row = mysqli_fetch_assoc($query_run)) {
        if ($row['role'] == 1) {
            // Set the $isAdmin flag if an admin is found
            $isAdmin = true;
            break; // Exit the loop if an admin is found
        }
    }
}

// Set the admin session based on the $isAdmin flag

// Check if the user is logged in
if (isset($_POST['register'])) {
    // Check if the form is submitted
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Get user registration data from the form
        // Create a new Database object and connect to the database
        $host = "localhost";
        $username = "root";
        $passwordDb = "";
        $databaseName = "clinic";
        $connection = new Database($host, $username, $passwordDb, $databaseName);
        $conn = $connection->connect();
        $invalidFields = [];
        $email=mysqli_real_escape_string($conn,$_POST['email']);
$check_email_query="SELECT email FROM users where email='$email'";
$check_email_query_run=mysqli_query($conn,$check_email_query);
        if (Validation::validateEmpty($_POST['name'])) {
            $invalidFields[] = "Name is Empty";
        }
        if (Validation::validateEmpty($_POST['phone'])) {
            $invalidFields[] = "Phone is Empty";
        }
        
        if (Validation::validateEmpty($_POST['password'])) {
            $invalidFields[] = "Password is Empty";
        }
        if(!(validMail($_POST["email"]))) {
         $invalidFields[] = "Enter a valid Email";
        }
        if (Validation::validatePhone($_POST['phone'])) {
            $invalidFields[] = "Enter a Valid Phone";
        }
        if(mysqli_num_rows($check_email_query_run)>0){
            $invalidFields[]="Email Already Exist ! , Try Another one";
        }
        if (!empty($invalidFields)) {
            // Store the error messages in the session
            $_SESSION['invalidError'] = $invalidFields;
        } else {
            // All fields are valid, proceed with registration
            $data = [
                'name' => $_POST['name'],
                'phone' => $_POST['phone'],
                'email' => $_POST['email'],
                'password' => $_POST['password']
            ];

            $insertId = $connection->create('users', $data);

            if ($insertId) {
                $register_query = "SELECT * FROM users WHERE id = $insertId";
                $register_query_run = mysqli_query($conn, $register_query);
                $dataUser = mysqli_fetch_array($register_query_run);
                $email = $dataUser['email'];
                $_SESSION['auth'] = $email;

                header("Location: index.php");
                exit;
            } else {
                $_SESSION['error'] = "Something went wrong";
                exit;
            }
        }

        // Close the database connection
        $connection->close();
    }
}
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
    <link rel="stylesheet" href="./assets/styles/pages/main.css">

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
                    ?>                          <a type="button" class="btn btn-outline-light navigation--button" href="./index.php">Home</a>
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
                        <a type="button" class="btn btn-outline-light navigation--button" href="./login.php">login</a>
                        <?php
                        }
                        ?>  
                            <?php
                        if(isset($_SESSION['auth'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="./logout.php">logout</a>
                        <?php
                        }
                        ?>  

                         </div>
                </div>
            </div>
        </nav>
            <div class="d-flex flex-column gap-3 account-form mx-auto mt-5">
                <form  method="POST" class="form">
                <?php
   if (isset($_SESSION['invalidError']) && is_array($_SESSION['invalidError']) && count($_SESSION['invalidError']) > 0) {
    foreach ($_SESSION['invalidError'] as $error) {
        echo '<div class="alert alert-danger">' . $error . '</div>';
    }
    // Unset the session variable only when displaying the errors
    unset($_SESSION['invalidError']);
}
    ?>
                    <div class="form-items">
                        <div class="mb-3">
                            <label class="form-label required-label" for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" id="phone" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="email">Email</label>
                            <input type="text" name="email" class="form-control" id="email" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="password">password</label>
                            <input type="password" name="password" class="form-control" id="password" >
                        </div>
                    </div>
                    <button type="submit" name="register" class="btn btn-primary">Create account</button>
                </form>
                <div class="d-flex justify-content-center gap-2">
                    <span>already have an account?</span><a class="link" href="./login.php"> login</a>
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