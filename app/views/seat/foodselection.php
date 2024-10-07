<?php
session_start(); 

if (isset($_SESSION['booking_confirmed']) && $_SESSION['booking_confirmed']) {
    echo "You have already booked seats. Please go to the confirmation page.";
    exit;
}

unset($_SESSION['booking_confirmed']);
$_SESSION['booking_session_id'] = uniqid();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="/movie-booking/public/css/food.css" />
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <script src="/movie-booking/public/js/food.js"></script>
    <script src="/movie-booking/public/js/foodsecurity.js" type="module"></script>
    <script src= "/movie-booking/public/js/payment.js"></script>
    <script src= "/movie-booking/public/js/foodselection.js"></script>
    <title>CineEase</title>
</head>
<body>
<?php
$selectedDate = isset($_POST['selectedDate']) ? $_POST['selectedDate'] : date('Y-m-d');
$formattedDate = strtoupper(date('j M', strtotime($selectedDate)));
?>
    <form id="food-form" action="index.php?url=SeatBooking/bookSeats" method="POST">
    <input type="hidden" name="firebase_uid" id="form-firebase-uid" value="<?php echo htmlspecialchars($firebaseUid); ?>" />
    <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>" />
    <input type="hidden" name="show_time_id" value="<?php echo htmlspecialchars($showTimeId); ?>" />
    <input type="hidden" name="selected_seats" value="<?php echo htmlspecialchars($selectedSeats); ?>" />
    <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>" />
    <input type="hidden" name="food" id="form-food" value="" />
    <input type="hidden" name="total_price" id="form-total-price" value="<?php echo htmlspecialchars($totalPrice); ?>" />
    <input type="hidden" name="screen" id="form-screen" value="" />
    <input type="hidden" name="theater" id="form-theater" value="" />
    <!-- <input type="hidden" name="theatre_id" id="form-theatre-id" value="<?php echo htmlspecialchars($theatreId); ?>" /> -->

        <!-- Food Items Section -->
        <div class="food-item-container">
            <!-- Example food item -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/coffee2.jpeg" alt="Latte" class="food-image">
                <div class="food-info">
                    <h3>Latte</h3>
                    <p>150 mL</p>
                    <p class="food-price">₹230</p>
                </div>
                <button type="button" class="food-add-btn" data-food="Latte" data-price="230">+</button>
                <button type="button" class="food-remove-btn" data-food="Latte" data-price="230">-</button>
            </div>
<!-- 2 -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/coca.jpeg" alt="Coca" class="food-image">
                <div class="food-info">
                    <h3>Coca Cola</h3>
                    <p>150 mL</p>
                    <p class="food-price">₹99</p>
                </div>
                <button type="button" class="food-add-btn" data-food="Coca" data-price="99">+</button>
                <button type="button" class="food-remove-btn" data-food="Coca" data-price="99">-</button>
            </div>
<!-- 3 -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/popcorn.jpeg" alt="Combo1" class="food-image">
                <div class="food-info">
                    <h3>Popcorn</h3>
                    <p>Small</p>
                    <p class="food-price">₹189</p>
                </div>
                <button type="button" class="food-add-btn" data-food="popcorn" data-price="189">+</button>
                <button type="button" class="food-remove-btn" data-food="popcorn" data-price="189">-</button>
            </div>
<!-- 4 -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/combo4.jpeg" alt="combo4" class="food-image">
                <div class="food-info">
                    <h3>Combo 1</h3>
                    <p>Popcorn & Cola</p>
                    <p class="food-price">₹249</p>
                </div>
                <button type="button" class="food-add-btn" data-food="Combo1" data-price="249">+</button>
                <button type="button" class="food-remove-btn" data-food="Combo1" data-price="249">-</button>
            </div>
<!-- 5 -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/combo1.jpeg" alt="combo1" class="food-image">
                <div class="food-info">
                    <h3>Combo 2</h3>
                    <p>Popcorn, Nachos & Cola</p>
                    <p class="food-price">₹469</p>
                </div>
                <button type="button" class="food-add-btn" data-food="Combo2" data-price="469">+</button>
                <button type="button" class="food-remove-btn" data-food="Combo2" data-price="469">-</button>
            </div>
<!-- 6 -->
            <div class="food-card">
                <img src="/movie-booking/app/views/seat/combos/nachos.jpeg" alt="nachos" class="food-image">
                <div class="food-info">
                    <h3>Nachos</h3>
                    <p>With Mayonnaise Dip</p>
                    <p class="food-price">₹219</p>
                </div>
                <button type="button" class="food-add-btn" data-food="Nachos" data-price="219">+</button>
                <button type="button" class="food-remove-btn" data-food="Nachos" data-price="219">-</button>
            </div>
<!-- 7 -->
           
            
            
        </div>


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
                <span class="ticket-summary" id="mv3">
                    <span id="seat-count"><?php echo htmlspecialchars($selectedSeatsCount); ?></span> ticket(s)
                </span>
            </div>

            <div class="total-price">
                <strong id="scr">SCREEN:</strong>
                <p><?php echo htmlspecialchars($timings['screen']); ?></p>
            </div>
            <div class="selected-seats">
                <strong>SEATS:</strong>
                <p id="selected-seats"><?php echo htmlspecialchars($selectedSeatsDisplay); ?></p>
            </div>

            <div class="selected-food">
                <strong>FOOD:</strong>
                <p id="selected-food">No food selected</p>
            </div>
            <div class="used-promocode-container" style="display: none;">
                <p id="applied-promo-code"></p>
            </div>

            <!-- Promocode and Bonuses Section -->
            <div class="promocode-bonuses-container">
                <a href="#" id="promo-link" class="promocode-btn">+ Use Promocode</a>
                <div id="promo-input-container" style="display: none;">
                    <input type="text" id="promo-code" placeholder="Enter promo code">
                    <input type="submit" id="apply-promo" value="Apply" class="apply-btn">
                    <p id="promo-message" style="color: red; display: none;">Invalid promo code!</p>
                </div>
            </div>

            <hr id="hr">
            <div class="total-price">
                <strong id="t2">TOTAL:</strong>
                <p id="total">₹<?php echo htmlspecialchars(number_format($totalPrice, 1)); ?></p>
            </div>
            <div class="fees-taxes">
                <span id="gst-info"></span>
                <p id="taxes-info">PLATFORM FEE: ₹2</p>
            </div>

            <button id="proceed-payment-btn" type="submit" class="book-btn">Proceed To Payment</button>
        </div>
        
    </form>

<!-- payment -->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
    window.totalPrice = <?php echo json_encode($totalPrice); ?>; 
</script>


</body>
</html>
