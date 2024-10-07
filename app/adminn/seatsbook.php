<?php
include 'db.php';

// Retrieve POST data
$movieId = $_POST['movie_id'] ?? null;
$showTimeId = $_POST['show_time_id'] ?? null;
$date = $_POST['date'] ?? date('Y-m-d');
$selectedSeatsString = $_POST['selected_seats'] ?? ''; 

// Convert the selected seats string into an array
$selectedSeats = !empty($selectedSeatsString) ? explode(',', $selectedSeatsString) : [];

if (!$movieId || !$showTimeId || empty($selectedSeats)) {
    echo json_encode(['success' => false, 'message' => 'Invalid booking data.']);
    exit;
}

$firebaseUid = 'ADMIN'; // Set firebaseUid to "ADMIN"
$bookedSeats = [];
$theater = ''; // Dynamically set based on the theater
$screen = '';  // Dynamically fetched from the show_timings table

// Fetch the theatre name and screen dynamically from show_timings table
$showTimeQuery = "SELECT t.theatre_name, st.screen 
                  FROM show_timings st
                  JOIN theatre t ON t.id = st.theatre_id
                  WHERE st.id = ?";
$showTimeStmt = $con->prepare($showTimeQuery);
$showTimeStmt->bind_param('i', $showTimeId);
$showTimeStmt->execute();
$showTimeResult = $showTimeStmt->get_result()->fetch_assoc();

$theater = $showTimeResult['theatre_name'] ?? 'Unknown Theatre';
$screen = $showTimeResult['screen'] ?? 'Unknown Screen';

foreach ($selectedSeats as $seat) {
    $seatRow = substr($seat, 0, 1);  // Seat row, e.g., 'A'
    $seatNumber = substr($seat, 1);  // Seat number, e.g., '4'

    // Attempt to book the seat by its ID
    $seatId = getSeatId($movieId, $showTimeId, $seatRow, $seatNumber, $date);
    if ($seatId && bookSeat($seatId, $firebaseUid, $date)) {
        $bookedSeats[] = $seatId; // Store the seat ID instead of seat label
    }
}

if (count($bookedSeats) === count($selectedSeats)) {

    // Insert the booking record
    $totalPrice = ''; // Total price left empty for admin-side booking
    insertBooking($firebaseUid, $movieId, $showTimeId, $screen, $date, $totalPrice, $bookedSeats, $theater);
    
    echo "<script>alert('Booking successful!'); window.location.href='bookseats.php';</script>";
    exit; // Ensure no further code is executed
} else {
    // Some seats could not be booked
    echo "<script>alert('Some seats could not be booked.'); window.location.href='bookseats.php';</script>";
    exit; // Ensure no further code is executed
}

// Function to get seat ID from seats table
function getSeatId($movieId, $showTimeId, $seatRow, $seatNumber, $date)
{
    global $con;
    $query = "SELECT id FROM seats WHERE movie_id = ? AND show_time_id = ? AND seat_row = ? AND seat_number = ? AND booking_date = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param('iisss', $movieId, $showTimeId, $seatRow, $seatNumber, $date);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['id'] ?? null; // Return seat ID or null if not found
}

function bookSeat($seatId, $firebaseUid, $date)
{
    global $con;
    // Start transaction
    $con->begin_transaction();

    try {
        // Lock the seat row to avoid concurrent bookings
        if (checkSeatExists($seatId, $date)) {
            // If the seat is already booked, return false
            return false;
        }

        // Proceed to book the seat by ID
        $query = "UPDATE seats SET status = 'booked', user_id = ? WHERE id = ?";
        $stmt = $con->prepare($query);
        $stmt->bind_param('si', $firebaseUid, $seatId);
        $stmt->execute();

        // Commit transaction if booking succeeds
        $con->commit();
        return true;

    } catch (Exception $e) {
        // Rollback transaction in case of an error
        $con->rollback();
        return false;
    }
}

function checkSeatExists($seatId, $date)
{
    global $con;
    $query = "SELECT status FROM seats WHERE id = ? AND booking_date = ? FOR UPDATE";
    $stmt = $con->prepare($query);
    $stmt->bind_param('is', $seatId, $date);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();
    return $result['status'] === 'booked'; // Return true if seat is booked
}

function insertBooking($firebaseUid, $movieId, $showTimeId, $screen, $movieDate, $totalPrice, $seatIds, $theater)
{
    global $con;
    $bookingUid = substr(bin2hex(random_bytes(4)), 0, 8); // Generates 8-character UID
    // Store seat IDs as a comma-separated string
    $seatIdsStr = implode(',', $seatIds);
    $query = "INSERT INTO bookings (firebase_uid, movie_id, showtime_id, screen, movie_date, booking_date, total_price, seats, theater, booking_uid) 
              VALUES (?, ?, ?, ?, ?, NOW(), ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param('siissssss', $firebaseUid, $movieId, $showTimeId, $screen, $movieDate, $totalPrice, $seatIdsStr, $theater, $bookingUid);
    return $stmt->execute();
}
?>
