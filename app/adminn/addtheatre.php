<?php 

include 'db.php';
include 'header.php';
include 'ft.php';
 ?>


<div class="container">
    <h2>Add Theatre</h2>
    <form method="post" action="addtheatre.php">
        <div class="form-group">
            <label for="tname">Theatre Name</label>
            <input type="text" class="form-control" id="tname" name="tname" value="" required>
        </div>
        <div class="form-group">
            <label for="tloc">Address</label>
            <input type="text" class="form-control" id="tloc" name="tloc" value="" required>
        </div>
        <div class="form-group">
            <label for="tscreen">Screens</label>
            <input type="number" class="form-control" id="tscreen" name="tscreen" value="" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Add Theatre</button>
        <a href="theatrelist.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php 

if (isset($_POST['submit'])) {
    $tname = trim($_POST['tname']);
    $tloc = trim($_POST['tloc']);
    $tscreen = trim($_POST['tscreen']);

    // Check if inputs are not empty
    if (empty($tname) || empty($tloc)) {
        echo "<script>alert('Please fill in all fields.');window.location.href='addtheatre.php';</script>";
        exit();
    }

    // Prepare SQL query
    $query = $con->prepare("INSERT INTO `theatre` (`theatre_name`, `address`, `screens`) VALUES (?, ?, ?)");
    $query->bind_param("ssi", $tname, $tloc, $tscreen);

    if ($query->execute()) {
        echo "<script>alert('Theatre successfully added!');window.location.href='theatrelist.php';</script>";
    } else {
        echo "<script>alert('Something went wrong. Please try again.');window.location.href='addtheatre.php';</script>";
    }

    $query->close();
}

?>
