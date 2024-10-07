<?php
include 'db.php';

if (isset($_POST['movie_id'], $_POST['theatre_id'])) {
    $movie_id = $_POST['movie_id'];
    $theatre_id = $_POST['theatre_id'];

    // Adjust your query to select timings based on the movie_id and theatre_id
    $query = "SELECT id, movie_timings FROM show_timings WHERE movie_id = $movie_id AND theatre_id = $theatre_id";
    $result = mysqli_query($con, $query);

    echo '<option value="">Select Timings</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['id'] . '">' . $row['movie_timings'] . '</option>';
    }
}
?>
