<?php
include 'db.php';

if (isset($_POST['movie_id']) && isset($_POST['theatre_id'])) {
    $movieId = $_POST['movie_id'];
    $theatreId = $_POST['theatre_id'];

    // Query to get the start and end dates for the selected movie
    $query = "SELECT sdate, edate FROM movie WHERE id = '$movieId'";
    $result = mysqli_query($con, $query);
    $dates = mysqli_fetch_assoc($result);

    $startDate = new DateTime($dates['sdate']);
    $endDate = new DateTime($dates['edate']);
    $dateOptions = '';

    // Generate date options between sdate and edate
    while ($startDate <= $endDate) {
        $dateOptions .= '<option value="' . $startDate->format('Y-m-d') . '">' . $startDate->format('Y-m-d') . '</option>';
        $startDate->modify('+1 day'); // Move to the next day
    }

    echo $dateOptions; // Return the date options
}
?>
