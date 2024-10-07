<?php
require_once '../app/models/BookingsModel.php'; // Include BookingsModel
class BookingsController {
    public function index() {
        // Check if the Firebase UID is passed via POST
        if (isset($_POST['firebase_uid'])) {
            $firebase_uid = $_POST['firebase_uid'];

            // Fetch the user's bookings using the model
            $bookings = BookingsModel::getUserBookings($firebase_uid);

            // Pass the bookings to the view to display them
            require_once '../app/views/layout/bookings.php'; // Load the view that will display bookings
        } else {
            // Redirect to an error page or show a message if Firebase UID is missing
            header("Location: /error");
        }
    }
}


?>