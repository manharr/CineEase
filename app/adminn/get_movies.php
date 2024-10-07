<?php
include 'db.php';

if (isset($_POST['theatre_id'])) {
    $theatre_id = $_POST['theatre_id'];
    $query = "SELECT id, mv_name FROM movie";
    $result = mysqli_query($con, $query);

    echo '<option value="">Select Movie</option>';
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $row['id'] . '">' . $row['mv_name'] . '</option>';
    }
}
?>
