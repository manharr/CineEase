<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <link rel="stylesheet" href="/movie-booking/public/css/nowshowing.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>CineEase</title>
</head>


<div class="exact-banner-container">
    <div class="exact-left-arrow"></div>
    <div class="exact-banner-text">COMING SOON</div>
    <div class="exact-right-arrow"></div>
</div>
<div class="movie-container">
    <?php
    if (!empty($comingSoonMovies)) {
        foreach ($comingSoonMovies as $movie) {
            ?>
            <div class="movie-card">
                <img src="/movie-booking/app/thumb/<?php echo $movie['img']; ?>" alt="<?php echo $movie['mv_name']; ?>">
                <div class="movie-info">
                    <h2><?php echo $movie['mv_name']; ?></h2>
                    <div class="movie-tags">
                        <span class="tag"><?php echo $movie['certificate']; ?></span>
                        <span class="tag"><?php echo $movie['lang']; ?></span>
                    </div>
                    <div class="movie-tags2">
                        <span class="tag"><?php echo $movie['mv_tag']; ?></span>
                    </div>
                </div>
                
                <div class="over">
                    <!-- <a href="<?php echo $trailerLink ?> " target="_blank"> <button class="over-btn2">Watch Trailer</button> </a> -->
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
