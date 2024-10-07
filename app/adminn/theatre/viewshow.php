<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

// Get the theatre ID from the session for the logged-in theatre admin
$theatre_id = $_SESSION['theatre_id'];

// Check if 'id' is set in the URL
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

    // Fetch showtimes for the specified movie ID and the logged-in theatre admin's theatre
    $query = "SELECT st.*, t.theatre_name 
              FROM show_timings st
              JOIN theatre t ON st.theatre_id = t.id
              WHERE st.movie_id = ? AND st.theatre_id = ?";
    $stmt = mysqli_prepare($con, $query);
    mysqli_stmt_bind_param($stmt, 'ii', $movie_id, $theatre_id); // Bind both movie ID and theatre ID
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if ($result && mysqli_num_rows($result) > 0) {
        ?>

        <div class="container">
            <h2>Showtimes for <?php echo htmlspecialchars($movie['mv_name']); ?> at <?php echo htmlspecialchars($theatre['theatre_name']); ?></h2>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Theatre</th>
                        <th scope="col">Language</th>
                        <th scope="col">Format</th>
                        <th scope="col">Showtime</th>
                        <th scope="col">Gold Price</th>
                        <th scope="col">Platinum Price</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['theatre_name']); ?></td>
                            <td><?php echo htmlspecialchars($row['movie_language']); ?></td>
                            <td><?php echo htmlspecialchars($row['movie_format']); ?></td>
                            <td><?php echo htmlspecialchars($row['movie_timings']); ?></td>
                            <td><?php echo htmlspecialchars($row['gold_price']); ?></td>
                            <td><?php echo htmlspecialchars($row['platinum_price']); ?></td>
                            <td>
                                <!-- Edit Showtime Link -->
                                <a href="editshowtime.php?show_id=<?php echo $row['id']; ?>&movie_id=<?php echo $movie_id; ?>" class="btn btn-warning btn-sm">Edit Showtime</a>
                                
                                <!-- Delete Showtime Link -->
                                <a href="deleteshowtime.php?show_id=<?php echo $row['id']; ?>&movie_id=<?php echo $movie_id; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this showtime?');">Delete Showtime</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
        <?php
    } else {
        echo "<div class='container'><h3>No showtimes found for this movie at your theatre.</h3></div>";
    }
} else {
    echo "<div class='container'><h3>Movie ID not specified.</h3></div>";
}
?>
