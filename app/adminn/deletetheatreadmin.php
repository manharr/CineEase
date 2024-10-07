<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

// Get the admin ID from the URL
$id = $_GET['id'];

// Prepare and execute the delete query
$query = "DELETE FROM admin_theatre WHERE admin_id = ?";
$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'i', $id);
$run = mysqli_stmt_execute($stmt);

// Check if the deletion was successful
if ($run) {
    echo "<script>alert('Theatre Admin Has Been Deleted!!'); window.location.href='adminlist.php';</script>";
} else {
    echo "<script>alert('Something went wrong!!'); window.location.href='adminlist.php';</script>";
}
?>
