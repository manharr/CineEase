<?php

class SeatBookingModel
{
    public static function checkSeatExists($movieId, $showTimeId, $seatRow, $seatNumber, $date)
    {
        global $con;
        $query = "SELECT status FROM seats WHERE movie_id = ? AND show_time_id = ? AND seat_row = ? AND seat_number = ? AND booking_date = ? FOR UPDATE";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iisss', $movieId, $showTimeId, $seatRow, $seatNumber, $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['status'] === 'booked'; 
    }

    public static function getSeatId($movieId, $showTimeId, $seatRow, $seatNumber, $date)
    {
        global $con;
        $query = "SELECT id FROM seats WHERE movie_id = ? AND show_time_id = ? AND seat_row = ? AND seat_number = ? AND booking_date = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('iisss', $movieId, $showTimeId, $seatRow, $seatNumber, $date);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['id'] ?? null; 
    }

    public static function bookSeat($movieId, $showTimeId, $seatRow, $seatNumber, $firebaseUid, $date)
    {
        global $con;
        // Start transaction
        $con->begin_transaction();
        
        try {
            if (self::checkSeatExists($movieId, $showTimeId, $seatRow, $seatNumber, $date)) {
                return false;
            }

            $query = "UPDATE seats SET status = 'booked', user_id = ? WHERE movie_id = ? AND show_time_id = ? AND seat_row = ? AND seat_number = ? AND booking_date = ?";
            $stmt = $con->prepare($query);
            $stmt->bind_param('siisss', $firebaseUid, $movieId, $showTimeId, $seatRow, $seatNumber, $date);
            $stmt->execute();
            
            // Commit transaction if booking succeeds
            $con->commit();
            return true;

        } catch (Exception $e) {
            // Rollback transaction in case of an error
            $con->rollback();
            return false;
        }
    }


    public static function insertBooking($firebaseUid, $movieId, $showTimeId, $screen, $movieDate, $totalPrice, $seatIds, $theater)
    {
        global $con;
        $bookingUid = substr(bin2hex(random_bytes(4)), 0, 8); 
        $seatIdsStr = implode(',', $seatIds);
        $query = "INSERT INTO bookings (firebase_uid, movie_id, showtime_id, screen, movie_date, booking_date, total_price, seats, theater, booking_uid) VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
        $stmt = $con->prepare($query);
        $stmt->bind_param('siissssss', $firebaseUid, $movieId, $showTimeId, $screen, $movieDate, $totalPrice, $seatIdsStr, $theater, $bookingUid);
        return $stmt->execute();
    }
    public static function isBookingSessionCompleted($bookingSessionId)
    {
        global $con;
        $query = "SELECT COUNT(*) as count FROM bookings WHERE booking_session_id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('s', $bookingSessionId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0;
    }
}
?>
