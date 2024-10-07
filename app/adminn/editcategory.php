<?php 

include 'header.php';
include 'ft.php';
include 'db.php';
 ?>


<?php
if (isset($_GET['id'])) {
	$id =$_GET['id'];
	$catname = $_GET['catname'];
	$fk = $_GET['forkey'];

	if (isset($_POST['submit'])) {
		$cname = $_POST['categoryname'];
		$frky = $_POST['frky'];
		$pid = $_POST['pid'];

		$query = "UPDATE `category` SET `id`=$pid,`category_id`=$frky,`category_name`='$cname' WHERE id=$id";
		$run = mysqli_query($con,$query);
		if ($run) {
			echo "<script>alert('Category Successfully Updated.. :)');window.location.href='categorylist.php';</script>";
		}
		else{
			echo "<script>alert('Something Went Wrong');window.location.href='editcategory.php';</script>";
		}

	}

}
else{
	header('location:categorylist.php');
}
?>


<div class="container">
	<div class="head" style="text-align: center;padding: 10px 0px 10px 0px;">
		<h1>Edit Catgegory</h1>
	</div>
	<hr>
	<form action="#" method="post">
  <div class="form-row">
    <div class="col-7">
    	<small>Category Name</small>
      <input type="text" class="form-control" name="categoryname" value="<?php echo$catname; ?>" placeholder="Category Name">
    </div>
    <div class="col">
    	<small>Foriegn Key</small>
      <input type="text" class="form-control" name="frky" value="<?php echo$fk; ?>" placeholder="Foriegn Key">
    </div>
    <div class="col">
    	<small>Primary ID</small>
      <input type="text" class="form-control" name="pid" value="<?php echo$id; ?>" placeholder="Primary ID">
    </div>
  </div>
  <br>
  <br>
  <input type="submit" class="btn btn-outline-success btn-lg" name="submit">
</form>
</div>


