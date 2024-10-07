<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

// Get the theatre_id from the session
$theatre_id = $_SESSION['theatre_id'];

// Fetch the theatre name from the database
$query = "SELECT theatre_name FROM theatre WHERE id = '$theatre_id'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $theatre = mysqli_fetch_assoc($result);
    $theatre_name = $theatre['theatre_name'];
} else {
    $theatre_name = 'Unknown Theatre'; // Default value if not found
}
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Booking Management Panel</a> 

    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item"></li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <a class="btn btn-warning text-light" href="canbookings.php">Cancelled Bookings</a>
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
    // Fetch bookings only for this theatre
    $query = "
      SELECT b.id, b.firebase_uid, m.mv_name AS movie_name, s.movie_timings AS show_time, 
             b.screen, b.movie_date, b.booking_date, b.theater, b.total_price, b.status, b.booking_uid,
             GROUP_CONCAT(CONCAT(seat.seat_row, seat.seat_number) ORDER BY seat.seat_row, seat.seat_number SEPARATOR ', ') AS seats
      FROM bookings b
      JOIN movie m ON b.movie_id = m.id
      JOIN show_timings s ON b.showtime_id = s.id
      JOIN seats seat ON FIND_IN_SET(seat.id, b.seats) > 0
      WHERE b.theater = '$theatre_name'
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
        <td><?php echo $row['seats']; ?></td>
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
    }
    ?>
  </table>
</div>
