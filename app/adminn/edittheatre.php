<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

// Check if the ID is set in the query string
if (isset($_GET['id'])) {
    $theatre_id = intval($_GET['id']);
    
    // Fetch the current theatre details from the database
    $query = "SELECT * FROM theatre WHERE id = ?";
    $stmt = $con->prepare($query);
    $stmt->bind_param("i", $theatre_id);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the theatre exists
    if ($result->num_rows > 0) {
        $theatre = $result->fetch_assoc();
    } else {
        echo "<script>alert('Theatre not found.');window.location.href='theatrelist.php';</script>";
        exit();
    }
} else {
    echo "<script>alert('Invalid request.');window.location.href='theatrelist.php';</script>";
    exit();
}
?>

<div class="container">
    <h2>Edit Theatre</h2>
    <form method="post" action="edittheatre.php?id=<?php echo $theatre_id; ?>">
        <div class="form-group">
            <label for="tname">Theatre Name</label>
            <input type="text" class="form-control" id="tname" name="tname" value="<?php echo htmlspecialchars($theatre['theatre_name']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tloc">Address</label>
            <input type="text" class="form-control" id="tloc" name="tloc" value="<?php echo htmlspecialchars($theatre['address']); ?>" required>
        </div>
        <div class="form-group">
            <label for="tscreen">Screens</label>
            <input type="number" class="form-control" id="tscreen" name="tscreen" value="<?php echo htmlspecialchars($theatre['screens']); ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Update Theatre</button>
        <a href="theatrelist.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php
// Handle form submission
if (isset($_POST['submit'])) {
    $tname = trim($_POST['tname']);
    $tloc = trim($_POST['tloc']);
    $tscreen = intval($_POST['tscreen']);

    // Update the theatre details in the database
    $updateQuery = "UPDATE theatre SET theatre_name = ?, address = ?, screens = ? WHERE id = ?";
    $updateStmt = $con->prepare($updateQuery);
    $updateStmt->bind_param("ssii", $tname, $tloc, $tscreen, $theatre_id);

    if ($updateStmt->execute()) {
        echo "<script>alert('Theatre updated successfully!');window.location.href='theatrelist.php';</script>";
    } else {
        echo "<script>alert('Error updating theatre.');window.location.href='edittheatre.php?id=$theatre_id';</script>";
    }
}
?>

