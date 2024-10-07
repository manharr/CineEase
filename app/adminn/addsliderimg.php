<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

if(isset($_POST['submit'])) {
    $img = $_FILES['img']['name'];
    $target_dir = "uploads/"; // Directory where you want to save the uploaded file
    $target_file = $target_dir . basename($img);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    $check = getimagesize($_FILES['img']['tmp_name']);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES['img']['size'] > 500000) { // 500KB
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    // Check if uploads directory exists
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    // Check if $uploadOk is set to 0 by an error
     else {
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            // File successfully uploaded, now insert it into the database
            $img = mysqli_real_escape_string($con, $target_file);

            $query = "INSERT INTO slider (imgz) VALUES ('$img')";
            if (mysqli_query($con, $query)) {
                echo "<script>
                        alert('The file has been uploaded.');
                        window.location.href='sliderlist.php';
                      </script>";
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
?>

<div class="container">
    <div class="head">
        <div class="jumbotron">
            <h1 class="display-4">Add Slider Image</h1>
            <p>Upload Movie Banners ONLY</p>
            <hr class="my-4">
            <form action="addsliderimg.php" method="post" enctype="multipart/form-data">
                <div class="custom-file">
                    <input type="file" name="img" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Choose file</label>
                </div>
                <br><br>
                <button class="btn btn-primary btn-lg" name="submit" type="submit">Add Image</button>
            </form>
        </div>
    </div>
</div>
