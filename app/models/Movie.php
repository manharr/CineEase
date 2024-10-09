<?php
class Movie {
    public static function getNowShowing() {
        global $con;
        $today = date('Y-m-d'); 

        $updateQuery = "UPDATE movie 
                        SET coming_soon = 0 
                        WHERE coming_soon = 1 
                        AND (sdate <= '$today' OR status = 'Advance Booking')";

       
        if (!mysqli_query($con, $updateQuery)) {
            
            die('Error updating coming_soon status: ' . mysqli_error($con));
        }

        
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
        $today = date('Y-m-d'); 

        
        $query = "SELECT * FROM movie 
                  WHERE coming_soon = 1 
                  AND sdate > '$today'
                  AND status != 'Advance Booking'"; 
        
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
