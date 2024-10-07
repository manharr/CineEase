<?php
class HomeController {
    public function index() {
        require_once __DIR__ . '/../../config/db.php'; 
        require_once __DIR__ . '/../models/Slider.php';
        require_once __DIR__ . '/../models/Movie.php';

        global $con; 

        $theatres = Theatre::getAll(); 

        $slides = Slider::getSlides();

        $nowShowingMovies = Movie::getNowShowing();

        $comingSoonMovies = Movie::getComingSoon();

        include __DIR__ . '/../views/home/index.php';
    }
}
