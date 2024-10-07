<?php 
include 'db.php';
include 'ft.php';
session_start();

if (!isset($_SESSION['loginsuccesfull']) || $_SESSION['loginsuccesfull'] != 1) {
    echo "<script>alert('You Are not Logged in');window.location.href='login.php';</script>";
    exit();
}

if ($_SESSION['user_role'] != 'main_admin') {
    echo "<script>alert('Access Denied');window.location.href='login.php';</script>";
    exit();
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">

<head>

    <title>Main Admin - CineEase</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        body {
            margin: 0;
        }
        .sidebar {
            margin: 0;
            padding: 0;
            width: 170px;
            background-color: #f1f1f1;
            position: fixed;
            height: 100%;
            font-size:15px;
            overflow-y: auto; 
            overflow-x: hidden; 
        }
        .sidebar a {
            display: block;
            color: black;
            padding: 16px;
            text-decoration: none;
        }
        .sidebar a.active {
            background-color: #140d14;
            color: white;
        }
        .sidebar a:hover:not(.active) {
            background-color: #140d14;
            color: white;
        }
        div.content {
            margin-left: 200px;
            padding: 1px 16px;
            height: calc(100vh - 56px); 
        }
        @media screen and (max-width: 700px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
            }
            .sidebar a { float: left; }
            div.content { margin-left: 0; }
        }

        @media screen and (max-width: 400px) {
            .sidebar a {
                text-align: center;
                float: none;
            }
        }
    </style>
</head>
<body>

<!-- nav -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <a class="navbar-brand" href="#">Hello, <?php echo htmlspecialchars($_SESSION['user']); ?></a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item <?php echo ($current_page == 'stats.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="stats.php">Home</a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'registeradmin.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="registeradmin.php">Register Admin</a>
            </li>
            <li class="nav-item <?php echo ($current_page == 'adminlist.php') ? 'active' : ''; ?>">
                <a class="nav-link" href="adminlist.php">Admin List</a>
            </li>
            <li class="nav-item">
                <a class="btn btn-outline-danger" href="logout.php">Logout</a>
            </li>
        </ul>
    </div>
</nav>
<!-- navend -->

<div class="sidebar">
    <a class="<?php echo ($current_page == 'stats.php') ? 'active' : ''; ?>" href="stats.php">Home</a>
    <a class="<?php echo ($current_page == 'movielist.php') ? 'active' : ''; ?>" href="movielist.php">Movies</a>
    <a class="<?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" href="contact.php">Contact</a>
    <a class="<?php echo ($current_page == 'adminlist.php') ? 'active' : ''; ?>" href="adminlist.php">Admins</a>
    <a class="<?php echo ($current_page == 'categorylist.php') ? 'active' : ''; ?>" href="categorylist.php">Categories</a>
    <a class="<?php echo ($current_page == 'genrelist.php') ? 'active' : ''; ?>" href="genrelist.php">Genre</a>
    <a class="<?php echo ($current_page == 'theatrelist.php') ? 'active' : ''; ?>" href="theatrelist.php">Theatres</a>
    <a class="<?php echo ($current_page == 'sliderlist.php') ? 'active' : ''; ?>" href="sliderlist.php">Image Slider</a>
    <a class="<?php echo ($current_page == 'showlist.php') ? 'active' : ''; ?>" href="showlist.php">Shows</a>
    <a class="<?php echo ($current_page == 'bookinglist.php') ? 'active' : ''; ?>" href="bookinglist.php">Bookings</a>
    <a class="<?php echo ($current_page == 'bookseats.php') ? 'active' : ''; ?>" href="bookseats.php">Reserve Seats</a>
</div>
