<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Start a transaction
    mysqli_begin_transaction($con);
    try {
        // Delete dependent rows first
        $deleteShowTimingsQuery = "DELETE FROM show_timings WHERE movie_id = $id";
        mysqli_query($con, $deleteShowTimingsQuery);

        // Now delete the movie
        $deleteMovieQuery = "DELETE FROM movie WHERE id = $id";
        mysqli_query($con, $deleteMovieQuery);

        // Commit the transaction
        mysqli_commit($con);
        
        echo  "<script> window.location.href='movielist.php';</script>";
    } catch (Exception $e) {
        // Rollback the transaction on error
        mysqli_rollback($con);
        echo "<script>alert('Something went wrong: " . $e->getMessage() . "');window.location.href='movielist.php';</script>";
    }
} else {
    echo  "<script> window.location.href='movielist.php';</script>";
}
?>
