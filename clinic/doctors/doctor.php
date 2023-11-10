<?php
session_start();
include("../../database.php");
include("../../validation.php");
$host = "localhost";
$username = "root";
$passwordDb = "";
$databaseName = "clinic";
$connection = new Database($host, $username, $passwordDb, $databaseName);
$conn = $connection->connect();
$doctors=$connection->read("doctors");
if (isset($_SESSION['auth'])) {
    $email = $_SESSION['auth'];
    $query = "SELECT name, email, role,phone,id FROM users WHERE email = '$email'";
    $query_run = mysqli_query($conn, $query);

    if ($query_run && mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $userName = $row['name'];
        $userId=$row['id'];
        $userPhone=$row['phone'];
        $userEmail=$row['email'];
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
if(isset($_GET['id'])){
    $docid = $_GET['id'];
    $query="SELECT name,bio,image,major_id FROM doctors where id='$docid'";
    $query_run = mysqli_query($conn, $query);
    if(mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $name= $row["name"];
        $image=$row['image'];
        $bio=$row['bio'];
        $majorId=$row['major_id'];
        $query="SELECT name from major where id='$majorId'";
        $query_run = mysqli_query($conn, $query);
        $majorRow=mysqli_fetch_assoc($query_run);
        $docMajor=$majorRow['name'];

    }
}
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['confirm'])){
        $username=$_POST['userName'];
        $Phone=$_POST['UserPhone'];
        $Email=$_POST['UserEmail'];
        $email=mysqli_real_escape_string($conn,$_POST['UserEmail']);
        $check_email_query="SELECT email FROM users where email='$email'";
        $check_email_query_run=mysqli_query($conn,$check_email_query);
        $invalidFields = [];
        if(Validation::validateEmpty($_POST['userName'])){
            $invalidFields[]="Name is Required";
        }
        if(Validation::validateEmpty($_POST['UserPhone'])){
            $invalidFields[]="Phone is Required";
        }
        if(Validation::validateEmpty($_POST['UserEmail'])){
            $invalidFields[]="Email is Required";
        }
        if(mysqli_num_rows($check_email_query_run)==0){
            $invalidFields[]="Email Doesn't Exist";
        }
        if(!empty($invalidFields)){
            $_SESSION['invalidError']=$invalidFields;
        }
        else{
        $data=array(
            'user_id'=>$userId,
            'user_name'=>$username,
            'user_phone'=>$Phone,
            'user_email'=> $Email,
            'doctor_id'=>$docid,
        );
        $result=$connection->create('booking',$data);
        if ($result !== false) {
            // Data insertion was successful
            echo '<div class="alert alert-success">'.
            'Booking Successfully'
            .'</div>';
            
        } else {
            // Data insertion failed
            echo "Failed to insert data.";
        }

    }
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
    <link rel="stylesheet" href="../assets/styles/pages/main.css">

    <title>Document</title>
</head>

<body>
    <div class="page-wrapper">
        <nav class="navbar navbar-expand-lg navbar-expand-md bg-blue sticky-top">
            <div class="container">
                <div class="navbar-brand">
                    <a class="fw-bold text-white m-0 text-decoration-none h3" href="../index.php">VCare</a>
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
                        <a type="button" class="btn btn-outline-light navigation--button" href="../index.php">Home</a>
                        <?php
                        if(isset($_SESSION['admin'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="../../admin/admin.php"><?=$_SESSION['admin']?></a>
                        <?php
                        }
                        ?>                    
                            <a type="button" class="btn btn-outline-light navigation--button"
                            href="../majors.php">majors</a>
                        <a type="button" class="btn btn-outline-light navigation--button"
                        href="../booking.php">Booking</a>
                            <?php
                        if(!isset($_SESSION['auth'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="../login.php">login</a>
                        <?php
                        }
                        ?>  
                           <?php
                        if(isset($_SESSION['auth'])){
                        ?>
                        <a type="button" class="btn btn-outline-light navigation--button" href="../logout.php">logout</a>
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
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="./index.php">doctors</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page"><?=$name?></li>
                </ol>
            </nav>
            <div class="d-flex flex-column gap-3 details-card doctor-details">
                <div class="details d-flex gap-2 align-items-center">
                    <img src="../../uploadsdoc/<?=$image?>" alt="doctor" class="img-fluid rounded-circle" height="150"
                        width="150">
                    <div class="details-info d-flex flex-column gap-3 ">
                        <h4 class="card-title fw-bold"><?=$name?></h4>
                        <div class="d-flex gap-3 align-bottom">
                            <ul class="rating">
                                <li class="star"></li>
                                <li class="star"></li>
                                <li class="star"></li>
                                <li class="star"></li>
                                <li class="star"></li>
                            </ul>
                            <a href="#" class="align-baseline">submit rating</a>
                        </div>
                        <h6 class="card-title fw-bold"><?=$bio?></h6>
                    </div>
                </div>
                <hr />
                <form method="POST" class="form">
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
                            <input type="text" name="userName" value="<?= $userName ?>" class="form-control" id="name" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="phone">Phone</label>
                            <input type="tel" name="UserPhone" value="<?=$userPhone?>" class="form-control" id="phone" >
                        </div>
                        <div class="mb-3">
                            <label class="form-label required-label" for="email">Email</label>
                            <input type="email" name="UserEmail" value="<?=$userEmail?>" class="form-control" id="email" >
                        </div>
                    </div>
                    <button type="submit" name="confirm" class="btn btn-primary">Confirm Booking</button>
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
                    <a href="../index.php" class="link text-white">Home</a>
                    <a href="../majors.php" class="link text-white">Majors</a>
                    <a href="./index.php" class="link text-white">Doctors</a>
                    <a href="../login.php" class="link text-white">Login</a>
                    <a href="../register.php" class="link text-white">Register</a>
                    <a href="../contact.php" class="link text-white">Contact</a>
                </div>
            </div>
        </div>
    </footer>
    <script>
        const stars = document.querySelectorAll('.star');

        stars.forEach((star, index) => {
            star.addEventListener('click', () => {
                const isActive = star.classList.contains('active');
                if (isActive) {
                    star.classList.remove('active');
                } else {
                    star.classList.add('active');
                }
                for (let i = 0; i < index; i++) {
                    stars[i].classList.add('active');
                }
                for (let i = index + 1; i < stars.length; i++) {
                    stars[i].classList.remove('active');
                }
            });
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.1/js/bootstrap.min.js"
        integrity="sha512-fHY2UiQlipUq0dEabSM4s+phmn+bcxSYzXP4vAXItBvBHU7zAM/mkhCZjtBEIJexhOMzZbgFlPLuErlJF2b+0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
</body>

</html>