<?php
require_once '../config/db.php'; // Include database connection
require_once '../app/models/Movie.php';

class MovieController {
    public function index() {
        $movies = Movie::getAll();
        echo '<pre>'; print_r($movies); echo '</pre>'; // Debugging output to check movies
        require '../app/views/movie/index.php';
    }

    public function details($id) {
        $movie = Movie::getById($id);
        require '../app/views/movie/details.php';
    }
}
?>
