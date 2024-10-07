<?php
session_start(); // Ensure session is started

// Reset booking status after showing the confirmation
unset($_SESSION['booking_confirmed']);

// Display booking confirmation message or page content
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Successful</title>
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <link rel="stylesheet" href="/movie-booking/public/css/confirmation.css">
</head>
<body>
    
<div class="success-container">
        <div class="checkmark-container">
            <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
                <circle class="checkmark-circle" cx="30" cy="30" r="28" fill="none"/>
                <path class="checkmark-check" fill="none" d="M15 30l10 10 20-20"/>
            </svg>
        </div>
        <h1>Booking Confirmed!</h1>
        <p>Your tickets have been successfully booked. Enjoy the show!</p>
        <div class="button-container">
            <a href="/movie-booking/app/views/seat/bookings.php" class="btn view-ticket">My Bookings</a>
            <a href="index.php" class="btn home-button">Home</a>
        </div>
    </div>
</body>
</html>
