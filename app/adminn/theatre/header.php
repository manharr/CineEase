<?php
include 'db.php';
include 'ft.php';
session_start();

// Check if the user is logged in and has the correct role
if (!isset($_SESSION['loginsuccesfull']) || $_SESSION['loginsuccesfull'] != 1) {
    echo "<script>alert('You Are not Logged in');window.location.href='./../login.php';</script>";
    exit();
}

if ($_SESSION['user_role'] != 'theatre_admin') {
    echo "<script>alert('Access Denied');window.location.href='./../login.php';</script>";
    exit();
}

// For theatre admin, ensure theatre_id is set
if ($_SESSION['user_role'] == 'theatre_admin' && !isset($_SESSION['theatre_id'])) {
    echo "<script>alert('Theatre ID not found');window.location.href='./../login.php';</script>";
    exit();
}
$theatre_id = $_SESSION['theatre_id'];

// Fetch the theatre name from the database
$query = "SELECT theatre_name FROM theatre WHERE id = '$theatre_id'";
$result = mysqli_query($con, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $theatre = mysqli_fetch_assoc($result);
    $theatre_name = $theatre['theatre_name'];
} else {
    $theatre_name = 'Unknown Theatre'; // Default value if not found
}
// Determine the current page
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<link rel="icon" href="/movie-booking/app/views/img/fav.png" sizes="16x16" type="image/png">

<head>
    <title><?php echo $theatre_name  ?> - CineEase</title>
    <!-- Latest compiled and minified CSS -->

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
            overflow-y: auto; /* Make the sidebar scrollable */
            overflow-x: hidden; /* Prevent horizontal scrolling */
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
            height: calc(100vh - 56px); /* Adjust the height if needed */
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
        <a class="navbar-brand" href="#"><?php echo $theatre_name  ?> - Admin <?php echo htmlspecialchars($_SESSION['user']); ?></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item <?php echo ($current_page == 'theatreadmin.php') ? 'active' : ''; ?>">
                    <a class="nav-link" href="theatreadmin.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-danger" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- navend -->

    <div class="sidebar">
        <a class="<?php echo ($current_page == 'theatreadmin.php') ? 'active' : ''; ?>" href="theatreadmin.php">Home</a>
        <a class="<?php echo ($current_page == 'showlist.php') ? 'active' : ''; ?>" href="showlist.php">Shows</a>
        <a class="<?php echo ($current_page == 'bookinglist.php') ? 'active' : ''; ?>" href="bookinglist.php">Bookings</a>
    </div>
