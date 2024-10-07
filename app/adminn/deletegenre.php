<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
if (isset($_GET['id'])) {

$id = $_GET['id'];
$query = "DELETE FROM `genre` WHERE id = $id";
$run = mysqli_query($con,$query);
if ($run) {
	echo "<script>alert('Genre Has Been Deleted!!');window.location.href='genrelist.php';</script>";
}
else{
	echo "<script>alert('somthing went wrong!!'); window.location.href='genrelist.php';</script>";
	}
}
else{
	echo  "<script> window.location.href='genrelist.php';</script>";}


 ?>