<?php 

include 'db.php';
include 'header.php';
include 'ft.php';

$search1 = isset($_POST['search']) ? $_POST['search'] : '';

?>
<div class="container">
    <center><h1>Search Result of "<?php echo htmlspecialchars($search1); ?>"</h1></center>
    <div class="row">

    <?php 

    if (isset($_POST['submit'])) {
        $search = $_POST['search'];
        $searchpreg = preg_replace("#[^0-9a-z]#i", "", $search);
        $query = "SELECT * FROM movie WHERE mv_name LIKE ? OR mv_tag LIKE ? OR lang LIKE ? OR director LIKE ?"; // Removed `date`

        // Prepare statement
        if ($stmt = $con->prepare($query)) {
            $like_search = "%$search%";
            $stmt->bind_param("ssss", $like_search, $like_search, $like_search, $like_search);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->num_rows;

            if ($count == 0) {
                echo "<h1>No Movie Found!!</h1>";
            } else {
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <div class="col">
                        <div class="card" style="width:200px; text-align: center;">
                                <?php echo "<img height='180px' width='auto' src='../thumb/".$row['img']."'>"; ?>
                            <p><?php echo htmlspecialchars($row['mv_name']); ?></p>
							<a href="editmovie.php?id=<?php echo $row['id'] ?>">Edit</a>
                        </div>
                    </div>
                    <?php
                }
            }
            $stmt->close();
        } else {
            echo "<h1>Error in query preparation!</h1>";
        }
    }

    ?>
    </div>
</div>
