<?php 
include 'header.php';
include 'db.php';
include 'ft.php';

 ?>

<?php 

if (isset($_GET['id'])) {
	
	$id = $_GET['id'];
	$genrename = $_GET['genrename'];
	$catid = $_GET['catid'];
	$genreid = $_GET['genreid'];

	if (isset($_POST['submit'])) {
		
		$genrename1 = $_POST['genrename'];
		$cat_id = $_POST['cat_id'];
		$genreid1 = $_POST['genre_id'];

		$query = " UPDATE `genre` SET `genre_name`='$genrename1',`category_id`=$cat_id,`genreid`=$genreid1 WHERE id=$id ";
		$run = mysqli_query($con,$query);
		if ($run) {
			echo "<script>alert('Genre Successfully Updated.. :)');window.location.href='genrelist.php';</script>";
		}
		else{
			echo "<script>alert('Something Went Wrong');window.location.href='editgenre.php';</script>";
		}

	}


}
else{
	header('location:genrelist.php');
}


 ?>


 <div class="container">
 	<div class="head">
 		
 		<div class="jumbotron">
  <h1 class="display-4">Edit Genre</h1>
  <p class="lead">Edit Genre and also please mention their Category ID</p>
  <hr class="my-4">
  <form action="#" method="post">
  <div class="form-row">
    <div class="col-7">
      <input type="text" value="<?php echo$genrename; ?>" name="genrename" class="form-control" placeholder="Genre Name">
    </div>
    <div class="col">
      <input type="text" value="<?php echo$catid; ?>" name="cat_id" class="form-control" placeholder="Category ID ">
    </div>
 <div class="col">
      <input type="text" value="<?php echo$genreid; ?>" name="genre_id"  class="form-control" placeholder="Genre ID ">
    </div>
  </div>
  <br><br>
  <button class="btn btn-primary btn-lg" name="submit">Edit Genre</button>
</form>
</div>
 	</div>
 </div>