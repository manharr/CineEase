<?php
require_once '../app/models/SeatBookingModel.php';
require_once '../app/models/FoodSelectionModel.php';

class SeatBookingController
{
    public function bookSeats()
    {
        session_start(); // Ensure session is started

        $firebaseUid = isset($_POST['firebase_uid']) ? $_POST['firebase_uid'] : '';
        $movieId = isset($_POST['movie_id']) ? $_POST['movie_id'] : '';
        $showTimeId = isset($_POST['show_time_id']) ? $_POST['show_time_id'] : '';
        $date = isset($_POST['date']) ? $_POST['date'] : '';
        $selectedSeats = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : '';
        $totalPrice = isset($_POST['total_price']) ? (float)$_POST['total_price'] : 0;
        $comboOffers = isset($_POST['food']) ? $_POST['food'] : '';
        $screen = isset($_POST['screen']) ? $_POST['screen'] : '';
        $theater = isset($_POST['theater']) ? $_POST['theater'] : '';

        // Validate input
        if (empty($selectedSeats)) {
            die("No seats selected");
        }

        // Initialize an array to store seat IDs
        $seatIds = [];
        $selectedSeatsArray = explode(',', $selectedSeats);

        // Validate and book seats
        foreach ($selectedSeatsArray as $seat) {
            $seatRow = substr($seat, 0, 1);
            $seatNumber = substr($seat, 1);

            // Try to book the seat
            if (!SeatBookingModel::bookSeat($movieId, $showTimeId, $seatRow, $seatNumber, $firebaseUid, $date)) {
                echo "<script>alert('Seat $seat is already booked.'); window.history.back();</script>";
                exit;
            }

            // Get the seat ID and add it to the seatIds array
            $seatId = SeatBookingModel::getSeatId($movieId, $showTimeId, $seatRow, $seatNumber, $date);
            if ($seatId !== null) {
                $seatIds[] = $seatId;
            } else {
                echo "Error fetching seat ID for seat $seat.";
                exit;
            }
        }

        if (!empty($comboOffers)) {
            FoodSelectionModel::insertFoodSelection($firebaseUid, $movieId, $showTimeId, $date, $comboOffers, 1);
        }

        // Insert booking details into the bookings table
        $bookingResult = SeatBookingModel::insertBooking(
            $firebaseUid, 
            $movieId, 
            $showTimeId, 
            $screen, 
            $date, 
            $totalPrice, 
            $seatIds, 
            $theater
        );

        if ($bookingResult) {
            $_SESSION['booking_confirmed'] = true;
            $_SESSION['booking_session_id'] = uniqid(); 

            
            $razorpayPaymentId = $_POST['razorpay_payment_id'];
            $razorpayOrderId = $_POST['razorpay_order_id'];
            $razorpaySignature = $_POST['razorpay_signature'];
            // Redirect to the bookings confirmation page
            header('Location: index.php?url=confirmation');
            exit;
        } else {
            echo "Error inserting booking details.";
            exit;
        }
    }
}
?>
