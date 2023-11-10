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
$majors = $connection->read('major');
// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form was submitted with the "Add" button
    if (isset($_POST['Add'])) {
        // Retrieve the uploaded image file

        // Extract the title from the form submission
        $title = $_POST['majorname'];
        $image_name = $_FILES['majorimg']['name'];
        $image_tmp = $_FILES['majorimg']['tmp_name'];
        $image_path = "../uploadsmajor/" . $image_name;
    
        move_uploaded_file($image_tmp, $image_path);
        // Check if an image was uploaded
            // Define the directory where the image will be saved
          
            // Create an associative array with the data to be inserted into the database
            $data = array(
                'image' => $image_name,
                'name' => $title
            );

            // Call the create() method to insert the data into the database
            $result = $connection->create('major', $data);

            if ($result !== false) {
                // Data insertion was successful
                echo "Data inserted successfully.";

            } else {
                // Data insertion failed
                echo "Failed to insert data.";
            }
        } else {
            // Handle the case where no image was uploaded or an error occurred
            echo "Error uploading the image.";
    }
}
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

    <title>Major</title>
</head>
<body>
<div class="container text-center">
            <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb" class="fw-bold my-4 h4">
                <ol class="breadcrumb justify-content-center">
                    <li class="breadcrumb-item active" aria-current="page">majors</li>
                </ol>
            </nav>
            <form action="" method="POST" enctype="multipart/form-data">
            <div class="card p-2 text-center" style="width: 18rem;">
                <h2>Add Major</h2>
                <input type="file" name="majorimg" id="">
                <div class="card-body d-flex flex-column gap-1 justify-content-center">
                    <input type="text" name="majorname" id="" placeholder="Add Major">
                    <input type="submit" name="Add" value="Add" class="btn btn-primary" id="">
                </div>
            </div>
        </form>
        <br>
            <div class="majors-grid">
            <?php
        // Iterate over the majors data and generate HTML code for each major
        foreach ($majors as $major) {
            // Extract the relevant data from the $major array
            $image = $major['image'];
            $title = $major['name'];
            $id=$major['id']

            // Generate the dynamic HTML code for each major
            ?>
            <div class="card p-2" style="width: 18rem;">
                <img src="../uploadsmajor/<?= $image; ?>" class="card-img-top rounded-circle card-image-circle" alt="major">
                <div class="card-body d-flex flex-column gap-1 justify-content-center">
                    <h4 class="card-title fw-bold text-center"><?php echo $title; ?></h4>
                    <a href="editMajor.php?id=<?=$id?>" class="btn btn-outline-primary card-button">Edit</a>
                    <a href="deleteMajor.php?id=<?=$id?>" class="btn btn-outline-danger card-button">Delete</a>
                </div>
            </div>
            <?php
        }
        ?>
            </div>
      
</div>
</body>
<style>
    .majors-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(18rem, 1fr));
    gap: 1rem;
    justify-items: center;
}
</style>
</html>
