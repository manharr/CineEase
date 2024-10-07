<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

// Get the theatre ID from the session for the logged-in theatre admin
$theatre_id = $_SESSION['theatre_id'];

// Fetch the theatre name based on the logged-in admin's theatre ID
$theatreQuery = "SELECT theatre_name FROM theatre WHERE id = ?";
$stmt = mysqli_prepare($con, $theatreQuery);
mysqli_stmt_bind_param($stmt, 'i', $theatre_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if ($result && mysqli_num_rows($result) > 0) {
    $theatre = mysqli_fetch_assoc($result);
    $theatre_name = $theatre['theatre_name'];
} else {
    echo "<div class='container'><h3>Theatre not found for the logged-in admin.</h3></div>";
    exit(); // Exit if the theatre is not found
}

// Check if 'id' is set in the URL for adding showtime
if (isset($_GET['id'])) {
    $movie_id = $_GET['id'];

    // Fetch the selected movie details
    $query = "SELECT mv_name FROM movie WHERE id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'i', $movie_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if ($result && mysqli_num_rows($result) > 0) {
        $movie = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='container'><h3>Movie not found.</h3></div>";
        exit(); // Exit if the movie is not found
    }
} else {
    echo "<div class='container'><h3>No movie ID specified.</h3></div>";
    exit(); // Exit if no movie ID is specified
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $movie_id = $_POST['movie_id'];
    $movie_language = $_POST['language'];
    $movie_format = $_POST['format'];
    $movie_showtime = $_POST['showtime'];
    $screen = $_POST['screen'];
    $gold = $_POST['gold_price'];
    $plat = $_POST['platinum_price'];

    // Insert the showtime with the fixed theatre ID for the logged-in theatre admin
    $query = "INSERT INTO `show_timings` (`movie_id`, `theatre_id`, `movie_language`, `movie_format`, `movie_timings`, `gold_price`, `platinum_price`, `screen`) 
              VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'iisssssi', $movie_id, $theatre_id, $movie_language, $movie_format, $movie_showtime, $gold, $plat, $screen);
    $run = mysqli_stmt_execute($stmt);

    if ($run) {
        echo "<script>alert('Showtime successfully added.'); window.location.href='showlist.php';</script>";
    } else {
        echo "<script>alert('There was a problem while adding the showtime.'); window.location.href='addshow.php?id=" . htmlspecialchars($movie_id) . "';</script>";
    }
    exit(); // Prevent further output
}
?>

<div class="container">
    <h2>Add Showtime for <?php echo htmlspecialchars($movie['mv_name']); ?></h2>
    <form method="post" action="addshow.php?id=<?php echo htmlspecialchars($movie_id); ?>">
        <input type="hidden" name="movie_id" value="<?php echo htmlspecialchars($movie_id); ?>">
        <input type="hidden" name="theatre_id" value="<?php echo htmlspecialchars($theatre_id); ?>">

        <div class="form-group">
            <label for="theatre">Theatre:</label>
            <input type="text" class="form-control" id="theatre" name="theatre_name" value="<?php echo htmlspecialchars($theatre_name); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="language">Language:</label>
            <input type="text" class="form-control" id="language" name="language" required>
        </div>
        <div class="form-group">
            <label for="format">Format:</label>
            <input type="text" class="form-control" id="format" name="format" required>
        </div>
        <div class="form-group">
            <label for="screen">Screen:</label>
            <input type="text" class="form-control" id="screen" name="screen" required>
        </div>
        <div class="form-group">
            <label for="showtime">Showtime:</label>
            <input type="time" class="form-control" id="showtime" name="showtime" required>
        </div>
        <div class="form-group">
            <label for="gold_price">Gold Price:</label>
            <input type="number" class="form-control" id="gold_price" name="gold_price" required>
        </div>
        <div class="form-group">
            <label for="platinum_price">Platinum Price:</label>
            <input type="number" class="form-control" id="platinum_price" name="platinum_price" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Save Showtime</button>
    </form>
</div>
