<?php
class MovieDesc {
    private static function decryptId($encoded_id) {
        // Decrypt the ID using the provided logic
        $decoded_value = base64_decode(urldecode($encoded_id));

        if ($decoded_value !== false) {
            // Ensure ID is an integer
            return round((($decoded_value * 956783) / 54321) / 123456789);
        } else {
            return null; // Return null if decryption fails
        }
    }

    public static function getTheatres() {
        global $con;
        $query = "SELECT * FROM theatre";
        $result = mysqli_query($con, $query);
        $theatres = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $theatres[] = $row;
        }
        return $theatres;
    }

    public static function getShowTimings($theatre_id, $encoded_id) {
        global $con;

        // Decrypt movie ID
        $movie_id = self::decryptId($encoded_id);
        if ($movie_id === null) {
            error_log("Invalid encoded movie ID: $encoded_id");
            return [];
        }

        // Query to select show timings based on theatre_id and movie_id
        $query = "SELECT * FROM show_timings WHERE theatre_id = ? AND movie_id = ? ORDER BY movie_language, STR_TO_DATE(movie_timings, '%H:%i')";

        if ($stmt = $con->prepare($query)) {
            $stmt->bind_param("ii", $theatre_id, $movie_id); // Bind theatre_id and movie_id as integers
            $stmt->execute();
            $result = $stmt->get_result();

            if ($result === false) {
                error_log("Query failed: " . $con->error);
                return [];
            }

            $languages = [];

            // Fetch and process show timings
            while ($showTiming = $result->fetch_assoc()) {
                // Ensure each show timing has the necessary fields
                if (isset($showTiming['movie_id'], $showTiming['movie_language'], $showTiming['movie_format'], $showTiming['movie_timings'], $showTiming['id'])) {
                    $movie_id = htmlspecialchars($showTiming['movie_id']);
                    $language = htmlspecialchars($showTiming['movie_language']);
                    $format = htmlspecialchars($showTiming['movie_format']);
                    $timings = htmlspecialchars($showTiming['movie_timings']);
                    $show_time_id = htmlspecialchars($showTiming['id']);

                    $timingArray = array_map('trim', explode(',', $timings));

                    if (!isset($languages[$language])) {
                        $languages[$language] = [];
                    }

                    foreach ($timingArray as $timing) {
                        $languages[$language][] = [
                            'time' => $timing,
                            'format' => $format,
                            'movie_id' => $movie_id,
                            'show_time_id' => $show_time_id
                        ];
                    }
                } else {
                    error_log("Missing data in show timing: " . print_r($showTiming, true));
                }
            }

            $stmt->close();

            return $languages;
        } else {
            error_log("Prepare failed: " . $con->error);
            return [];
        }
    }

    public static function getMovieById($encoded_id) {
        global $con;
        $id = self::decryptId($encoded_id);

        if ($id === null) {
            return null; 
        }

        $id = mysqli_real_escape_string($con, $id);
        $query = "SELECT * FROM movie WHERE id = $id";
        $result = mysqli_query($con, $query);
        return mysqli_fetch_assoc($result);
    }

    public static function getDateRange($encoded_id) {
        global $con;
        $id = self::decryptId($encoded_id);
    
        if ($id === null) {
            return null; 
        }
    
        $id = mysqli_real_escape_string($con, $id);
        $query = "SELECT sdate, edate, status FROM movie WHERE id = $id";
        $result = mysqli_query($con, $query);
        return mysqli_fetch_assoc($result);
    }
    
    public static function getAllDates($startDate, $endDate, $status) {
        $dates = [];
        $startDateTimestamp = strtotime($startDate); // Start date of movie availability
        $endDateTimestamp = strtotime($endDate);     
        $today = strtotime(date('Y-m-d'));           
    
        if ($status === 'Advance Booking') {
            $startingDate = $startDateTimestamp;
        } else {
            $startingDate = max($today, $startDateTimestamp);
        }
    
        // Set the limit to 4 days from the starting date
        $finalDate = min(strtotime('+4 days', $startingDate), $endDateTimestamp);
    
        // Generate the list of dates from startingDate to finalDate (next 4 days)
        while ($startingDate <= $finalDate) {
            $dates[] = date('Y-m-d', $startingDate);
            $startingDate = strtotime('+1 day', $startingDate);
        }
    
        // Default selected date logic
        $defaultSelectedDate = (strtotime(date('Y-m-d')) >= $startDateTimestamp && strtotime(date('Y-m-d')) <= $finalDate)
            ? date('Y-m-d')
            : $dates[0];
    
        sort($dates); // Sort the dates in ascending order
    
        return [$dates, $defaultSelectedDate]; // Return both dates and default selected date
    }
    

    public static function fetchMovieRatings($movie_name, $omdb_api_key) {
        $movie_name_encoded = urlencode($movie_name);
        $omdb_url = "http://www.omdbapi.com/?t=$movie_name_encoded&apikey=$omdb_api_key";
        $omdb_response = file_get_contents($omdb_url);
        $omdb_data = json_decode($omdb_response, true);

        if ($omdb_data && $omdb_data['Response'] == 'True') {
            return [
                'imdb' => $omdb_data['imdbRating'] ?? 'N/A',
                'rotten_tomatoes' => $omdb_data['Ratings'][1]['Value'] ?? 'N/A',
            ];
        } else {
            return [
                'imdb' => 'N/A',
                'rotten_tomatoes' => 'N/A',
            ];
        }
    }
}
?>
