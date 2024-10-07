<?php 

include 'db.php';
include 'header.php';
include 'ft.php';
?>

<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Slider Images</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ml-auto">
        <li class="nav-item">
          <a class="btn btn-warning text-light" href="addsliderimg.php">Add Images</a>
        </li>
      </ul>
      <ul class="navbar-nav ml-auto">
        <form class="form-inline" method="post" action="searchcategory.php">
          <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search">
          <button class="btn btn-success" name="submit" type="submit">Search</button>
        </form>
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
        <th scope="col">Images</th>
        <th scope="col">Actions</th>
      </tr>
    </thead>
    <tbody>
    <?php 
    $query = "SELECT * FROM slider";
    $run = mysqli_query($con, $query);
    if ($run) {
      while ($row = mysqli_fetch_assoc($run)) {
    ?>
      <tr>
        <th scope="row"><?php echo $row['id']; ?></th>
        <td>
          <img src="<?php echo $row['imgz']; ?>" alt="Slider Image" style="width: 100px; height: auto;">
        </td>
        <td>
          <a href="deleteslider.php?deleteid=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
        </td>
      </tr>
    <?php
      }
    }
    ?>
    </tbody>
  </table>
</div>
