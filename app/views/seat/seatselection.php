<?php
session_start();

if (isset($_SESSION['booking_confirmed']) && $_SESSION['booking_confirmed']) {
    echo "You have already booked seats. Please go to the confirmation page.";
    exit;
}

// Reset booking status for a new booking
unset($_SESSION['booking_confirmed']);
$_SESSION['booking_session_id'] = uniqid(); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/movie-booking/public/css/seats.css" />
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <script src="/movie-booking/public/js/seats.js" ></script>
    <title>CineEase - Seat Selection</title>
</head>
<body>
<?php
$selectedDate = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');  
$formattedDate = strtoupper(date('j M', strtotime($selectedDate))); 

?>
   
    <div class="movie-container">
    </div>
    <form id="booking-form" action="index.php?url=FoodSelection/food" method="POST">
    <input type="hidden" name="selectedDate" value="<?php echo htmlspecialchars($selectedDate); ?>" />
        <input type="hidden" name="firebase_uid" id="form-firebase-uid" value="" />
        <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($id); ?>" />
        <input type="hidden" name="show_time_id" value="<?php echo htmlspecialchars($showTimeId); ?>" />
        <input type="hidden" name="selected_seats" id="form-seats" value="" />
        <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>" />

        <div class="seat-selection-wrapper">
            <div class="container">
                <div class="screen"></div>

                <!-- Price label for GOLD seats -->
                <div class="price-label">Gold - <?php echo $goldPrice ?></div>

                <!-- Seat rows for GOLD -->
                <div id="seats-container-gold"></div>

                <!-- Price label for PLATINUM seats -->
                <div class="price-label">Platinum - <?php echo $platinumPrice?></div>

                <!-- Seat rows for PLATINUM -->
                <div id="seats-container-platinum"></div>
            </div>
        </div>

        <ul class="showcase">
            <li>
                <div class="seat" id="scase"></div>
                <small>Available</small>
            </li>
            <li>
                <div class="seat selected" id="selected"></div>
                <small>Selected</small>
            </li>
            <li>
                <div class="seat occupied" id="scase"></div>
                <small>Booked</small>
            </li>
        </ul>
        <p class="text"></p>
     <!-- MOVIE DETAILS CONTAINER -->
        <div class="ticket-container">
            <div class="ticket-header">
                <span class="ticket-title" id="mv"><?php echo htmlspecialchars($movie['mv_name']); ?> &nbsp;&nbsp;<?php echo htmlspecialchars($timings['movie_format']); ?></span> 
                <div class="ticket-details">
                    <span><?php echo htmlspecialchars($timings['movie_language']); ?></span>
                    <span><?php echo htmlspecialchars($timings['movie_timings']); ?></span>
                    <span><?php echo htmlspecialchars($formattedDate); ?></span> 
                    <span><?php echo htmlspecialchars($theatre['theatre_name']); ?></span>
                    

                </div>
                <span class="ticket-summary" id="mv3"><span id="count">0</span> ticket(s)</span>
            </div>

            <div class="total-price">
                <strong id="scr">SCREEN: </strong>
                <p><?php echo htmlspecialchars($timings['screen']); ?></p>
            </div>
            <div class="selected-seats">
                <strong>SEATS:</strong>
                <p id="selected-seats">No seats selected</p>
            </div>

            <!-- Promocode and Bonuses Section -->
            <div class="promocode-bonuses-container">
            </div>
            <hr id="hr">
            <div class="total-price">
                <strong id="t2">TOTAL:</strong>
                <p id="total"></p>
            </div>
            <div class="fees-taxes">
                <p id="taxes-info">Includes taxes and platform fee</p>
            </div>
            <button type="submit" class="book-btn">PROCEED</button>
        </div>
    </form>
</body>
</html>
