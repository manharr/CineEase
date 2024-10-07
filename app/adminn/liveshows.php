<?php 
include 'header.php';
include 'ft.php';
include 'db.php';

date_default_timezone_set('Asia/Kolkata');

$currentTime = new DateTime(); 
$query = "
    SELECT st.id, st.movie_timings, st.movie_language, st.movie_format, 
           st.gold_price, st.platinum_price, st.theatre_id, st.screen, 
           m.mv_name, t.theatre_name 
    FROM show_timings st 
    JOIN movie m ON st.movie_id = m.id 
    JOIN theatre t ON st.theatre_id = t.id  
    ORDER BY st.movie_timings ASC"; // Order by showtime
$run = mysqli_query($con, $query);
?>

<div class="container mt-5">
    <h1 class="text-center mb-4">Live Shows</h1>
    <table class="table table-striped table-bordered">
        <thead class="thead-dark">
            <tr>
                <th scope="col">Theatre Name</th>  
                <th scope="col">Movie Name</th>
                <th scope="col">Showtime</th>
                <th scope="col">Language</th>
                <th scope="col">Format</th>
                <th scope="col">Gold Price</th>
                <th scope="col">Platinum Price</th>
                <th scope="col">Screen</th>
                <th scope="col">Status</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            if ($run) {
                while ($row = mysqli_fetch_assoc($run)) {
                    $showTime = new DateTime($row['movie_timings']);
                    
                    $endTime = clone $showTime; // Clone the showtime so original is preserved
                    $endTime->modify('+2 hours 10 minutes');

                    // Determine the status based on the current time
                    if ($currentTime < $showTime) {
                        $status = '<button class="btn btn-info btn-sm">Next Showing</button>';
                    } elseif ($currentTime >= $showTime && $currentTime <= $endTime) {
                        $status = '<button class="btn btn-success btn-sm">Running</button>'; 
                    } else {
                        $status = '<button class="btn btn-danger btn-sm">Ended</button>';
                    }
            ?>
            <tr>
                <td><?php echo htmlspecialchars($row['theatre_name']); ?></td> 
                <td><?php echo htmlspecialchars($row['mv_name']); ?></td>
                <td><?php echo htmlspecialchars($row['movie_timings']); ?></td>
                <td><?php echo htmlspecialchars($row['movie_language']); ?></td>
                <td><?php echo htmlspecialchars($row['movie_format']); ?></td>
                <td>₹<?php echo htmlspecialchars($row['gold_price']); ?></td>
                <td>₹<?php echo htmlspecialchars($row['platinum_price']); ?></td>
                <td><?php echo htmlspecialchars($row['screen']); ?></td>
                <td><?php echo $status; ?></td>
            </tr>
            <?php
                }
            } else {
                echo "<tr><td colspan='9' class='text-center'>No shows available</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>
