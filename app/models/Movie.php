<?php
class Movie {
    public static function getNowShowing() {
        global $con;
        $today = date('Y-m-d'); // Get today's date

        $updateQuery = "UPDATE movie 
                        SET coming_soon = 0 
                        WHERE coming_soon = 1 
                        AND (sdate <= '$today' OR status = 'Advance Booking')";

        // Check if the update query executed successfully
        if (!mysqli_query($con, $updateQuery)) {
            // Output any error from the database
            die('Error updating coming_soon status: ' . mysqli_error($con));
        }

        // Fetch all 'Now Showing' movies
        $query = "SELECT * FROM movie 
                  WHERE coming_soon = 0 
                  AND (sdate <= '$today' OR status = 'Advance Booking')  /* Show movies in Advance Booking */
                  AND edate >= '$today'  /* Exclude movies where edate is in the past */
                  ORDER BY 
                    CASE 
                        WHEN status = 'Advance Booking' THEN 2
                        WHEN status = 'Promoted' THEN 1
                        WHEN status = 'Discounted' THEN 3
                        WHEN status = 'New Release' THEN 4
                        ELSE 5
                    END";
        
        $result = mysqli_query($con, $query);
        $movies = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $movies[] = $row;
            }
        }

        return $movies;
    }

    public static function getComingSoon() {
        global $con;
        $today = date('Y-m-d'); // Get today's date

        // Fetch movies that are still 'Coming Soon' with sdate in the future and not 'Advance Booking'
        $query = "SELECT * FROM movie 
                  WHERE coming_soon = 1 
                  AND sdate > '$today'
                  AND status != 'Advance Booking'";  // Exclude movies with 'Advance Booking' from Coming Soon
        
        $result = mysqli_query($con, $query);
        $movies = [];
        if ($result) {
            while ($row = mysqli_fetch_assoc($result)) {
                $movies[] = $row;
            }
        }

        return $movies;
    }
}
?>
