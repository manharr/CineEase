<?php
require_once '../config/db.php'; // Include database connection
require_once '../app/models/Slider.php'; // Include Slider model

class SliderController {
    public function index() {
        $slides = Slider::getAll(); // Fetch slides from the database
        require '../app/views/layout/slider.php'; // Include the slider view
    }
}
?>
