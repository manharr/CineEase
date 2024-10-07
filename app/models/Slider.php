<?php
class Slider {
    public static function getSlides() {
        global $con;
        $query = "SELECT * FROM slider";
        $result = mysqli_query($con, $query);
        $slides = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $slides[] = $row;
            }
        }
        return $slides;
    }
}
