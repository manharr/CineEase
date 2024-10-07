<?php 

include 'db.php';
include 'header.php';
include 'ft.php';
?>

<div class="container">
    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="#">Genre On CineEase</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="btn btn-warning text-light" href="addgenre.php">Add a Genre</a>
                    </li>
                </ul>
                <ul class="navbar-nav ml-auto">
                    <form class="form-inline" method="post" action="searchgenre.php">
                        <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search">
                        <button class="btn btn-success" name="submit" type="submit">Search</button>
                    </form>
                </ul>
            </div>
        </nav>
    </div>
    <hr>
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Genre Name</th>
                <th scope="col">Category ID</th>
                <th scope="col">Genre ID</th>
                <th scope="col">No. of Categories</th>
                <th scope="col">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            $query = "SELECT * FROM genre";
            $run = mysqli_query($con, $query);

            // Fetch total number of categories once
            $queryTotalCategories = "SELECT count(*) as total_category FROM category";
            $runTotalCategories = mysqli_query($con, $queryTotalCategories);
            $totalCategoriesRow = mysqli_fetch_assoc($runTotalCategories);
            $totalCategories = $totalCategoriesRow['total_category'];

            if ($run) {
                $firstRow = true; // Flag to check if it's the first row
                while ($row = mysqli_fetch_assoc($run)) {
                    $id = $row['id'];
                    $query2 = "SELECT count(*) as total_post FROM movie WHERE genre_id=$id";
                    $run2 = mysqli_query($con, $query2);
                    $totalPostsRow = mysqli_fetch_assoc($run2);
                    $totalPosts = $totalPostsRow['total_post'];
                    ?>
                    <tr>
                        <th scope="row"><?php echo $row['id']; ?></th>
                        <td><?php echo $row['genre_name']; ?></td>
                        <td><?php echo $row['category_id']; ?></td>
                        <td><?php echo $row['genreid']; ?></td>
                        <td><?php echo $firstRow ? $totalCategories : ''; ?></td>
                        <td>
                            <a class="btn btn-danger" href="deletegenre.php?id=<?php echo $row['id']; ?>">DELETE</a> 
                            <a class="btn btn-outline-info" href="editgenre.php?id=<?php echo $row['id']; ?>&genrename=<?php echo $row['genre_name']; ?>&catid=<?php echo $row['category_id']; ?>&genreid=<?php echo $row['genreid']; ?>">EDIT</a>
                        </td>
                    </tr>
                    <?php
                    $firstRow = false; // Set the flag to false after the first row
                }
            }
            ?>
        </tbody>
    </table>
</div>
