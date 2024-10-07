<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Bookings On CineEase</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"></li>
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
        <th scope="col">Booking ID</th>
        <th scope="col">User</th>
        <th scope="col">Movie Name</th>
        <th scope="col">Show Time</th>
        <th scope="col">Screen</th>
        <th scope="col">Movie Date</th>
        <th scope="col">Booking Date</th>
        <th scope="col">Seats</th>
        <th scope="col">Theatre</th>
        <th scope="col">Total Amount</th>
        <th scope="col">Status</th>
        <th scope="col">Action</th>
      </tr>
    </thead>

    <?php 
    // Fetching all bookings for the ADMIN user
    $query = "
      SELECT b.id, b.firebase_uid, m.mv_name AS movie_name, s.movie_timings AS show_time, 
             b.screen, b.movie_date, b.booking_date, b.theater, b.total_price, b.status, b.booking_uid,
             GROUP_CONCAT(CONCAT(seat.seat_row, seat.seat_number) ORDER BY seat.seat_row, seat.seat_number SEPARATOR ', ') AS seats
      FROM bookings b
      JOIN movie m ON b.movie_id = m.id
      JOIN show_timings s ON b.showtime_id = s.id
      JOIN seats seat ON FIND_IN_SET(seat.id, b.seats) > 0
      WHERE b.firebase_uid = 'ADMIN'  -- Filter for bookings made by ADMIN
      GROUP BY b.id";
      
    $run = mysqli_query($con, $query);
    if ($run) {
      while ($row = mysqli_fetch_assoc($run)) {
    ?>
    <tbody>
      <tr>
        <th scope="row"><?php echo $row['id']; ?></th>
        <td><?php echo $row['booking_uid']; ?></td>
        <td><?php echo $row['firebase_uid']; ?></td>
        <td><?php echo $row['movie_name']; ?></td>
        <td><?php echo $row['show_time']; ?></td>
        <td><?php echo $row['screen']; ?></td>
        <td><?php echo $row['movie_date']; ?></td>
        <td><?php echo $row['booking_date']; ?></td>
        <td><?php echo $row['seats']; ?></td> <!-- Display seats as G5, A3, etc. -->
        <td><?php echo $row['theater']; ?></td>
        <td><?php echo $row['total_price']; ?></td>
        <td><?php echo $row['status']; ?></td>
        <td>
          <a href="deletebooking.php?deleteid=<?php echo $row['id']; ?>" class="btn btn-danger">Cancel</a>
        </td>
      </tr>
    </tbody>
    <?php
      }
    } else {
      echo "<tbody><tr><td colspan='13'>No bookings found for ADMIN.</td></tr></tbody>";
    }
    ?>
  </table>
</div>

