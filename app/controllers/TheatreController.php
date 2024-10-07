<?php
require_once '../config/db.php'; // Include database connection
require_once '../app/models/Theatre.php';

class TheatreController {
    public function index() {
        $theatres = Theatre::getAll();
        echo '<pre>'; print_r($theatres); echo '</pre>'; // Debugging output to check theatres
        require '../app/views/theatre/index.php';
        
    }

    public function details($id) {
        $theatre = Theatre::getById($id);
        require '../app/views/theatre/details.php';
    }
}
?>
