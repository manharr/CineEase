<?php
// Include the database connection file
include '../../../config/db.php';

$bookingDetails = [];
$movieDetails = [];
$combo_offer = '';

// Check if tid is provided via GET
if (isset($_GET['tid'])) {
    // Decode the booking ID
    $encoded_value = $_GET['tid'];
    $decoded_value = base64_decode(urldecode($encoded_value));

    // Reverse the calculation to get the original ID
    $id = round((($decoded_value * 956783) / 54321) / 123456789);
    $id = intval(mysqli_real_escape_string($con, $id));

    // Fetch the booking details
    $query = "
    SELECT b.*, m.mv_name AS movie_name, m.runtime AS runtime, s.movie_timings AS show_time, 
           GROUP_CONCAT(CONCAT(seat.seat_row, seat.seat_number) ORDER BY seat.seat_row, seat.seat_number SEPARATOR ', ') AS seats,
           f.combo_offer AS combo_offer  -- Fetch the latest combo offer based on Firebase UID
    FROM bookings b
    JOIN movie m ON b.movie_id = m.id
    JOIN show_timings s ON b.showtime_id = s.id
    JOIN seats seat ON FIND_IN_SET(seat.id, b.seats) > 0
    LEFT JOIN food f ON f.firebase_uid = b.firebase_uid  -- Assuming there is a firebase_uid in bookings table
    WHERE b.id = $id
    ORDER BY f.created_at DESC  -- Sort by the creation date to get the latest offer
    LIMIT 1  -- Get the latest combo offer
";


    $result = mysqli_query($con, $query);

    if ($result) {
        $bookingDetails = mysqli_fetch_assoc($result);
    } else {
        $error = 'Error fetching booking details: ' . mysqli_error($con);
    }

    // Check if combo is provided via GET and decode it
    if (isset($_GET['combo'])) {
        $combo_offer = base64_decode(urldecode($_GET['combo']));
    }
} else {
    $error = 'Booking ID not provided';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Ticket</title>
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <!-- Google Fonts and External CSS Imports -->
    <style>
        @import url("https://fonts.googleapis.com/css2?family=Acme&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap");
        @import url("https://fonts.googleapis.com/css2?family=Lato:wght@400;700&family=Montserrat:wght@600&display=swap");

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background-color: #383735;
            font-family: 'Lato', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .ticket-container {
            width: 1000px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #000;
            border-radius: 10px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            font-size: 1rem;
        }

        #ticket {
            display: flex;
            width: 100%;
            color: #fff;
        }

        #ticket .left,
        #ticket .right {
            padding: 2em;
        }

        #ticket .left {
            width: 65%;
            border-right: 2px dashed #555;
            background-color: #1c1c1c;
        }

        #ticket .right {
            width: 35%;
            background-color: #141414;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
        }

        /* Left Section */
        #ticket .left h2 {
            font-family: 'Lato', sans-serif;
            font-size: 2.1rem;
            color: #f1c40f;
        }

        #ticket .left .details {
            margin-top: 1.5em;
            display: grid;
            grid-template-columns: auto auto;
            gap: 0.8em 1.5em;
            font-weight: 500;
        }

        #ticket .left .details div {
            color: #fff;
        }

        #ticket .seat-code {
            margin-top: 2.2em;
            color: white;
            text-align: center;
            font-weight: 700;
            font-size: 20px;
            position: relative;
            left:-226px;
            top:24px;
        }

        #total {
            position: relative;
            left: 400px;
            font-size: 20px;
        }

        /* Right Section */
        #ticket .right h3 {
            font-size: 1.5rem;
            color: #f1c40f;
        }

        #ticket .right h6 {
            margin-top: 0.5em;
            font-size: 1rem;
            color: #ccc;
        }

        #ticket .right .details {
            font-size: 0.95rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.8em;
            width: 100%;
        }

        #ticket .right .details div {
            text-align: center;
            color: #fff;
            font-weight: 500;
        }

        #ticket .right div {
            position: relative;
            top: 8px;
        }

        .qr-code img {
            width: 120px;
        }

        .qr-code {
            margin-top: 2em;
        }

        /* Hover effects */
        #ticket .left h2:hover,
        #ticket .right h3:hover,
        .seat-code:hover {
            color: #e67e22;
            cursor: pointer;
        }

        .ticket-container:hover {
            box-shadow: 0 30px 40px rgba(0, 0, 0, 0.6);
            transition: box-shadow 0.5s ease;
        }
    </style>
</head>
<body>

<div class="ticket-container">
        <div id="ticket">
            <!-- Left Section -->
            <div class="left">
                <h2><?php echo htmlspecialchars($bookingDetails['movie_name']); ?></h2>
                <div class="details">
                    <div>Date:</div>
                    <div><?php echo date('d F Y', strtotime($bookingDetails['movie_date'])); ?></div>
                    <div>Time:</div>
                    <div><?php echo htmlspecialchars($bookingDetails['show_time']); ?></div>
                    <div>Theater:</div>
                    <div><?php echo htmlspecialchars($bookingDetails['theater']); ?></div>
                    <div>Food & Beverages:</div>
            <div><?php echo htmlspecialchars($bookingDetails['combo_offer']); ?></div>

                </div>
                <div class="seat-code">Status: <?php echo htmlspecialchars($bookingDetails ['status']); ?></div>
                <span id="total">Total Amount: ₹<?php echo htmlspecialchars($bookingDetails['total_price']); ?></span>
            </div>

            <!-- Right Section -->
            <div class="right">
                <div>
                    <h3>Booking ID - <?php echo htmlspecialchars($bookingDetails['booking_uid']); ?></h3>
                </div>
                <div class="details">
                    <div>Duration:</div>
                    <div><?php echo htmlspecialchars($bookingDetails['runtime']); ?></div>
                    <div>Seats:</div>
                    <div><?php echo htmlspecialchars($bookingDetails['seats']); ?></div>
                </div>
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=120x120&data=<?php echo urlencode($bookingDetails['booking_uid']); ?>" alt="QR Code">
                </div>
            </div>
        </div>
    </div>

</body>
</html>