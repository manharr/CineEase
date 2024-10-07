<?php 
include 'header.php';
include 'db.php';

if (isset($_POST['submit'])) {
    // Retrieve form data and escape special characters
    $mv_name = mysqli_real_escape_string($con, $_POST['mv_name']);
    $mv_m_desc = mysqli_real_escape_string($con, $_POST['mv_m_desc']);
    $mv_m_tag = mysqli_real_escape_string($con, $_POST['mv_m_tag']);
    $mv_link1 = mysqli_real_escape_string($con, $_POST['mv_link1']);
    $mv_certificate = mysqli_real_escape_string($con, $_POST['certificate']); 
    $mv_status = mysqli_real_escape_string($con, $_POST['status']);
    $mv_runtime = mysqli_real_escape_string($con, $_POST['runtime']);
    $mv_lang = mysqli_real_escape_string($con, $_POST['mv_lang']);
    $cat_id = mysqli_real_escape_string($con, $_POST['cat_id']);
    $genre_id = mysqli_real_escape_string($con, $_POST['genre_id']);
    $mv_desc = mysqli_real_escape_string($con, $_POST['mv_desc']);
    $mv_cast = mysqli_real_escape_string($con, $_POST['mv_cast']); // Escape cast
    $mv_director = mysqli_real_escape_string($con, $_POST['mv_director']);
    $mv_date = date('Y-m-d', strtotime($_POST['mv_date']));
    $mv_edate = date('Y-m-d', strtotime($_POST['mv_edate']));
    $target = "../thumb/".basename($_FILES['img']['name']);
    $img = mysqli_real_escape_string($con, $_FILES['img']['name']);
    $coming_soon = isset($_POST['coming_soon']) ? mysqli_real_escape_string($con, $_POST['coming_soon']) : '0'; 
    // $show_timings = mysqli_real_escape_string($con, $_POST['show_timings']); 

    // $formats = isset($_POST['formats']) ? $_POST['formats'] : [];
    // $formats_str = implode(',', $formats); 

    $query2 = "SELECT mv_name FROM movie WHERE mv_name = '$mv_name'";
    $movieresult = mysqli_query($con, $query2);

    if (mysqli_num_rows($movieresult) > 0) {
        echo "<script>alert('A movie with this name already exists. Please choose a different name.');window.location.href='addmovie.php';</script>";
    }


    else {

    $query = "INSERT INTO movie (cat_id, genre_id, mv_name, mv_des, mv_tag, link1, certificate, img, sdate, edate, lang, director, meta_description, runtime, status, coming_soon, cast) 
              VALUES ('$cat_id', '$genre_id', '$mv_name', '$mv_desc', '$mv_m_tag', '$mv_link1', '$mv_certificate', '$img', '$mv_date', '$mv_edate', '$mv_lang', '$mv_director', '$mv_m_desc', '$mv_runtime', '$mv_status', '$coming_soon', '$mv_cast')";

    // Debugging: Print the query to check for syntax errors
    // echo $query; exit;
    

    
    // Execute the query
    $run = mysqli_query($con, $query);
    if ($run) {
        // Get the last inserted movie ID
        // $movie_id = mysqli_insert_id($con);

        // // Insert show timings data
        // if (isset($_POST['showLang'])) {
        //     for ($i = 0; $i < count($_POST['showLang']); $i++) {
        //         $movie_language = mysqli_real_escape_string($con, $_POST['showLang'][$i]);
        //         $movie_format = mysqli_real_escape_string($con, $_POST['showFormat'][$i]);
        //         $movie_timings = mysqli_real_escape_string($con, $_POST['showTimings'][$i]);
        //         $gold_price = mysqli_real_escape_string($con, $_POST['goldPrice'][$i]);
        //         $platinum_price = mysqli_real_escape_string($con, $_POST['platinumPrice'][$i]);

        //         $showTimingsQuery = "INSERT INTO show_timings (movie_id, movie_language, movie_format, movie_timings, gold_price, platinum_price) 
        //                              VALUES ('$movie_id', '$movie_language', '$movie_format', '$movie_timings', '$gold_price', '$platinum_price')";
                
        //         mysqli_query($con, $showTimingsQuery);
        //     }
        // }

        // Check if the file upload was successful
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target)) {
            echo "<script>alert('Movie Successfully Added.. :)');window.location.href='movielist.php';</script>";
        } else {
            echo "<script>alert('Movie data added, but file upload failed!!.. :(');window.location.href='addmovie.php';</script>";
        }
    } else {
        echo "<script>alert('Something Went Wrong!!.. :(');window.location.href='addmovie.php';</script>";
        }
    }
}
?>