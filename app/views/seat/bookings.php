<?php
// Include the database connection file
include '../../../config/db.php';

$bookings = [];
$combo_offers = [];
$cancelled_bookings = [];

// Check if firebase_uid is provided via POST
if (isset($_POST['firebase_uid'])) {
    $firebase_uid = $_POST['firebase_uid'];

    // Query to fetch active booking details
    $query = "
        SELECT b.id, b.firebase_uid, m.mv_name AS movie_name, s.movie_timings AS show_time,
               b.movie_date, b.booking_date, b.screen, b.theater, b.total_price, b.status, b.booking_uid,
               GROUP_CONCAT(CONCAT(seat.seat_row, seat.seat_number) ORDER BY seat.seat_row, seat.seat_number SEPARATOR ', ') AS seats
        FROM bookings b
        JOIN movie m ON b.movie_id = m.id
        JOIN show_timings s ON b.showtime_id = s.id
        JOIN seats seat ON FIND_IN_SET(seat.id, b.seats) > 0
        WHERE b.firebase_uid = ?
        GROUP BY b.id";
    
    $stmt = $con->prepare($query);

    if ($stmt) {
        // Bind the firebase_uid to the statement and execute it
        $stmt->bind_param('s', $firebase_uid);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Fetch all active bookings for the user
        $bookings = $result->fetch_all(MYSQLI_ASSOC);
        $stmt->close();
    } else {
        $error = 'Error in the database query: ' . $con->error;
    }

    $cancelled_query = "
        SELECT cb.id, cb.firebase_uid, m.mv_name AS movie_name, s.movie_timings AS show_time,
               cb.movie_date, cb.booking_date, cb.screen, cb.theater, cb.total_price, 'cancelled' AS status, '' AS booking_uid,
               cb.seats
        FROM canbookings cb
        JOIN movie m ON cb.movie_id = m.id
        JOIN show_timings s ON cb.showtime_id = s.id
        WHERE cb.firebase_uid = ?
        GROUP BY cb.id";
    
    $cancelled_stmt = $con->prepare($cancelled_query);

    if ($cancelled_stmt) {
        $cancelled_stmt->bind_param('s', $firebase_uid);
        $cancelled_stmt->execute();
        $cancelled_result = $cancelled_stmt->get_result();
        
        $cancelled_bookings = $cancelled_result->fetch_all(MYSQLI_ASSOC);
        $cancelled_stmt->close();
    } else {
        $error = 'Error in the cancelled booking query: ' . $con->error;
    }

} else {
    $error = 'Firebase UID not provided';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking History</title>
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">
    <link rel="stylesheet" href="/movie-booking/public/css/cans.css">
</head>
<body>
    <!-- Header Section -->
    <header>
        <h1>CineEase</h1>
        <nav>
            <ul>
                <li><a href="#">My Bookings</a></li>
            </ul>
        </nav>
    </header>

    <!-- Main Container -->
    <div class="container">
    <div class="policy-notice">
                <p><strong>Cancellation Policy:</strong> Tickets can be cancelled up to 1 hour before the showtime for a full refund.</p>
            </div>
        <?php if (isset($error)): ?>
            <!-- <div class="message error"><?php echo $error; ?></div> -->

        <?php elseif (!empty($bookings)): ?>
           
            <table>
                <thead>
                    <tr>
                        <th>Booking Id</th>
                        <th>Movie</th>
                        <th>Date</th>
                        <th>Seats</th>
                        <th>Showtime</th>
                        <th>Theater</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="historyTable">
                    <?php 
                    date_default_timezone_set('Asia/Kolkata');
                    
                    foreach ($bookings as $booking):
                        $formattedDate = strtoupper(date('j M', strtotime($booking['movie_date'])));
                        
                        $showDateTimeStr = $booking['movie_date'] . ' ' . $booking['show_time'];
                        $showDateTime = strtotime($showDateTimeStr);
                        $cancelLimitTime = $showDateTime - (1 * 60 * 60);  // 1 hour before the showtime
                        $currentTime = time();
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['booking_uid']); ?></td>
                        <td><?php echo htmlspecialchars($booking['movie_name']); ?></td>
                        <td><?php echo "$formattedDate "?></td>
                        <td><?php echo htmlspecialchars($booking['seats']); ?></td>
                        <td><?php echo htmlspecialchars($booking['show_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['theater']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        
                        <td>
                        <?php
                        $id = $booking['id'];
                        $cal = (($id * 123456789 * 54321) / 956783);

                        $combo_offer = ''; 
                        if (!empty($combo_offers)) {
                            foreach ($combo_offers as $offer) {
                                $combo_offer = $offer['combo_offer'];
                            }
                        }

                        $url = "canbooking.php?deleteid=" . urlencode(base64_encode($cal));
                        $url2 = "ticket.php?tid=" . urlencode(base64_encode($cal)) . "&combo=" . urlencode(base64_encode($combo_offer));

                        if ($booking['status'] !== 'cancelled' && $currentTime < $cancelLimitTime): 
                        ?>
                            <a target="_blank" href="<?php echo $url2; ?>" class="view-btn">View</a>
                            <a href="<?php echo $url; ?>" class="cancel-btn" onclick="return confirm('Are you sure you want to cancel this booking?');">Cancel</a>
                        <?php else: ?>
                            <a target="_blank" href="<?php echo $url2; ?>" class="view-btn">View</a>
                            <span class="disabled-cancel"></span>
                        <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message">No Active bookings found for this user.</div>
        <?php endif; ?>
<br>
        <!-- Display cancelled bookings table if cancelled bookings are found -->
        <?php if (!empty($cancelled_bookings)): ?>
            <h2>Cancelled Bookings</h2>
            <table>
                <thead>
                    <tr>
                        <th>Movie</th>
                        <th>Date</th>
                        <th>Showtime</th>
                        <th>Theater</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="cancelledTable">
                    <?php 
                    foreach ($cancelled_bookings as $booking):
                        $formattedDate = strtoupper(date('j M', strtotime($booking['movie_date'])));
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($booking['movie_name']); ?></td>
                        <td><?php echo "$formattedDate "?></td>
                        <td><?php echo htmlspecialchars($booking['show_time']); ?></td>
                        <td><?php echo htmlspecialchars($booking['theater']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="message"></div>
        <?php endif; ?>
    </div>

    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js"></script>
    <script src="/movie-booking/public/js/book.js" type="module"></script>
</body>
</html>
