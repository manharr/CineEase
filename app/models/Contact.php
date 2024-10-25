<?php
class Contact {
    public static function submitMessage($name, $email, $message) {
        global $con;
        $name = mysqli_real_escape_string($con, $name);
        $email = mysqli_real_escape_string($con, $email);
        $message = mysqli_real_escape_string($con, $message);

        $query = "INSERT INTO contactus (uname, mail, message) VALUES ('$name', '$email', '$message')";
        return mysqli_query($con, $query);
    }
}
?>
