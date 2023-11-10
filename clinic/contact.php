<?php
session_start();
include("../database.php");
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
if($_SERVER['REQUEST_METHOD']==='POST'){
if(isset($_POST['sendMsg'])){
    $name= $_POST['name'];
    $email= $_POST['email'];
    $phone = $_POST['phone'];
    $subject=$_POST['subject'];
    $message=$_POST['message'];
    $data=array(
        "name"=>$name,
        "phone"=>$phone,
        "email"=>$email,
        "subject"=>$subject,
        "message"=>$message,
    );
    $result=$connection->create('contact',$data);
    // to prevent insert the same data again when refresh the page
    header("location:contact.php");
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
        <nav class="navbar navbar-expand-lg navbar-expand-md bg-blue sticky-top navbar-light">
            <div class="container">
                <div class="navbar-brand">
                    <a class="fw-bold text-white m-0 text-decoration-none h3" href="./index.php">VCare</a>
                </div>
                <button class="navbar-toggler btn-outline-light border-0 shadow-none" type="button"
                    data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                    aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                    <div class="d-flex gap-3 flex-wrap justify-content-center" role="group">
                        <a type="button" class="btn btn-outline-light navigation--button" href="./index.php">Home</a>
                        <?php
                          if  (isset($_SESSION['auth'])){
                            ?>
                        <a type="button" class="btn btn-outline-light navigation--button"
                            href="./booking.php">Booking</a>
                            <?php
                            }
                            ?>
                        <a type="button" class="btn btn-outline-light navigation--button"
                        href="./booking.php">Booking</a>
                        <a type="button" class="btn btn-outline-light navigation--button" href="./login.php">login</a>
                    </div>
                </div>
            </div>
        </nav>
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">contact</li>
                </ol>
            </nav>
            <div class="d-flex flex-column gap-3 account-form mx-auto mt-5">
                <form method="POST" class="form">
                    <div class="form-items">
                        <div class="mb-3">
                            <label class="form-label required-label" for="name">Name</label>
                            <input type="text" name="name" class="form-control" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="phone">Phone</label>
                            <input type="tel" name="phone" class="form-control" id="phone" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="email">Email</label>
                            <input type="email" name="email" class="form-control" id="email" required>
                        </div>
                        <div class="mb-3">
                            <?php
                        $majorQuery = "SELECT name FROM major";
                        $majorResult = mysqli_query($conn, $majorQuery);
                        ?>
                        <label for="">Choose Subject</label>
                        <select name="subject" id="">
                            <?php while ($majorRow = mysqli_fetch_assoc($majorResult)) : ?>
                                <option value="<?php echo $majorRow['name']; ?>"><?php echo $majorRow['name']; ?></option>
                            <?php endwhile; ?>
                        </select>                         </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="message">message</label>
                            <textarea class="form-control" name="message" id="message" required></textarea>
                        </div>
                    </div>
                    <button type="submit" name="sendMsg" value="sendMsg" class="btn btn-primary">Send Message</button>
                </form>
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