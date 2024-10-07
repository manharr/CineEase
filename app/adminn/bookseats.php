<?php
include 'db.php';
include 'header.php';
include 'ft.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select Movie Show</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Select Movie Show Details</h2>

    <!-- Theater Dropdown -->
    <div class="form-group">
        <label for="theatre">Select Theatre:</label>
        <select id="theatre" name="theatre" class="form-control">
            <option value="">Select Theatre</option>
            <?php
            $theatres = mysqli_query($con, "SELECT id, theatre_name FROM theatre");
            while ($theatre = mysqli_fetch_assoc($theatres)) {
                echo '<option value="' . $theatre['id'] . '">' . $theatre['theatre_name'] . '</option>';
            }
            ?>
        </select>
    </div>

    <!-- Movie Dropdown (Initially Empty) -->
    <div class="form-group">
        <label for="movie">Select Movie:</label>
        <select id="movie" name="movie" class="form-control" disabled>
            <option value="">Select Movie</option>
        </select>
    </div>

    <!-- Available Dates Dropdown (Initially Empty) -->
    <div class="form-group">
        <label for="date">Select Date:</label>
        <select id="date" name="date" class="form-control" disabled>
            <option value="">Select Date</option>
        </select>
    </div>

    <!-- Movie Timings Dropdown (Initially Empty) -->
    <div class="form-group">
        <label for="show_timings">Select Movie Timings:</label>
        <select id="show_timings" name="show_timings" class="form-control" disabled>
            <option value="">Select Timings</option>
        </select>
    </div>

    <div class="form-group">
        <a href="seats.php?movie_id=&show_time_id=&theatre_id=" id="proceed_link">
            <button id="proceed" class="btn btn-primary" disabled>Proceed</button>
        </a>
    </div>

    <div class="form-group">
        <a href="seatshistory.php">
            <button class="btn btn-secondary">Admin Booking History</button>
        </a>
    </div>
</div>
<script>
$(document).ready(function() {
    // When a theatre is selected, fetch corresponding movies
    $('#theatre').change(function() {
        var theatreId = $(this).val();
        if (theatreId) {
            $.ajax({
                url: 'get_movies.php',
                type: 'POST',
                data: {theatre_id: theatreId},
                success: function(response) {
                    $('#movie').html(response);
                    $('#movie').prop('disabled', false);
                }
            });
        } else {
            $('#movie').html('<option value="">Select Movie</option>');
            $('#movie').prop('disabled', true);
            $('#date').html('<option value="">Select Date</option>');
            $('#date').prop('disabled', true);
            $('#show_timings').html('<option value="">Select Timings</option>');
            $('#show_timings').prop('disabled', true);
            $('#proceed').prop('disabled', true);
        }
    });

    // When a movie is selected, fetch available dates
    $('#movie').change(function() {
        var movieId = $(this).val();
        var theatreId = $('#theatre').val();
        if (movieId) {
            $.ajax({
                url: 'get_dates.php',
                type: 'POST',
                data: {movie_id: movieId, theatre_id: theatreId},
                success: function(response) {
                    $('#date').html(response);
                    $('#date').prop('disabled', false);
                }
            });
        } else {
            $('#date').html('<option value="">Select Date</option>');
            $('#date').prop('disabled', true);
            $('#show_timings').html('<option value="">Select Timings</option>');
            $('#show_timings').prop('disabled', true);
            $('#proceed').prop('disabled', true);
        }
    });

    // When a date is selected, fetch the show timings
    // When both movie and theatre are selected, fetch the show timings
$('#theatre, #movie').change(function() {
    var movieId = $('#movie').val();
    var theatreId = $('#theatre').val();

    if (movieId && theatreId) {
        $.ajax({
            url: 'get_timings.php',
            type: 'POST',
            data: {
                movie_id: movieId,
                theatre_id: theatreId
            },
            success: function(response) {
                $('#show_timings').html(response);
                $('#show_timings').prop('disabled', false);
                $('#proceed').prop('disabled', false);
            }
        });
    } else {
        $('#show_timings').html('<option value="">Select Timings</option>');
        $('#show_timings').prop('disabled', true);
    }
});
// When a show timing is selected, update the proceed link
$('#show_timings, #date').change(function() {
        var showTimeId = $('#show_timings').val(); // Get the selected show time ID
        var movieId = $('#movie').val(); // Get the selected movie ID
        var theatreId = $('#theatre').val(); // Get the selected theatre ID
        var selectedDate = $('#date').val(); // Get the selected date

        // Check if movie, theatre, show timings, and date are all selected
        if (showTimeId && movieId && theatreId && selectedDate) {
            // Update the href of the proceed link with movie_id, show_time_id, theatre_id, and date
            $('#proceed_link').attr('href', 'seats.php?movie_id=' + movieId + '&show_time_id=' + showTimeId + '&theatre_id=' + theatreId + '&date=' + selectedDate);
            $('#proceed').prop('disabled', false); // Enable the proceed button
        } else {
            // Reset the link if the conditions are not met
            $('#proceed_link').attr('href', 'seats.php?movie_id=&show_time_id=&theatre_id=&date=');
            $('#proceed').prop('disabled', true); // Disable the proceed button
        }
    });

});
</script>

</body>
</html>