<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

$id = $_GET['unameid'];
$query = "DELETE FROM `admin` WHERE id = $id";
$run = mysqli_query($con,$query);
if ($run) {
	echo "<script>alert('Admin Has Been Deleted!!');window.location.href='adminlist.php';</script>";
}
else{
	echo "<script>alert('somthing went wrong!!'); window.location.href='adminlist.php';</script>";
	}
 ?>
