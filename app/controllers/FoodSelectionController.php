<?php
require_once '../app/models/FoodSelectionModel.php';
require_once '../app/models/SeatSelectionModel.php'; 

class FoodSelectionController
{
    public function food()
    {
        $firebaseUid = isset($_POST['firebase_uid']) ? $_POST['firebase_uid'] : '';
        $movieId = isset($_POST['movie_id']) ? $_POST['movie_id'] : '';
        $showTimeId = isset($_POST['show_time_id']) ? $_POST['show_time_id'] : '';
        $selectedSeats = isset($_POST['selected_seats']) ? $_POST['selected_seats'] : '';
        $date = isset($_POST['date']) ? $_POST['date'] : '';


        // Fetch movie details, timings, and theatre information
        $movie = SeatSelectionModel::getMovieDetails($movieId);
        $timings = SeatSelectionModel::getShowTimings($movieId, $showTimeId);
        $theatre = SeatSelectionModel::getTheatre($timings['theatre_id']);

        // Calculate selected seats count for the view
        $selectedSeatsArray = explode(',', $selectedSeats);
        $selectedSeatsCount = count($selectedSeatsArray);
        $selectedSeatsDisplay = implode(', ', $selectedSeatsArray);

        // Calculate the total price for selected seats
        $goldPrice = $timings['gold_price'];
        $platinumPrice = $timings['platinum_price'];
        $totalPrice = 0;
        foreach ($selectedSeatsArray as $seat) {
            $rowName = substr($seat, 0, 1);
            if (in_array($rowName, ['A', 'B', 'C', 'D', 'E','F'])) {
                $totalPrice += $goldPrice;
            } else if (in_array($rowName, ['G','H'])) {
                $totalPrice += $platinumPrice;
            }
        }


        require_once '../app/views/seat/foodselection.php';
    }
}