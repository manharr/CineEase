<?php
include 'header.php';
include 'db.php';

// Check if 'show_id' and 'movie_id' are set in the URL
if (isset($_GET['show_id']) && isset($_GET['movie_id'])) {
    $show_id = $_GET['show_id'];
    $movie_id = $_GET['movie_id'];

    // Delete bookings associated with the showtime
    $deleteBookingsQuery = "DELETE FROM bookings WHERE showtime_id = ?";
    $stmtBookings = mysqli_prepare($con, $deleteBookingsQuery);
    mysqli_stmt_bind_param($stmtBookings, 'i', $show_id);
    mysqli_stmt_execute($stmtBookings);

    // Delete seats associated with the showtime
    $deleteSeatsQuery = "DELETE FROM seats WHERE show_time_id = ?";
    $stmtSeats = mysqli_prepare($con, $deleteSeatsQuery);
    mysqli_stmt_bind_param($stmtSeats, 'i', $show_id);
    mysqli_stmt_execute($stmtSeats);

    // Now delete the showtime
    $query = "DELETE FROM show_timings WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $show_id);
    $run = mysqli_stmt_execute($stmt);

    if ($run) {
        echo "<script>alert('Showtime successfully deleted.'); window.location.href='viewshow.php?id=" . htmlspecialchars($movie_id) . "';</script>";
    } else {
        echo "<script>alert('There was a problem while deleting the showtime.'); window.location.href='viewshow.php?id=" . htmlspecialchars($movie_id) . "';</script>";
    }
} else {
    echo "<div class='container'><h3>Invalid request.</h3></div>";
}
?>
