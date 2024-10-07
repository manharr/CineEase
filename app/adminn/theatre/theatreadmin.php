<?php 

include 'db.php';
include 'header.php';
include 'ft.php';

// Get the theatre_id from the session
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

function getTotal($query, $con) {
    $run = mysqli_query($con, $query);
    if ($run) {
        $row = mysqli_fetch_assoc($run);
        return $row['total'];
    }
    return 0;
}
?>
<html lang="en">
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f0f2f5;
            color: #333;
        }
        .content {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            padding: 20px;
        }
        .stats-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 49%;
            margin-bottom: 20px;
        }
        .stats-card h5 {
            font-size: 1.2rem;
            color: #555;
        }
        .stats-card p {
            font-size: 2.3rem;
            font-weight: bold;
            margin: 10px 0;
        }
        .stats-card .small-text {
            font-size: 0.9rem;
            color: #888;
        }
        .highlight {
            color: #28a745;
        }
        .highlight-danger {
            color: #dc3545;
        }
    </style>
</head>
<body>
<div class="content">

    <!-- Total Bookings This Week -->
    <div class="stats-card">
        <h5>Total Bookings This Week </h5>
        <?php
        $currentWeekQuery = "SELECT COUNT(*) as total FROM bookings WHERE theater = '$theatre_name' AND YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)";
        $currentWeekTotal = getTotal($currentWeekQuery, $con);

        $lastWeekQuery = "SELECT COUNT(*) as total FROM bookings WHERE theater = '$theatre_name' AND YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1) - 1";
        $lastWeekTotal = getTotal($lastWeekQuery, $con);

        // Calculate percentage change
        $weekChange = $lastWeekTotal ? (($currentWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100 : 0;
        $weekChangeFormatted = number_format($weekChange, 1) . '%';
    ?>
    <p><?php echo $currentWeekTotal; ?></p>
    <span class="small-text">Compared to last week: <span class="highlight"><?php echo ($weekChange >= 0 ? '+' : '') . $weekChangeFormatted; ?></span></span>
</div>

    <!-- Total Bookings This Month -->
    <div class="stats-card">
        <h5>Total Bookings This Month</h5>
        <?php
        $currentMonthQuery = "SELECT COUNT(*) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $currentMonthTotal = getTotal($currentMonthQuery, $con);

        $lastMonthQuery = "SELECT COUNT(*) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
        $lastMonthTotal = getTotal($lastMonthQuery, $con);

        // Calculate percentage change
        $monthChange = $lastMonthTotal ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : 0;
        $monthChangeFormatted = number_format($monthChange, 1) . '%';
    ?>
    <p><?php echo $currentMonthTotal; ?></p>
    <span class="small-text">Compared to last month: <span class="highlight"><?php echo ($monthChange >= 0 ? '+' : '') . $monthChangeFormatted; ?></span></span>
</div>
    <!-- Total Amount Wagered This Week -->
    <div class="stats-card">
        <h5>Total Revenue This Week</h5>
        <?php
        $currentWeekRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE theater = '$theatre_name' AND YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)";
        $currentWeekRevenue = getTotal($currentWeekRevenueQuery, $con);

        $lastWeekRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE theater = '$theatre_name' AND YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1) - 1";
        $lastWeekRevenue = getTotal($lastWeekRevenueQuery, $con);

        // Calculate percentage change
        $revenueWeekChange = $lastWeekRevenue ? (($currentWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100 : 0;
        $revenueWeekChangeFormatted = number_format($revenueWeekChange, 1) . '%';
    ?>
    <p class="">₹<?php echo number_format($currentWeekRevenue, 2); ?></p>
    <span class="small-text">Compared to last week: <span class="highlight-danger"><?php echo ($revenueWeekChange >= 0 ? '+' : '') . $revenueWeekChangeFormatted; ?></span></span>
</div>

    <!-- Total Amount Wagered This Month -->
    <div class="stats-card">
        <h5>Total Revenue This Month</h5>
        <?php
        $currentMonthRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $currentMonthRevenue = getTotal($currentMonthRevenueQuery, $con);

        $lastMonthRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
        $lastMonthRevenue = getTotal($lastMonthRevenueQuery, $con);

        // Calculate percentage change
        $revenueMonthChange = $lastMonthRevenue ? (($currentMonthRevenue - $lastMonthRevenue) / $lastMonthRevenue) * 100 : 0;
        $revenueMonthChangeFormatted = number_format($revenueMonthChange, 1) . '%';
    ?>
    <p>₹<?php echo number_format($currentMonthRevenue, 2); ?></p>
    <span class="small-text">Compared to last month: <span class="highlight"><?php echo ($revenueMonthChange >= 0 ? '+' : '') . $revenueMonthChangeFormatted; ?></span></span>
</div>

    <!-- Total Cancelled Bookings -->
    <div class="stats-card">
        <h5>Total Cancelled Bookings</h5>
        <p class="highlight-danger"><?php
            $query = "SELECT COUNT(*) as total FROM canbookings WHERE theater = '$theatre_name'";
            echo getTotal($query, $con);
        ?></p>
        <span class="small-text">Cancelled bookings over time</span>
    </div>

    <!-- Total Profit This Month (Platform Fee: 2 INR per booking) -->
    <div class="stats-card">
        <h5>Total Profit This Month</h5>
        <p class="highlight">₹<?php
            $query = "SELECT SUM(total_price) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
            $grossRevenue = getTotal($query, $con);
            
            $revenueSharingPercentage = 0.40;
            
            // Calculate theater's share from ticket sales
            $theaterShare = $grossRevenue * $revenueSharingPercentage;
            
            // Calculate total number of bookings for platform fee calculation
            $queryBookings = "SELECT COUNT(*) as total FROM bookings WHERE theater = '$theatre_name' AND MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
            $totalBookings = getTotal($queryBookings, $con);
            
            // Platform fee per booking
            $platformFeePerBooking = 2;
            
            // Calculate total platform fee revenue
            $platformFeeRevenue = $totalBookings * $platformFeePerBooking;
            
            // Calculate total profit including platform fees
            $totalProfit = $theaterShare + $platformFeeRevenue;
            
            // Display the total profit
            echo number_format($totalProfit, 2); // Format for currency
        ?></p>
        <span class="small-text">Including platform fee and theater's share</span>
    </div>

</div>
</body>
</html>
