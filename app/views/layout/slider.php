
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/movie-booking/public/css/slider.css">
    <script src="/movie-booking/public/js/slider.js" ></script>
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>CineEase</title>
</head>
<body>
<div class="slider-container">
    <div class="slider">
        <div class="slides">
            <?php 
            if (!empty($slides)) {
                foreach ($slides as $slide) {
                    $imgSrc = '/movie-booking/app/adminn/' . htmlspecialchars($slide['imgz']);
                    
            ?>
                <div class="slide"><img src="<?php echo $imgSrc; ?>" alt="Slider Image"></div> 
            <?php
                }
            } else {
                echo "<p>No images found!</p>";
            }
            ?>
        </div>

        <div class="arrows">
            <span class="prev" onclick="moveSlide(-1)">&#10094;</span>
            <span class="next" onclick="moveSlide(1)">&#10095;</span>
        </div>
        <div class="dots">
            <?php 
            $total = count($slides);
            for ($i = 1; $i <= $total; $i++) {
                echo '<span class="dot"></span>';
            }
            ?>
        </div>
    </div>
</div>


</body>
</html>
