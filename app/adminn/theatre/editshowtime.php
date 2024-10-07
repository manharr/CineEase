<?php
include 'header.php';
include 'ft.php';
include 'db.php';

// Get the theatre ID from the session for the logged-in theatre admin
$theatre_id = $_SESSION['theatre_id'];

// Check if 'show_id' and 'movie_id' are set in the URL
if (isset($_GET['show_id']) && isset($_GET['movie_id'])) {
    $show_id = $_GET['show_id'];
    $movie_id = $_GET['movie_id'];

    // Fetch the existing showtime details for the given show ID and theatre admin's theatre
    $query = "SELECT st.*, t.theatre_name, m.mv_name 
              FROM show_timings st
              JOIN theatre t ON st.theatre_id = t.id
              JOIN movie m ON st.movie_id = m.id
              WHERE st.id = ? AND st.theatre_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $show_id, $theatre_id); // Bind show_id and theatre_id
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        $showtime = mysqli_fetch_assoc($result);
    } else {
        echo "<div class='container'><h3>Showtime not found or you do not have permission to edit this showtime.</h3></div>";
        exit(); // Exit if the showtime is not found or doesn't belong to the logged-in theatre admin
    }
} else {
    echo "<div class='container'><h3>Show ID or Movie ID not specified.</h3></div>";
    exit(); // Exit if no show ID or movie ID is specified
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Retrieve form data
    $movie_language = $_POST['language'];
    $movie_format = $_POST['format'];
    $movie_showtime = $_POST['showtime'];
    $screen = $_POST['screen'];
    $gold = $_POST['gold_price'];
    $plat = $_POST['platinum_price'];

    // Update query for showtimes
    $query = "UPDATE `show_timings` 
              SET `movie_language` = ?, `movie_format` = ?, `movie_timings` = ?, `gold_price` = ?, `platinum_price` = ?, `screen` = ?
              WHERE `id` = ? AND `theatre_id` = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ssssssii', $movie_language, $movie_format, $movie_showtime, $gold, $plat, $screen, $show_id, $theatre_id);
    $run = mysqli_stmt_execute($stmt);

    if ($run) {
        echo "<script>alert('Showtime successfully updated.'); window.location.href='viewshow.php?id=" . htmlspecialchars($movie_id) . "';</script>";
    } else {
        echo "<script>alert('There was a problem while updating the showtime.'); window.location.href='editshowtime.php?show_id=" . htmlspecialchars($show_id) . "&movie_id=" . htmlspecialchars($movie_id) . "';</script>";
    }
    exit(); // Prevent further output
}
?>

<div class="container">
    <h2>Edit Showtime for <?php echo htmlspecialchars($showtime['mv_name']); ?> at <?php echo htmlspecialchars($showtime['theatre_name']); ?></h2>
    <form method="post" action="editshowtime.php?show_id=<?php echo htmlspecialchars($show_id); ?>&movie_id=<?php echo htmlspecialchars($movie_id); ?>">
        <div class="form-group">
            <label for="theatre">Theatre:</label>
            <input type="text" class="form-control" id="theatre" name="theatre" value="<?php echo htmlspecialchars($showtime['theatre_name']); ?>" readonly>
            <!-- The theatre field is locked and shown as readonly -->
        </div>
        <div class="form-group">
            <label for="language">Language:</label>
            <input type="text" class="form-control" id="language" name="language" value="<?php echo htmlspecialchars($showtime['movie_language']); ?>" required>
        </div>
        <div class="form-group">
            <label for="format">Format:</label>
            <input type="text" class="form-control" id="format" name="format" value="<?php echo htmlspecialchars($showtime['movie_format']); ?>" required>
        </div>
        <div class="form-group">
            <label for="screen">Screen:</label>
            <input type="text" class="form-control" id="screen" name="screen" value="<?php echo htmlspecialchars($showtime['screen']); ?>" required>
        </div>
        <div class="form-group">
            <label for="showtime">Showtime:</label>
            <input type="time" class="form-control" id="showtime" name="showtime" value="<?php echo htmlspecialchars($showtime['movie_timings']); ?>" required>
        </div>
        <div class="form-group">
            <label for="gold_price">Gold Price:</label>
            <input type="number" class="form-control" id="gold_price" name="gold_price" value="<?php echo htmlspecialchars($showtime['gold_price']); ?>" required>
        </div>
        <div class="form-group">
            <label for="platinum_price">Platinum Price:</label>
            <input type="number" class="form-control" id="platinum_price" name="platinum_price" value="<?php echo htmlspecialchars($showtime['platinum_price']); ?>" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Update Showtime</button>
    </form>
</div>
