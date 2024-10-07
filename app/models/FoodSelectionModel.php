<?php
class FoodSelectionModel
{
    public static function insertFoodSelection($firebaseUid, $movieId, $showTimeId, $bookingDate, $comboOffer, $quantity)
    {
        global $con;

        $query = "INSERT INTO food (firebase_uid, movie_id, show_time_id, booking_date, combo_offer, quantity, created_at) 
                  VALUES (?, ?, ?, ?, ?, ?, NOW())";

        $stmt = mysqli_prepare($con, $query);
        if ($stmt === false) {
            die('Prepare failed: ' . mysqli_error($con));
        }

        mysqli_stmt_bind_param($stmt, 'sssssi', $firebaseUid, $movieId, $showTimeId, $bookingDate, $comboOffer, $quantity);

        $result = mysqli_stmt_execute($stmt);
        if ($result === false) {
            die('Execute failed: ' . mysqli_error($con));
        }

        mysqli_stmt_close($stmt);

        return $result;
    }
}
?>
