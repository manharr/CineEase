<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

function getTotal($query, $con) {
    $run = mysqli_query($con, $query);
    if ($run) {
        $row = mysqli_fetch_assoc($run);
        return $row['total'] ?? 0; // Return 0 if the result is null
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

<div class="stats-card">
    <h5>Total Bookings This Week</h5>
    <?php
        // Query for current week total bookings
        $currentWeekQuery = "SELECT COUNT(*) as total FROM bookings WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)";
        $currentWeekTotal = getTotal($currentWeekQuery, $con);

        // Query for last week total bookings
        $lastWeekQuery = "SELECT COUNT(*) as total FROM bookings WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1) - 1";
        $lastWeekTotal = getTotal($lastWeekQuery, $con);

        // Calculate percentage change
        $weekChange = $lastWeekTotal ? (($currentWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100 : 0;
        $weekChangeFormatted = number_format($weekChange, 1) . '%';
    ?>
    <p><?php echo $currentWeekTotal; ?></p>
    <span class="small-text">Compared to last week: <span class="highlight"><?php echo ($weekChange >= 0 ? '+' : '') . $weekChangeFormatted; ?></span></span>
    <br> <!-- Adding a line break for spacing -->
    <span class="small-text">Last week's total bookings: <?php echo $lastWeekTotal; ?></span>
</div>

<!-- Total Bookings This Month -->
<div class="stats-card">
    <h5>Total Bookings This Month</h5>
    <?php
        $currentMonthQuery = "SELECT COUNT(*) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $currentMonthTotal = getTotal($currentMonthQuery, $con);

        $lastMonthQuery = "SELECT COUNT(*) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
        $lastMonthTotal = getTotal($lastMonthQuery, $con);

        // Calculate percentage change
        $monthChange = $lastMonthTotal ? (($currentMonthTotal - $lastMonthTotal) / $lastMonthTotal) * 100 : 0;
        $monthChangeFormatted = number_format($monthChange, 1) . '%';
    ?>
    <p><?php echo $currentMonthTotal; ?></p>
    <span class="small-text">Compared to last month: <span class="highlight"><?php echo ($monthChange >= 0 ? '+' : '') . $monthChangeFormatted; ?></span></span>
    <br>
    <span class="small-text">Last month's total bookings: <?php echo $lastMonthTotal; ?></span>
</div>


<!-- Total Revenue This Week -->
<div class="stats-card">
    <h5>Total Revenue This Week</h5>
    <?php
        // Total Revenue This Week
        $currentWeekRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1)";
        $currentWeekRevenue = getTotal($currentWeekRevenueQuery, $con);

        $lastWeekRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE YEARWEEK(booking_date, 1) = YEARWEEK(CURDATE(), 1) - 1";
        $lastWeekRevenue = getTotal($lastWeekRevenueQuery, $con);

        // Handle null values with default 0
        $currentWeekRevenue = $currentWeekRevenue ?? 0;
        $lastWeekRevenue = $lastWeekRevenue ?? 0;

        // Calculate percentage change
        $revenueWeekChange = $lastWeekRevenue ? (($currentWeekRevenue - $lastWeekRevenue) / $lastWeekRevenue) * 100 : 0;
        $revenueWeekChangeFormatted = number_format($revenueWeekChange, 1) . '%';
        ?>
    <p class="">₹<?php echo number_format($currentWeekRevenue, 2); ?></p>

    <span class="small-text">Compared to last week: <span class="highlight"><?php echo ($revenueWeekChange >= 0 ? '+' : '') . $revenueWeekChangeFormatted; ?></span></span>
</div>

<!-- Total Revenue This Month -->
<div class="stats-card">
    <h5>Total Revenue This Month</h5>
    <?php
        $currentMonthRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $currentMonthRevenue = getTotal($currentMonthRevenueQuery, $con);

        $lastMonthRevenueQuery = "SELECT SUM(total_price) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
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
            $query = "SELECT COUNT(*) as total FROM canbookings";
            echo getTotal($query, $con);
        ?></p>
        <span class="small-text">Cancelled bookings over time</span>
</div>


<!-- Total profit  -->
<div class="stats-card">
    <h5>Total Profit This Month</h5>
    <p class="highlight">₹<?php
        // Current month profit calculation
        $queryCurrentMonthRevenue = "SELECT SUM(total_price) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $grossRevenueCurrentMonth = getTotal($queryCurrentMonthRevenue, $con);

        $revenueSharingPercentage = 0.40;
        $theaterShareCurrentMonth = $grossRevenueCurrentMonth * $revenueSharingPercentage;

        $queryCurrentMonthBookings = "SELECT COUNT(*) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) AND YEAR(booking_date) = YEAR(CURDATE())";
        $totalBookingsCurrentMonth = getTotal($queryCurrentMonthBookings, $con);

        $platformFeePerBooking = 2;
        $platformFeeRevenueCurrentMonth = $totalBookingsCurrentMonth * $platformFeePerBooking;

        // Total profit for the current month
        $totalProfitCurrentMonth = $theaterShareCurrentMonth + $platformFeeRevenueCurrentMonth;

        // Last month profit calculation
        $queryLastMonthRevenue = "SELECT SUM(total_price) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
        $grossRevenueLastMonth = getTotal($queryLastMonthRevenue, $con);

        $theaterShareLastMonth = $grossRevenueLastMonth * $revenueSharingPercentage;

        $queryLastMonthBookings = "SELECT COUNT(*) as total FROM bookings WHERE MONTH(booking_date) = MONTH(CURDATE()) - 1 AND YEAR(booking_date) = YEAR(CURDATE())";
        $totalBookingsLastMonth = getTotal($queryLastMonthBookings, $con);

        $platformFeeRevenueLastMonth = $totalBookingsLastMonth * $platformFeePerBooking;

        // Total profit for the last month
        $totalProfitLastMonth = $theaterShareLastMonth + $platformFeeRevenueLastMonth;

        // Display current month's total profit
        echo number_format($totalProfitCurrentMonth, 2); // Format for currency
    ?></p>
    <span class="small-text">Including platform fee and theater's share</span>
    <br>
    <span class="small-text">Last month's total profit: ₹<?php echo number_format($totalProfitLastMonth, 2); ?></span>
</div>



</div>

</body>
</html>
