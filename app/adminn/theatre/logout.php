<?php
session_start();

// Check if the user is a theatre admin
if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'theatre_admin') {
    // Destroy theatre admin session data
    session_unset();
    session_destroy();
    header('Location: ./../login.php');
    exit();
} else {
    // Redirect to login if not a theatre admin
    header('Location: ./../login.php');
    exit();
}
?>
