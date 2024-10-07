<!DOCTYPE html>
<html lang="en">

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
    <div class="exact-banner-text">NOW SHOWING</div>
    <div class="exact-right-arrow"></div>
</div>
<div class="movie-container">
    <?php
    if (!empty($nowShowingMovies)) {
        foreach ($nowShowingMovies as $movie) {
            ?>
            <div class="movie-card">
                <?php
                $status = $movie['status'];
                if (!empty($status)) {
                    if ($status == 'Advance Booking') {
                        $badgeClass = 'badge-advanced';
                        $badgeText = 'Advance Booking';
                    } elseif ($status == 'Promoted') {
                        $badgeClass = 'badge-promoted';
                        $badgeText = 'Promoted';
                    } elseif ($status == 'New Release') {
                        $badgeClass = 'badge-release';
                        $badgeText = 'New Release';
                    } elseif ($status == 'Discounted') {
                        $badgeClass = 'badge-discounted';
                        $badgeText = 'Discounted';
                    } else {
                        $badgeClass = '';
                        $badgeText = '';
                    }
                    ?>
                    <div class="badge <?php echo $badgeClass; ?>"><?php echo $badgeText; ?></div>
                <?php } ?>
                <img src="/movie-booking/app/thumb/<?php echo $movie['img']; ?>" alt="<?php echo $movie['mv_name']; ?>">
                <div class="movie-info">
                    <h2><?php echo $movie['mv_name']; ?></h2>
                    <p><?php echo $movie['runtime']; ?></p>
                    <div class="movie-tags">
                        <span class="tag"><?php echo $movie['certificate']; ?></span>
                        <span class="tag"><?php echo $movie['lang']; ?></span>
                    </div>
                    <div class="movie-tags2">
                        <span class="tag"><?php echo $movie['mv_tag']; ?></span>
                    </div>
                </div>
                <?php
                $id = $movie['id'];
                $cal = (($id * 123456789 * 54321) / 956783);
                $url = "index.php?url=description/description&id=" . urlencode(base64_encode($cal));
                $trailerLink = $movie['link1']; // Fetch trailer link from the database

                ?>
                <div class="over">
                    <a href="<?php echo $url; ?>" class="nav-link"><button class="over-btn">Book Tickets</button></a>
                    <a href="<?php echo $trailerLink ?> " target="_blank"><button class="over-btn2">Watch Trailer</button> </a>
                </div>
            </div>
            <?php
        }
    }
    ?>
</div>
