<?php
session_start();

// Check if the user is a main admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'main_admin') {
    // Destroy main admin session data
    session_unset();
    session_destroy();
    header('Location:login.php');
    exit();
} else {
    // Redirect to login if not a main admin
    header('Location:login.php');
    exit();
}
?>
