<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($movie['mv_name']); ?> </title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/movie-booking/public/css/description.css"> <!-- New styles -->
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <script src="/movie-booking/public/js/description.js" ></script>
</head>
<body>
    
    <div class="movie-container">
        
        <div class="movie-banner">
            <?php echo "<img src='/movie-booking/app/thumb/" . htmlspecialchars($movie['img']) . "' alt='" . htmlspecialchars($movie['mv_name']) . "'>"; ?>
        </div>
        
        <div class="movie-details">
            <h1><?php echo htmlspecialchars($movie['mv_name']); ?></h1>
            <p id="desc"><?php echo htmlspecialchars($movie['mv_des']); ?></p>
            
            <div class="movie-info">
                <div class="info-item"><strong>Release Date:</strong> <?php echo date('d M', strtotime($movie['sdate'])); ?></div>
                <div class="info-item"><strong>Director:</strong> <?php echo htmlspecialchars($movie['director']); ?></div>
                <div class="info-item"><strong>Language:</strong> <?php echo htmlspecialchars($movie['lang']); ?></div>
                <div class="info-item"><strong>Runtime:</strong> <?php echo htmlspecialchars($movie['runtime']); ?></div>
                
            </div>
            
            <div class="movie-meta">
                <div class="meta-item"><strong>Genre:</strong> <?php echo htmlspecialchars($movie['mv_tag']); ?></div>
                <div class="meta-item"><strong>Starring:</strong>
                    <div class="actor-container">
                        <?php foreach ($actor_data as $actor): ?>
                            <div class="actor-image" style="background-image: url('<?php echo htmlspecialchars($actor['image']); ?>');"></div>
                            <div class="actor-name"><?php echo htmlspecialchars($actor['name']); ?></div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
            </div>
            <div class="meta-item2">
                <div class="ratings-container"><strong>Ratings:</strong>
                    <div class="rating"><strong>IMDb:</strong> <?php echo htmlspecialchars($ratings['imdb']); ?></div>
                    <div class="rating"><strong>Rotten Tomatoes:</strong> <?php echo htmlspecialchars($ratings['rotten_tomatoes']); ?></div>
                    
                    <label class="container" id="heart-<?php echo $movie['id']; ?>">
                        <input type="checkbox" onclick="toggleHeart('<?php echo $movie['mv_name']; ?>', this)">
                        <div class="checkmark">
                            <svg viewBox="0 0 256 256">
                                <rect fill="none" height="256" width="256"></rect>
                                <path d="M224.6,51.9a59.5,59.5,0,0,0-43-19.9,60.5,60.5,0,0,0-44,17.6L128,59.1l-7.5-7.4C97.2,28.3,59.2,26.3,35.9,47.4a59.9,59.9,0,0,0-2.3,87l83.1,83.1a15.9,15.9,0,0,0,22.6,0l81-81C243.7,113.2,245.6,75.2,224.6,51.9Z" stroke-width="20px" stroke="#FFF" fill="none"></path>
                            </svg>
                        </div>
                    </label>


                <div class="notification" id="notification-<?php echo $movie['id']; ?>"></div>

                </div>
            </div>

        </div>
    </div>
    <script>
function toggleHeart(movieName, checkbox) {
    const movieId = checkbox.closest('.container').id.split('-')[1];
    const isFilled = checkbox.checked;
    const notification = document.getElementById(`notification-${movieId}`);

    localStorage.setItem(`liked-${movieId}`, isFilled);
    notification.textContent = isFilled ? `You liked ${movieName}!` : `You unliked ${movieName}.`;
    notification.classList.add('show');

    setTimeout(() => {
        notification.classList.remove('show');
    }, 3000); 
}

window.onload = function() {
    const containers = document.querySelectorAll('.container');
    containers.forEach(container => {
        const movieId = container.id.split('-')[1];
        const isLiked = localStorage.getItem(`liked-${movieId}`) === 'true'; // Check localStorage
        const checkbox = container.querySelector('input[type="checkbox"]');
        checkbox.checked = isLiked; 
    });
};
</script>



    <?php
    $defaultSelectedDate = date('Y-m-d');
    date_default_timezone_set('Asia/Kolkata'); 

    ?>
    <div class="exact-banner-container">
    <div class="exact-left-arrow"></div>
    <div class="exact-banner-text">
        <div class="date-selection">
            <?php $isFirst = true; ?>
            <?php foreach ($dates as $date): ?>
                <span class="date <?php echo ($date === $selectedDate) ? 'selected' : ''; ?>" data-date="<?php echo htmlspecialchars($date); ?>">
                    <span class="date-day"><?php echo date('j M', strtotime($date)); ?></span>
                    <span class="date-weekday"><?php echo date('D', strtotime($date)); ?></span>
                </span>
                <?php $isFirst = false; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="exact-right-arrow"></div>
