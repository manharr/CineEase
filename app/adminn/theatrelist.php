<?php 
include 'db.php';
include 'header.php';
include 'ft.php';
?>

<style>
  .btn-group a {
    margin-right: 5px; /* Adds space between buttons */
}

  </style>
<div class="container">
  <nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="#">Theatres</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarNav">
     <ul class="navbar-nav ml-auto">
            <li class="nav-item">
        <a class="btn btn-warning text-light" href="addtheatre.php">Add a Theatre</a>
      </li>
     </ul>
    <ul class="navbar-nav ml-auto">

     <form class="form-inline" method="post" action="searchcategory.php">
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
      <th scope="col">Theatre Name</th>
      <th scope="col">Address</th>
      <th scope="col">Sreens</th>


    </tr>
  </thead>
  <?php 

$query = "SELECT * FROM theatre";
$run = mysqli_query($con,$query);
if ($run) {
	while ($row = mysqli_fetch_assoc($run)) {

?>
  
  <tbody>
    <tr>
      <th scope="row"><?php echo $row['id']; ?></th>
      <td><?php echo $row['theatre_name']; ?></td>
      <td><?php echo $row['address']; ?> 
      <td><?php echo $row['screens']; ?> 
      </td>
               
     

      <td>
        &nbsp;
      	<div class="btn-group" role="group">
              
              <a href="edittheatre.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Edit</a>
              <a href="deletetheatre.php?deleteid=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
            </div>

    </tr>

  </tbody>
  <?php
	}
}

   ?>
</table>
</div>
