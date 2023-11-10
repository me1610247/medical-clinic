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
                        <a type="button" class="btn btn-outline-light navigation--button"
                        href="booking.php">Booking</a>
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
    </div>
        <div class="container">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item"><a class="text-decoration-none" href="../index.php">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Admin</li>
                </ol>
            </nav>
    <div class="container">
        <h2 class="h1 fw-bold  my-4">Users</h2>
        <a class="btn btn-dark text-center" href="../admin/users.php">Show</a>
        <h2 class="h1 fw-bold  my-4">Booking</h2>
        <a class="btn btn-dark text-center" href="../admin/booking.php">Show</a>
        <h2 class="h1 fw-bold  my-4">Doctors</h2>
        <a class="btn btn-dark text-center" href="../admin/doctor.php">Show</a>
        <h2 class="h1 fw-bold  my-4">Majors</h2>
        <a class="btn btn-dark text-center" href="../admin/major.php">Show</a>
        <h2 class="h1 fw-bold  my-4">Messages</h2>
        <a class="btn btn-dark text-center" href="../admin/message.php">Show</a>




    </div>

</body>
</html>