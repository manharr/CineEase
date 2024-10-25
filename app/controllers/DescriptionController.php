<?php
require_once '../app/models/MovieDesc.php'; 
date_default_timezone_set('Asia/Kolkata'); 

class DescriptionController {

    public function description($encoded_id) {
        $movie = MovieDesc::getMovieById($encoded_id);
        if ($movie) {
            $dateRange = MovieDesc::getDateRange($encoded_id);
            if (!$dateRange) {
                header('Location: /error'); 
                exit();
            }

            $status = $movie['status'];

            // Fetch all dates including the selected date
            list($dates, $defaultSelectedDate) = MovieDesc::getAllDates($dateRange['sdate'], $dateRange['edate'], $status);

            $actors = explode(',', $movie['cast']);
            $actor_data = [];
            foreach ($actors as $actor) {
                $actor_data[] = [
                    'name' => trim($actor),
                ];
            }

            // Fetch theatres and show timings
            $theatres = MovieDesc::getTheatres();
            $selectedDate = isset($_GET['date']) ? $_GET['date'] : $defaultSelectedDate;

            // Verify date range
            if ($selectedDate < $dateRange['sdate'] || $selectedDate > $dateRange['edate']) {
                die('Selected date is out of range.');
            }

            $theatreData = [];
            foreach ($theatres as $theatre) {
                // Fetch show timings from the model for the current theatre
                $showTimings = MovieDesc::getShowTimings($theatre['id'], $encoded_id);

                if (!empty($showTimings)) {
                    $theatreData[] = [
                        'theatre' => $theatre,
                        'showTimings' => $showTimings
                    ];
                } else {
                    error_log("No show timings found for theatre ID: " . $theatre['id']);
                    $theatreData[] = [
                        'theatre' => $theatre,
                        'showTimings' => []
                    ];
                }
            }

            require '../app/views/movie/description.php';
        } else {
            header('Location: /error'); 
            exit();
        }
    }

    
}
?>
