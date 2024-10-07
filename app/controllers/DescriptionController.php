<?php
require_once '../app/models/MovieDesc.php'; 
date_default_timezone_set('Asia/Kolkata'); 

class DescriptionController {
    private $omdb_api_key = 'bb0aebb3';

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
            $ratings = MovieDesc::fetchMovieRatings($movie['mv_name'], $this->omdb_api_key);

            $actors = explode(',', $movie['cast']);
            $actor_data = [];
            foreach ($actors as $actor) {
                $actor_data[] = [
                    'name' => trim($actor),
                    'image' => $this->fetchActorImage(trim($actor))
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

    private function fetchActorImage($actor_name) {
        $actor_name_encoded = urlencode($actor_name);
        $tmdb_api_key = 'dcfa7bf7b0d21c92cf188ed51682479e';
        $tmdb_url = "https://api.themoviedb.org/3/search/person?api_key=$tmdb_api_key&query=$actor_name_encoded";

        $tmdb_response = file_get_contents($tmdb_url);
        $tmdb_data = json_decode($tmdb_response, true);

        if (!empty($tmdb_data['results']) && !empty($tmdb_data['results'][0]['profile_path'])) {
            return 'https://image.tmdb.org/t/p/w200' . $tmdb_data['results'][0]['profile_path'];
        } else {
            return 'default_actor_image.jpg'; 
        }
    }
}
?>
