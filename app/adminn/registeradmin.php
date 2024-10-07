<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
?>

<!-- registration form -->
<div class="container">
    <div class="head" style="text-align: center;">
        <h1>Register Admin for Cross Cinema</h1>
    </div>
    <form action="registeradmin.php" method="post">
        <div class="form-group">
            <label for="adminType">Admin Type</label>
            <select id="adminType" name="adminType" class="form-control" required>
                <option value="" disabled selected>Select Admin Type</option>
                <option value="ADMIN">Normal Admin</option>
                <option value="THEATRE_ADMIN">Theatre Admin</option>
            </select>
        </div>

        <div class="form-group">
            <label for="uname">Username</label>
            <input type="text" name="uname" class="form-control" id="uname" placeholder="Username" required>
        </div>

        <div class="form-group">
            <label for="pwd">Password</label>
            <input type="password" name="pwd" class="form-control" id="pwd" placeholder="Password" required>
        </div>

        <div class="form-group" id="theatreContainer" style="display: none;">
            <label for="theatre">Select Theatre</label>
            <select name="theatre" id="theatre" class="form-control">
                <?php
                $theatreQuery = "SELECT * FROM theatre";
                $theatreResult = mysqli_query($con, $theatreQuery);
                while ($theatre = mysqli_fetch_assoc($theatreResult)) {
                    echo "<option value='" . $theatre['id'] . "'>" . $theatre['theatre_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <button type="submit" name="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
<!-- reg end -->

<script>
    document.getElementById('adminType').addEventListener('change', function () {
        var adminType = this.value;
        var theatreContainer = document.getElementById('theatreContainer');
        if (adminType === 'THEATRE_ADMIN') {
            theatreContainer.style.display = 'block';
        } else {
            theatreContainer.style.display = 'none';
        }
    });
</script>

<?php 
if (isset($_POST['submit'])) {
    $adminType = $_POST['adminType'];
    $uname = $_POST['uname'];
    $pwd = $_POST['pwd'];
    $hash = password_hash($pwd, PASSWORD_BCRYPT);

    if ($adminType === 'ADMIN') {
        $query = "INSERT INTO `admin`(`uname`, `pwd`) VALUES ('$uname','$hash')";
        $run = mysqli_query($con, $query);
        if ($run) {
            echo "<script>alert('Admin Successfully Added.. :)');window.location.href='adminlist.php';</script>";
        } else {
            echo "Something went wrong";
        }
    } elseif ($adminType === 'THEATRE_ADMIN') {
        $theatre_id = $_POST['theatre'];
        $query = "INSERT INTO `admin_theatre`(`uname`, `pwd`, `theatre_id`) VALUES ('$uname','$hash', '$theatre_id')";
        $run = mysqli_query($con, $query);
        if ($run) {
            echo "<script>alert('Theatre Admin Successfully Added.. :)');window.location.href='adminlist.php';</script>";
        } else {
            echo "Something went wrong";
        }
    }
}
?>
