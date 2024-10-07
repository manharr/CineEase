<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];
    $query = "DELETE FROM `slider` WHERE id = $id";
    $run = mysqli_query($con, $query);

    if ($run) {
        echo "<script>alert('Image Has Been Deleted!!'); window.location.href='sliderlist.php';</script>";
    } else {
        echo "<script>alert('Something went wrong!!'); window.location.href='sliderlist.php';</script>";
    }
} else {
    header('location:sliderlist.php');
}
?>
