<?php

class SeatSelectionModel
{
    public static function getDecodedMovieId($encodedId)
    {
        global $con;
        $decodedValue = base64_decode(urldecode($encodedId));
        if ($decodedValue === false) {
            return null;
        }
        $id = round((($decodedValue * 956783) / 54321) / 123456789);
        return mysqli_real_escape_string($con, $id);
    }

    public static function initializeSeats($id, $showTimeId, $date)
    {
        global $con;
        $rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
        $numbers = range(1, 11);

        foreach ($rows as $row) {
            foreach ($numbers as $number) {
                $seatId = $row . $number;
                $seatRow = $row;
                $seatNumber = $number;

                // Check if seat already exists
                $checkQuery = "SELECT COUNT(*) as count FROM seats 
                               WHERE movie_id = '$id' 
                               AND show_time_id = '$showTimeId' 
                               AND seat_row = '$seatRow' 
                               AND seat_number = '$seatNumber' 
                               AND booking_date = '$date'";
                $checkResult = mysqli_query($con, $checkQuery);
                $count = mysqli_fetch_assoc($checkResult)['count'];

                if ($count == 0) {
                    $seatType = in_array($row, ['A', 'B', 'C', 'D', 'E','F']) ? 'gold' : 'platinum';
                    $insertQuery = "INSERT INTO seats (movie_id, show_time_id, seat_row, seat_number, status, seat_type, booking_date) 
                                    VALUES ('$id', '$showTimeId', '$seatRow', '$seatNumber', 'available', '$seatType', '$date')";
                    mysqli_query($con, $insertQuery);
                }
            }
        }
    }

    
    public static function getMovieDetails($id)
    {
        global $con;
        $query = "SELECT * FROM movie WHERE id = $id";
        $result = mysqli_query($con, $query);
        return mysqli_fetch_assoc($result);
    }

    public static function getShowTimings($id, $showTimeId)
    {
        global $con;
        $timingsQuery = "SELECT * FROM show_timings WHERE movie_id = $id AND id = $showTimeId";
        $timingsRun = mysqli_query($con, $timingsQuery);
        return mysqli_fetch_assoc($timingsRun);
    }
    

    public static function getTheatre($theatreId)
    {
        global $con;
        $theatreQuery = "SELECT * FROM theatre WHERE id = $theatreId";
        $theatreRun = mysqli_query($con, $theatreQuery);
        return mysqli_fetch_assoc($theatreRun);
    }

    public static function getSeats($id, $showTimeId, $date)
    {
        global $con;
        $seatsQuery = "SELECT * FROM seats WHERE movie_id = $id AND show_time_id = $showTimeId AND booking_date = '$date'";
        $seatsRun = mysqli_query($con, $seatsQuery);
        $seats = [];
        while ($seatRow = mysqli_fetch_assoc($seatsRun)) {
            $seats[$seatRow['seat_row'] . $seatRow['seat_number']] = $seatRow['status'];
        }
        return $seats;
    }
}
