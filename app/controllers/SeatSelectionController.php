<?php
require_once '../app/models/SeatSelectionModel.php'; 
require_once '../app/models/MovieDesc.php'; 

class SeatSelectionController
{
    public function seatselection()
    {
        global $con;
        $encodedId = isset($_GET['movie_id']) ? $_GET['movie_id'] : '';
        $showTimeId = isset($_GET['show_time_id']) ? $_GET['show_time_id'] : '';
        $date = isset($_GET['date']) ? $_GET['date'] : '';
        $selectedDate = date('Y-m-d', strtotime($date));
        $currentDate = date('Y-m-d'); 
        $dateRange = MovieDesc::getDateRange($encodedId);

        $today = date('Y-m-d'); 

        $startDate = $dateRange['sdate'];
        $endDate = $dateRange['edate'];
        
        if ($selectedDate < $today) {
            // Show alert for past date
            echo "<script>alert('Cannot book for past dates.');</script>";
            return;
        }
        
        if ($selectedDate > $endDate || $selectedDate < $startDate) {
            echo "<script>alert('Cannot book for dates outside of the valid range.');</script>";
            return;
        }
        $id = SeatSelectionModel::getDecodedMovieId($encodedId);
        if ($id) {
            SeatSelectionModel::initializeSeats($id, $showTimeId, $date);

            $movie = SeatSelectionModel::getMovieDetails($id);
            $timings = SeatSelectionModel::getShowTimings($id, $showTimeId);
            $theatre = SeatSelectionModel::getTheatre($timings['theatre_id']);
            $goldPrice = $timings['gold_price'];
            $platinumPrice = $timings['platinum_price'];

            $seats = SeatSelectionModel::getSeats($id, $showTimeId, $date);

            // Pass PHP variables to JavaScript
            echo "<script>
                window.goldPrice = " . json_encode($goldPrice) . ";
                window.platinumPrice = " . json_encode($platinumPrice) . ";
                window.seats = " . json_encode($seats) . ";
            </script>";

            require_once '../app/views/seat/seatselection.php';
        } else {
            // Handle the error or invalid movie ID
            echo "Invalid movie ID";
        }
    }
}
