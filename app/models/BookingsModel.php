<?php
class BookingsModel {
    public static function getUserBookings($firebase_uid) {
        global $con; 

        $firebase_uid = mysqli_real_escape_string($con, $firebase_uid);
        $query = "SELECT * FROM bookings WHERE firebase_uid = '$firebase_uid'";
        $result = mysqli_query($con, $query);

        $bookings = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $bookings[] = $row;
            }
        }
        return $bookings;
    }
}

?>