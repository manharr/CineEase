<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

// Fetch movies from the database
$query = "SELECT id, mv_name FROM movie";
$result = mysqli_query($con, $query);
if (!$result) {
    die("Query failed: " . mysqli_error($con));
}
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#"><strong> Shows On CineEase</strong></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
      </ul>

      <ul class="navbar-nav ml-auto">
      <a class="btn btn-warning text-light" href="liveshows.php">LIVE SHOWS</a>
      </ul>
    </div>
  </nav>
</div>

<div class="container">
  <hr>
  <h2>Manage Showtimes</h2>
  <table class="table table-striped">
    <thead>
      <tr>
        <th scope="col">#</th>
        <th scope="col">Movie Name</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $counter = 1;
      while($row = mysqli_fetch_assoc($result)): ?>
        <tr>
          <th scope="row"><?php echo $counter++; ?></th>
          <td><?php echo htmlspecialchars($row['mv_name']); ?></td>
          <td>
            <!-- Link to add showtime -->
            <a href="addshow.php?id=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Add Showtime</a>
            <!-- Link to view showtime -->
            <a href="viewshow.php?id=<?php echo $row['id']; ?>" class="btn btn-info btn-sm">View Showtime</a>
          </td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>
</div>
