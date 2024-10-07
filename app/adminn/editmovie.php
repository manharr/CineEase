<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "SELECT * FROM movie WHERE id = $id";
    $run = mysqli_query($con, $query);

    if ($run) {
        $row = mysqli_fetch_assoc($run);
        if ($row) {
?>
<div class="container">
    <div class="jumbotron">
        <form action="editmovie.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <input type="text" name="mv_name" value="<?php echo htmlspecialchars($row['mv_name']); ?>" class="form-control" placeholder="Enter Movie Name">
            </div>
            <div class="form-group">
                <input type="text" name="mv_m_desc" value="<?php echo htmlspecialchars($row['meta_description']); ?>" class="form-control" placeholder="Enter Meta Description">
            </div>
            <div class="form-group">
                <input type="text" name="mv_m_tag" value="<?php echo htmlspecialchars($row['mv_tag']); ?>" class="form-control" placeholder="Enter Meta Tags">
            </div>
            <div class="form-group">
                <input type="text" name="mv_link1" value="<?php echo htmlspecialchars($row['link1']); ?>" class="form-control" placeholder="Enter Trailer Link">
            </div>
            <div class="form-group">
                <input type="text" name="runtime" value="<?php echo htmlspecialchars($row['runtime']); ?>" class="form-control" placeholder="Enter Runtime (e.g., 2h 1m)">
            </div>
            <div class="form-group">
                <select name="certificate" class="form-control">
                    <option value="" disabled>Select Certificate</option>
                    <option value="U" <?php echo $row['certificate'] == 'U' ? 'selected' : ''; ?>>U</option>
                    <option value="UA" <?php echo $row['certificate'] == 'UA' ? 'selected' : ''; ?>>UA</option>
                    <option value="A" <?php echo $row['certificate'] == 'A' ? 'selected' : ''; ?>>A</option>
                </select>
            </div>
            <div class="form-group">
                <select name="status" class="form-control">
                    <option value="" disabled>Select Status</option>
                    <option value="Advance Booking" <?php echo $row['status'] == 'Advance Booking' ? 'selected' : ''; ?>>Advance Booking</option>
                    <option value="Promoted" <?php echo $row['status'] == 'Promoted' ? 'selected' : ''; ?>>Promoted</option>
                    <option value="New Release" <?php echo $row['status'] == 'New Release' ? 'selected' : ''; ?>>New Release</option>
                    <option value="Discounted" <?php echo $row['status'] == 'Discounted' ? 'selected' : ''; ?>>Discounted</option>
                    <option value="" <?php echo empty($row['status']) ? 'selected' : ''; ?>>None</option>
                </select>
            </div>
          

            <div class="form-group">
                <input type="date" name="mv_date" value="<?php echo htmlspecialchars($row['sdate']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <input type="date" name="mv_edate" value="<?php echo htmlspecialchars($row['edate']); ?>" class="form-control">
            </div>
            <div class="form-group">
                <input type="text" name="mv_lang" value="<?php echo htmlspecialchars($row['lang']); ?>" class="form-control" placeholder="Enter Movie Language">
            </div>
            <div class="form-group">
                <input type="text" name="mv_director" value="<?php echo htmlspecialchars($row['director']); ?>" class="form-control" placeholder="Enter Movie Director">
            </div>
            <div class="form-group">
                <input type="text" name="cat_id" value="<?php echo htmlspecialchars($row['cat_id']); ?>" class="form-control" placeholder="Enter Category ID">
            </div>
            <div class="form-group">
                <input type="text" name="genre_id" value="<?php echo htmlspecialchars($row['genre_id']); ?>" class="form-control" placeholder="Enter Genre ID">
            </div>
            <div class="form-group">
                <input type="text" name="mv_cast" value="<?php echo htmlspecialchars($row['cast']); ?>" class="form-control" placeholder="Enter Movie Cast">
            </div>
            <div class="custom-file">
                <input type="file" name="img" class="custom-file-input" id="customFile">
                <img src="../thumb/<?php echo htmlspecialchars($row['img']); ?>" height="100px" alt="Current Image">
                <label class="custom-file-label" for="customFile">Choose file</label>
                <input type="hidden" name="old_img" value="<?php echo htmlspecialchars($row['img']); ?>">
            </div>
            <br><br><br><br><br><br>
            <div class="form-group">
                <textarea name="mv_desc" class="form-control" placeholder="Enter Movie Description" rows="4"><?php echo htmlspecialchars($row['mv_des']); ?></textarea>
            </div>
            <div class="form-group">
                <label for="coming-soon">Coming Soon</label>
                <input type="checkbox" id="coming-soon" name="coming_soon" value="1" <?php echo $row['coming_soon'] == '1' ? 'checked' : ''; ?>>
            </div>
            <br> 
            <button type="submit" name="submit" class="btn btn-info btn-lg">Submit</button>
        </form>
    </div>
</div>

<?php 
        } else {
            echo "<script>alert('Movie Not Found');window.location.href='movielist.php';</script>";
        }
    } else {
        echo "<script>alert('Query Failed');window.location.href='movielist.php';</script>";
    }
} else {
    header('location:movielist.php');
}