</div>

    

    
 <!-- Movie timings selection and lang/format -->
    <div class="movie-container2">
        <div class="theatre-wrapper">
            <?php if (!empty($theatres)): ?>
                <?php foreach ($theatres as $theatre): ?>
                    
                    <button class="cta">
                        <span class="span1"><?php echo htmlspecialchars($theatre['theatre_name']); ?></span>
                        <span class="second">
                            <svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 66 43" height="20px" width="50px">
                                <g fill-rule="evenodd" fill="none" stroke-width="1" stroke="none" id="arrow">
                                    <path fill="#FFFFFF" d="M40.1543933,3.89485454 L43.9763149,0.139296592 C44.1708311,-0.0518420739 44.4826329,-0.0518571125 44.6771675,0.139262789 L65.6916134,20.7848311 C66.0855801,21.1718824 66.0911863,21.8050225 65.704135,22.1989893 C65.7000188,22.2031791 65.6958657,22.2073326 65.6916762,22.2114492 L44.677098,42.8607841 C44.4825957,43.0519059 44.1708242,43.0519358 43.9762853,42.8608513 L40.1545186,39.1069479 C39.9575152,38.9134427 39.9546793,38.5968729 40.1481845,38.3998695 C40.1502893,38.3977268 40.1524132,38.395603 40.1545562,38.3934985 L56.9937789,21.8567812 C57.1908028,21.6632968 57.193672,21.3467273 57.0001876,21.1497035 C56.9980647,21.1475418 56.9959223,21.1453995 56.9937605,21.1432767 L40.1545208,4.60825197 C39.9574869,4.41477773 39.9546013,4.09820839 40.1480756,3.90117456 C40.1501626,3.89904911 40.1522686,3.89694235 40.1543933,3.89485454 Z" class="one"></path>
                                    <path fill="#FFFFFF" d="M20.1543933,3.89485454 L23.9763149,0.139296592 C24.1708311,-0.0518420739 24.4826329,-0.0518571125 24.6771675,0.139262789 L45.6916134,20.7848311 C46.0855801,21.1718824 46.0911863,21.8050225 45.704135,22.1989893 C45.7000188,22.2031791 45.6958657,22.2073326 45.6916762,22.2114492 L24.677098,42.8607841 C24.4825957,43.0519059 24.1708242,43.0519358 23.9762853,42.8608513 L20.1545186,39.1069479 C19.9575152,38.9134427 19.9546793,38.5968729 20.1481845,38.3998695 C20.1502893,38.3977268 20.1524132,38.395603 20.1545562,38.3934985 L36.9937789,21.8567812 C37.1908028,21.6632968 37.193672,21.3467273 37.0001876,21.1497035 C36.9980647,21.1475418 36.9959223,21.1453995 36.9937605,21.1432767 L20.1545208,4.60825197 C19.9574869,4.41477773 19.9546013,4.09820839 20.1480756,3.90117456 C20.1501626,3.89904911 20.1522686,3.89694235 20.1543933,3.89485454 Z" class="two"></path>
                                    <path fill="#FFFFFF" d="M0.154393339,3.89485454 L3.97631488,0.139296592 C4.17083111,-0.0518420739 4.48263286,-0.0518571125 4.67716753,0.139262789 L25.6916134,20.7848311 C26.0855801,21.1718824 26.0911863,21.8050225 25.704135,22.1989893 C25.7000188,22.2031791 25.6958657,22.2073326 25.6916762,22.2114492 L4.67709803,42.8607841 C4.48259567,43.0519059 4.1708242,43.0519358 3.97628529,42.8608513 L0.154518568,39.1069479 C-0.0424847685,38.9134427 -0.0453207248,38.5968729 0.148184485,38.3998695 C0.150289338,38.3977268 0.152413224,38.395603 0.154556199,38.3934985 L17.9937789,21.8567812 C18.1908028,21.6632968 18.1936717,21.3467273 18.0001876,21.1497035 C17.9980647,21.1475418 17.9959223,21.1453995 17.9937605,21.1432767 L0.154520844,4.60825197 C-0.0424845685,4.41477773 -0.0453206612,4.09820839 0.148075601,3.90117456 C0.150162594,3.89904911 0.152268611,3.89694235 0.154393339,3.89485454 Z" class="three"></path>
                                </g>
                            </svg>
                        </span>
                    </button>
                    <div class="address-container">
                        <span id="span2"><?php echo htmlspecialchars($theatre['address']); ?></span>
                    </div>


                    
                     <!-- Show Timings Section for each Theatre -->
