<?php
include 'db.php';

// Fetch parameters from the URL
$movieId = isset($_GET['movie_id']) ? $_GET['movie_id'] : null;
$showTimeId = isset($_GET['show_time_id']) ? $_GET['show_time_id'] : null;
$theatreId = isset($_GET['theatre_id']) ? $_GET['theatre_id'] : null;
$date = isset($_GET['date']) ? $_GET['date'] : date('Y-m-d');

// Ensure all required parameters are provided
if (!$movieId || !$showTimeId || !$theatreId) {
    echo "Missing movie, showtime, or theatre ID.";
    exit;
}

// Fetch Movie Name
$movieQuery = "SELECT mv_name FROM movie WHERE id = $movieId";
$movieResult = mysqli_query($con, $movieQuery);
$movieRow = mysqli_fetch_assoc($movieResult);
$movieName = $movieRow['mv_name'] ?? 'Unknown Movie';

// Fetch Theatre Name
$theatreQuery = "SELECT theatre_name FROM theatre WHERE id = $theatreId";
$theatreResult = mysqli_query($con, $theatreQuery);
$theatreRow = mysqli_fetch_assoc($theatreResult);
$theatreName = $theatreRow['theatre_name'] ?? 'Unknown Theatre';

// Fetch Show Time
$showTimeQuery = "SELECT movie_timings FROM show_timings WHERE id = $showTimeId";
$showTimeResult = mysqli_query($con, $showTimeQuery);
$showTimeRow = mysqli_fetch_assoc($showTimeResult);
$showTime = $showTimeRow['movie_timings'] ?? 'Unknown Show Time';

// Function to get seats
function getSeats($con, $movieId, $showTimeId, $date)
{
    $seatsQuery = "SELECT seat_row, seat_number, status FROM seats WHERE movie_id = $movieId AND show_time_id = $showTimeId AND booking_date = '$date'";
    $seatsRun = mysqli_query($con, $seatsQuery);
    $seats = [];
    while ($seatRow = mysqli_fetch_assoc($seatsRun)) {
        $seats[$seatRow['seat_row'] . $seatRow['seat_number']] = $seatRow['status'];
    }
    return $seats;
}

// Fetch seats data
$seats = getSeats($con, $movieId, $showTimeId, $date);

// Define the seat rows and numbers
$rows = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];
$numbers = range(1, 11);

// Prepare seat rendering
$seatHTML = '';
foreach ($rows as $row) {
    $seatHTML .= "<div class='row' data-row='$row'><div class='rname'>$row</div>";
    foreach ($numbers as $number) {
        $seatId = $row . $number;
        $seatStatus = isset($seats[$seatId]) ? $seats[$seatId] : 'available';
        $seatClass = ($seatStatus === 'booked') ? 'seat occupied' : 'seat';
        $seatHTML .= "<div class='$seatClass' data-seat-id='$seatId'></div>";
    }
    $seatHTML .= '</div>';
}

$formattedDate = strtoupper(date('j M', strtotime($date)));
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="seats.css">
    <title>Admin - Seat Selection</title>
</head>
<body>
    <div class="container">
        <h2>Date: <?php echo $formattedDate; ?></h2>
        <div class="seat-selection-wrapper">
            <?php echo $seatHTML; ?>
        </div>
        <form id="reserve-seats-form" action="seatsbook.php" method="POST">
            <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movieId); ?>">
            <input type="hidden" name="show_time_id" value="<?php echo htmlspecialchars($showTimeId); ?>">
            <input type="hidden" name="date" value="<?php echo htmlspecialchars($date); ?>">
            <input type="hidden" name="selected_seats" id="selected-seats" value="">

            <div class="ticket-container">
    <div class="ticket-header">
        <span class="ticket-title">Movie: <?php echo htmlspecialchars($movieName); ?></span>
        <div class="ticket-details">
            <span>Theatre: <?php echo htmlspecialchars($theatreName); ?></span><br>
            <span>Show Time: <?php echo htmlspecialchars($showTime); ?></span><br>
            <span>Date: <?php echo htmlspecialchars($formattedDate); ?></span>
        </div>
    </div>
    <div>
        <button type="submit" class="reserve-button">RESERVE SEATS</button>
    </div>
</div>

        </div>
        </form>

    </div>

    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const container = document.querySelector('.container');
        const selectedSeatsContainer = document.getElementById('selected-seats');
        const maxSeats = 10; // Maximum seats allowed

        const updateSelectedCount = () => {
            const selectedSeats = document.querySelectorAll('.seat.selected');
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.getAttribute('data-seat-id'));
            selectedSeatsContainer.value = seatNumbers.join(','); // Update hidden input for form submission
        };

        container.addEventListener('click', (e) => {
            if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
                const selectedSeatsCount = document.querySelectorAll('.seat.selected').length;

                if (selectedSeatsCount >= maxSeats && !e.target.classList.contains('selected')) {
                    alert(`You cannot select more than ${maxSeats} seats.`);
                    return; // Prevent selecting more seats
                }

                e.target.classList.toggle('selected'); // Toggle selection
                // Update seat display for selected seats
                e.target.textContent = e.target.classList.contains('selected') ? e.target.getAttribute('data-seat-id') : '';
                
                // Update the selected seat count and display
                updateSelectedCount();
            }
        });

        updateSelectedCount(); // Initial update for selected seats
    });
    </script>

</body>
</html>
