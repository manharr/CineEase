<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

if (isset($_GET['deleteid'])) {
    $id = $_GET['deleteid'];
    $query = "DELETE FROM `theatre` WHERE id = $id";
    $run = mysqli_query($con, $query);

    if ($run) {
        echo "<script>alert('Theatre Has Been Deleted!!'); window.location.href='theatrelist.php';</script>";
    } else {
        echo "<script>alert('Something went wrong!!'); window.location.href='theatrelist.php';</script>";
    }
} else {
    header('location:theatrelist.php');
}
?>
