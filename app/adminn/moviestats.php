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

function getMovieStats($con) {
    $query = "
        SELECT 
            movie.mv_name, 
            COUNT(bookings.id) AS total_bookings, 
            SUM(bookings.total_price) AS total_revenue 
        FROM 
            movie
        LEFT JOIN 
            bookings ON movie.id = bookings.movie_id 
        GROUP BY 
            movie.id 
        ORDER BY 
            total_revenue DESC";
    return mysqli_query($con, $query);
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
            padding: 20px;
        }
        .stats-card {
            background-color: #fff;
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .stats-card h5 {
            font-size: 1.5rem;
            color: #555;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f4f4f4;
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
        <h5>Movie Booking Statistics</h5>
        <table>
            <thead>
                <tr>
                    <th>Movie Title</th>
                    <th>Total Bookings</th>
                    <th>Total Revenue (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $movieStats = getMovieStats($con);
                if (mysqli_num_rows($movieStats) > 0) {
                    while ($row = mysqli_fetch_assoc($movieStats)) {
                        // Ensure total_revenue is not null
                        $totalRevenue = $row['total_revenue'] ?? 0;
                        echo "<tr>
                                <td>{$row['mv_name']}</td>
                                <td>{$row['total_bookings']}</td>
                                <td>₹" . number_format($totalRevenue, 2) . "</td>
                              </tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>No data available.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>
</div>

</body>
</html>
