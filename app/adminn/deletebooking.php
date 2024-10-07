<?php 
include 'db.php'; // Make sure to include your database connection file

// Check if deleteid is set
if (isset($_GET['deleteid'])) {
    $bookingId = intval($_GET['deleteid']);

    // Fetch the booking details including seats
    $query = "SELECT * FROM bookings WHERE id = $bookingId";
    $result = mysqli_query($con, $query);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        
        // Get booking details
        $seats = $row['seats'];
        $firebase_uid = $row['firebase_uid'];
        $movie_id = $row['movie_id'];
        $showtime_id = $row['showtime_id'];
        $screen = $row['screen'];
        $movie_date = $row['movie_date'];
        $booking_date = $row['booking_date'];
        $theater = $row['theater'];
        $total_price = $row['total_price'];

        // Insert the booking into canbookings
        $insertQuery = "INSERT INTO canbookings (firebase_uid, movie_id, showtime_id, screen, movie_date, booking_date, seats, theater, total_price) 
                        VALUES ('$firebase_uid', $movie_id, $showtime_id, '$screen', '$movie_date', '$booking_date', '$seats', '$theater', $total_price)";
        mysqli_query($con, $insertQuery);

        // Split the seats into an array
        $seatIds = explode(',', $seats);

        // Update seat statuses to 'available'
        foreach ($seatIds as $seatId) {
            $updateQuery = "UPDATE seats SET status = 'available' WHERE id = " . intval($seatId);
            mysqli_query($con, $updateQuery);
        }

        // Delete the booking record
        $deleteQuery = "DELETE FROM bookings WHERE id = $bookingId";
        mysqli_query($con, $deleteQuery);
        
        // Redirect back to the booking list with a success message
        header('Location: bookinglist.php?message=Booking%20Cancelled%20Successfully');
        exit;
    } else {
        echo "Error fetching booking details.";
    }
} else {
    echo "Invalid request.";
}
?>
