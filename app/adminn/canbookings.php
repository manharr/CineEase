<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Cancelled Bookings On CineEase</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"></li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <a class="btn btn-primary" href="bookinglist.php">Back to Bookings</a>
      </ul>
    </div>
  </nav>
</div>

<div class="container">
  <hr>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Customer Name</th>
        <th scope="col">Movie Name</th>
        <th scope="col">Show Time</th>
        <th scope="col">Screen</th>
        <th scope="col">Movie Date</th>
        <th scope="col">Booking Date</th>
        <th scope="col">Seats</th>
        <th scope="col">Theatre</th>
        <th scope="col">Total Amount</th>
        <th scope="col">Action</th>
      </tr>
    </thead>
    <?php 
    // Fetching all cancelled bookings
    $query = "
      SELECT id, firebase_uid, movie_id, showtime_id, screen, movie_date, booking_date, seats, theater, total_price
      FROM canbookings";
      
    $run = mysqli_query($con, $query);
    if ($run) {
      while ($row = mysqli_fetch_assoc($run)) {
    ?>
    <tbody>
      <tr>
        <th scope="row"><?php echo $row['id']; ?></th>
        <td><?php echo $row['firebase_uid']; ?></td>
        <td><?php 
          // Fetch movie name
          $movieQuery = "SELECT mv_name FROM movie WHERE id = " . intval($row['movie_id']);
          $movieResult = mysqli_query($con, $movieQuery);
          $movieRow = mysqli_fetch_assoc($movieResult);
          echo $movieRow['mv_name'];
        ?></td>
        <td><?php 
          // Fetch showtime
          $showtimeQuery = "SELECT movie_timings FROM show_timings WHERE id = " . intval($row['showtime_id']);
          $showtimeResult = mysqli_query($con, $showtimeQuery);
          $showtimeRow = mysqli_fetch_assoc($showtimeResult);
          echo $showtimeRow['movie_timings'];
        ?></td>
        <td><?php echo $row['screen']; ?></td>
        <td><?php echo $row['movie_date']; ?></td>
        <td><?php echo $row['booking_date']; ?></td>
        <td><?php echo $row['seats']; ?></td>
        <td><?php echo $row['theater']; ?></td>
        <td><?php echo $row['total_price']; ?></td>
        <td>
          <div class="btn-group" role="group" aria-label="Booking Actions">
            <a href="deletecanbooking.php?deleteid=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a> &nbsp;
            <button type="button" class="btn btn-dark" disabled>Cancelled</button>
          </div>
        </td>
      </tr>
    </tbody>
    <?php
      }
    }
    ?>
  </table>
</div>
