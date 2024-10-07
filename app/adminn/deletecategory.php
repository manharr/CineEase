<?php 
include 'db.php';
include 'header.php';
include 'ft.php';
$id = $_GET['deleteid'];

$query = "DELETE FROM `category` WHERE id =$id";

$run = mysqli_query($con,$query);

if ($run) {
	echo  "<script> window.location.href='categorylist.php';</script>";}
else{
	echo "<script>alert('somthing went wrong!!'); window.location.href='categorylist.php';</script>";
}

 ?>
