<?php 
include 'db.php'; // Make sure to include your database connection file

// Check if deleteid is set
if (isset($_GET['deleteid'])) {
    $bookingId = intval($_GET['deleteid']);

    // Delete the cancelled booking record
    $deleteQuery = "DELETE FROM canbookings WHERE id = $bookingId";
    if (mysqli_query($con, $deleteQuery)) {
        // Redirect back to the cancelled bookings list with a success message
        header('Location: canbookings.php?message=Booking%20Deleted%20Successfully');
        exit;
    } else {
        echo "Error deleting booking.";
    }
} else {
    echo "Invalid request.";
}
?>
