<?php 
include '../../../config/db.php';

// Check if deleteid is set
if (isset($_GET['deleteid'])) {
    // Decode the deleteid
    $encoded_value = $_GET['deleteid'];
    $decoded_value = base64_decode(urldecode($encoded_value));

    // Reverse the calculation to get the original ID
    $id = round((($decoded_value * 956783) / 54321) / 123456789);
    $id = intval(mysqli_real_escape_string($con, $id));

    // Fetch the booking details including seats
    $query = "SELECT * FROM bookings WHERE id = $id";
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

        // Mark the booking as 'cancelled'
        $deleteQuery = "UPDATE bookings SET status = 'cancelled' where id = $id";
        mysqli_query($con, $deleteQuery);
        
        // Delete the booking record
        $deleteQuery = "DELETE FROM bookings WHERE id = $id";
        mysqli_query($con, $deleteQuery);
        
        // Redirect back to the booking list with a success message
        header('Location: bookings.php');
        exit;
    } else {
        echo "Error fetching booking details.";
    }
} else {
    echo "Invalid request.";
}
?>