<!-- <pre>
    <?php print_r($theatreData); ?>
</pre> -->
<?php
date_default_timezone_set('Asia/Kolkata'); 

$currentDate = date('Y-m-d'); 
$currentTime = date('H:i'); 

// Fetch show timings from the model
$theatre_id = $theatre['id'];
$showTimings = MovieDesc::getShowTimings($theatre_id, $encoded_id);

if (!empty($showTimings)):
    $showTimingsAvailable = false; // Flag to track if any show timings are available for today

    foreach ($showTimings as $language => $timings):
        // Sort show timings by time
        usort($timings, function($a, $b) {
            $timeA = date('H:i', strtotime($a['time']));
            $timeB = date('H:i', strtotime($b['time']));
            return strcmp($timeA, $timeB);
        });
?>
        <div class="details">
            <div class="language-title"><?php echo htmlspecialchars($language); ?></div>
            <div class="show-timings">
                <?php
                $hasFutureTimings = false; 

                foreach ($timings as $time): 
                    $showTimeDate = date('Y-m-d', strtotime($selectedDate));
                    $showTimeFormatted = date('H:i', strtotime($time['time']));

                    // Check if the selected date is today and filter out past timings
                    if ($showTimeDate > $currentDate || ($showTimeDate == $currentDate && $showTimeFormatted > $currentTime)):
                        $hasFutureTimings = true; // Mark that future timings are available
                        $showTimingsAvailable = true; // Mark that at least one timing is available for today
                        
                        $movie_id = $time['movie_id'];
                        $show_time_id = $time['show_time_id'];
                        $cal = (($movie_id * 123456789 * 54321) / 956783);
                        $encoded_movie_id = base64_encode($cal);
                        $timeFormatted = date('g:i A', strtotime($time['time']));
                        $url = "index.php?url=seatselection/seatselection&movie_id=" . urlencode($encoded_movie_id) . "&show_time_id=" . urlencode($show_time_id) . "&language=" . urlencode($language) . "&format=" . urlencode($time['format']) . "&time=" . urlencode($timeFormatted) . "&date=" . urlencode($selectedDate);
                ?>
                        <a href="<?php echo htmlspecialchars($url); ?>">
                            <div class="show-time"><?php echo htmlspecialchars($timeFormatted . ' (' . $time['format'] . ')'); ?></div>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>

                <!-- If no future timings for this language, display a message -->
                <?php if (!$hasFutureTimings && $showTimeDate == $currentDate): ?>
                    <p>No more show timings left for today in <?php echo htmlspecialchars($language); ?>.</p>
                <?php endif; ?>
            </div>
        </div>
        <br>
    <?php endforeach; ?>

    <!-- If no show timings available at all for today -->
    <?php if (!$showTimingsAvailable && $selectedDate == $currentDate): ?>
        <!-- <p>No show timings left for today. Please check tomorrow or select a future date.</p> -->
    <?php endif; ?>

<?php else: ?>
    <p>No show timings available.</p>
<?php endif; ?>



            <?php endforeach; ?>
        <?php else: ?>
            <p>No theatres available.</p>
        <?php endif; ?>
    </div>
</div>

<a href="index.php" > <img id="home" src="/movie-booking/app/views/img/home.png" alt=""> </a>

<script>

    var defaultSelectedDate = '<?php echo $defaultSelectedDate; ?>';
   
</script>
        
<div id="loader">
    <div class="traffic-loader"></div>
</div>

<div id="content" style="display: none;">
</div>


<script>
    window.addEventListener('load', function() {
        document.getElementById('loader').style.display = 'none';
        document.getElementById('content').style.display = 'block';
    });
    
</script> 
</body>
</html>