if (isset($_POST['submit'])) {
    $mv_name = mysqli_real_escape_string($con, $_POST['mv_name']);
    $mv_m_desc = mysqli_real_escape_string($con, $_POST['mv_m_desc']);
    $mv_m_tag = mysqli_real_escape_string($con, $_POST['mv_m_tag']);
    $mv_link1 = mysqli_real_escape_string($con, $_POST['mv_link1']);
    $mv_runtime = mysqli_real_escape_string($con, $_POST['runtime']);
    $mv_lang = mysqli_real_escape_string($con, $_POST['mv_lang']);
    $cat_id = mysqli_real_escape_string($con, $_POST['cat_id']);
    $genre_id = mysqli_real_escape_string($con, $_POST['genre_id']);
    $mv_desc = mysqli_real_escape_string($con, $_POST['mv_desc']);
    $mv_director = mysqli_real_escape_string($con, $_POST['mv_director']);
    $mv_cast = mysqli_real_escape_string($con, $_POST['mv_cast']);
    $mv_date = date('Y-m-d', strtotime($_POST['mv_date']));
    $mv_edate = date('Y-m-d', strtotime($_POST['mv_edate']));
    $old_img = mysqli_real_escape_string($con, $_POST['old_img']);
    $coming_soon = isset($_POST['coming_soon']) ? '1' : '0';


    if ($_FILES['img']['name'] != '') {
        $new_img = mysqli_real_escape_string($con, $_FILES['img']['name']);
        $target = "../thumb/" . basename($new_img);
    } else {
        $new_img = $old_img;
    }

    $status = mysqli_real_escape_string($con, $_POST['status']) ?: '';

    $query1 = "UPDATE movie SET 
                cat_id = '$cat_id', 
                genre_id = '$genre_id', 
                mv_name = '$mv_name', 
                mv_des = '$mv_desc', 
                mv_tag = '$mv_m_tag', 
                link1 = '$mv_link1', 
                runtime = '$mv_runtime', 
                img = '$new_img', 
                sdate = '$mv_date', 
                edate = '$mv_edate', 
                lang = '$mv_lang', 
                director = '$mv_director', 
                meta_description = '$mv_m_desc', 
                status = '$status', 
                coming_soon = '$coming_soon', 
                cast = '$mv_cast'
              WHERE id = $id";

    $qr = mysqli_query($con, $query1);

    if ($qr) {
        if ($_FILES['img']['name'] != '') {
            if (move_uploaded_file($_FILES['img']['tmp_name'], $target)) {
                echo "<script>alert('Movie Successfully Updated');window.location.href='movielist.php';</script>";
            } else {
                echo "<script>alert('File Upload Failed');window.location.href='editmovie.php?id=$id';</script>";
            }
        } else {
            echo "<script>alert('Movie Successfully Updated');window.location.href='movielist.php';</script>";
        }
    } else {
        echo "<script>alert('Update Query Failed');window.location.href='editmovie.php?id=$id';</script>";
    }
}
?>
