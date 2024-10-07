<?php 
include 'db.php';
include 'header.php';
include 'ft.php';

?>

<style>
    #stats{
        position: relative;
        left:455px;
    }
    </style>
<div class="container mt-4">
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="#">Movies On CineEase</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="btn btn-warning text-light" href="addmovie.php">Add a Movie</a>
                </li>
                
            </ul>
            
            <ul class="navbar-nav ml-auto">
                <form class="form-inline" method="post" action="searchmovie.php">
                    <input class="form-control mr-sm-2" name="search" type="text" placeholder="Search">
                    <button class="btn btn-success" name="submit" type="submit">Search</button>
                </form>
            </ul>

        </div>

    </nav>
    <a class="btn btn-success text-light" id="stats" href="moviestats.php">Movie Stats</a>

</div>


<div class="container mt-4">
    <div class="row">
        <?php 
        $query = "SELECT * FROM movie";
        $run = mysqli_query($con, $query);
        if ($run) {
            while ($row = mysqli_fetch_assoc($run)) {
                ?>
                <div class="col-md-3 mb-4">
                    <div class="card h-100 shadow-sm">
                        <div class="card-header bg-dark text-white">
                            <p class="mb-0"><?php echo $row['id']; ?></p>
                        </div>
                        <img class="card-img-top" height="100px" src="../thumb/<?php echo $row['img']; ?>" alt="<?php echo $row['mv_name']; ?>">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title"><?php echo $row['mv_name']; ?></h5>
                            <p class="card-text"><?php echo $row['meta_description']; ?></p>
                            <div class="mt-2">
                                <a href="deletemovie.php?id=<?php echo $row['id']; ?>" class="btn btn-danger">Delete</a>
                                <a href="editmovie.php?id=<?php echo $row['id']; ?>" class="btn btn-info">Edit</a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }
        ?>
    </div>
</div>

<style>
.card {
    transition: transform 0.3s ease-in-out;
}


.card-img-top {
    object-fit: cover;
}
</style>
