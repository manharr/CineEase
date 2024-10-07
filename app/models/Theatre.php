<?php
class Theatre {
    public static function getAll() {
        global $con; // Ensure $con is accessible
        try {
            $query = 'SELECT * FROM theatre';
            $result = mysqli_query($con, $query);
            if ($result) {
                return mysqli_fetch_all($result, MYSQLI_ASSOC); // Return as associative array
            } else {
                throw new Exception('Query failed: ' . mysqli_error($con));
            }
        } catch (Exception $e) {
            echo 'Query failed: ' . $e->getMessage(); // Display any query errors
            return [];
        }
    }

    public static function getById($id) {
        global $con;
        try {
            $stmt = mysqli_prepare($con, 'SELECT * FROM theatre WHERE id = ?');
            if ($stmt) {
                mysqli_stmt_bind_param($stmt, 'i', $id);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                return mysqli_fetch_assoc($result);
            } else {
                throw new Exception('Query preparation failed: ' . mysqli_error($con));
            }
        } catch (Exception $e) {
            echo 'Query failed: ' . $e->getMessage();
            return null;
        }
    }
}
?>
