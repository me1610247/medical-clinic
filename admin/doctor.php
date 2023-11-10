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
$doctors=$connection->read("doctors");
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['Add'])){
        $name=$_POST['docName'];
        $majorName=$_POST['majorName'];
        $docBio=$_POST['docBio'];
        $image_name = $_FILES['docImg']['name'];
        $image_tmp = $_FILES['docImg']['tmp_name'];
        $image_path = "../uploadsdoc/" . $image_name;
        move_uploaded_file($image_tmp, $image_path);
        $majorQuery = "SELECT id FROM major WHERE name = '$majorName'";
        $majorResult = mysqli_query($conn, $majorQuery);
        $majorRow = mysqli_fetch_assoc($majorResult);
        $majorID = $majorRow['id'];
        $data = array(
            "name" => $name,
            "bio" => $docBio,
            "image" => $image_name,
            "major_id" => $majorID,
        );
        $result=$connection->create('doctors',$data);
        if ($result !== false) {
            // Data insertion was successful
            echo "Data inserted successfully.";

        } else {
            // Data insertion failed
            echo "Failed to insert data.";
        }
    }
     else {
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
                <h2>Add Doctor</h2>
                <input type="file" name="docImg" id="">
                <div class="card-body d-flex flex-column gap-1 justify-content-center">
                    <input type="text" name="docName" id="" placeholder="Add Doctor Name"><br>
                    <?php
                        $majorQuery = "SELECT name FROM major";
                        $majorResult = mysqli_query($conn, $majorQuery);
                        ?>
                        <label for="">Choose Major</label>
                        <select name="majorName" id="">
                            <?php while ($majorRow = mysqli_fetch_assoc($majorResult)) : ?>
                                <option value="<?php echo $majorRow['name']; ?>"><?php echo $majorRow['name']; ?></option>
                            <?php endwhile; ?>
                        </select>    <br>
                        <input type="text" name="docBio" id="" placeholder="Add Bio"><br>
                    <input type="submit" name="Add" value="Add" class="btn btn-primary" id="">
                </div>
            </div>
        </form>
        <br>
            <div class="majors-grid">
            <?php
        // Iterate over the majors data and generate HTML code for each major
        foreach ($doctors as $doctor) {
            // Extract the relevant data from the $major array
            $image = $doctor['image'];
            $name = $doctor['name'];
            $id=$doctor['id']

            // Generate the dynamic HTML code for each major
            ?>
            <div class="card p-2" style="width: 18rem;">
                <img src="../uploadsdoc/<?=$image?><?=  "" ?>" class="card-img-top rounded-circle card-image-circle" alt="major">
                <div class="card-body d-flex flex-column gap-1 justify-content-center">
                    <h4 class="card-title fw-bold text-center"><?= $name ?></h4>
                    <a href="editDoctor.php?id=<?=$id?>" class="btn btn-outline-primary card-button">Edit</a>
                    <a href="deleteDoctor.php?id=<?=$id?>" class="btn btn-outline-danger card-button">Delete</a>
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
