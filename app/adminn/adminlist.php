<?php 
include 'header.php';
include 'ft.php';
include 'db.php';
?>
<!-- Main Admins Section -->
<div class="container">
    <div class="head" style="padding-top: 10px; padding-bottom: 10px; text-align: center;">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Main Admins of CineEase</h2>
            <!-- New Admin button placed at the top, next to the main heading -->
            <a class="btn btn-success" href="registeradmin.php">New Admin</a>
        </div>
        <hr>
    </div>
    
    <!-- Main Admins Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Password</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Query for fetching main admins
            $queryMainAdmin = "SELECT * FROM admin";
            $runMainAdmin = mysqli_query($con, $queryMainAdmin);

            if ($runMainAdmin) {
                while ($row = mysqli_fetch_assoc($runMainAdmin)) {
            ?>
            <tr>
                <th scope="row"><?php echo $row['id']; ?></th>
                <td><?php echo $row['uname']; ?></td>
                <td><pre>Password Encrypted</pre></td>
                <td>
                    <a class="btn btn-danger" href="deleteadmin.php?unameid=<?php echo $row['id']; ?>">Remove</a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Theatre Admins Section -->
<div class="container">
    <div class="head" style="padding-top: 10px; padding-bottom: 10px; text-align: center;">
        <div class="d-flex justify-content-between align-items-center">
            <h2>Theatre Admins of CineEase</h2>
        </div>
        <hr>
    </div>
    
    <!-- Theatre Admins Table -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Username</th>
                <th scope="col">Theatre Name</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            // Query for fetching theatre admins
            $queryTheatreAdmin = "SELECT at.admin_id, at.uname, t.theatre_name 
                                  FROM admin_theatre at 
                                  JOIN theatre t ON at.theatre_id = t.id";
            $runTheatreAdmin = mysqli_query($con, $queryTheatreAdmin);

            if ($runTheatreAdmin) {
                while ($row = mysqli_fetch_assoc($runTheatreAdmin)) {
            ?>
            <tr>
                <th scope="row"><?php echo $row['admin_id']; ?></th>
                <td><?php echo $row['uname']; ?></td>
                <td><?php echo $row['theatre_name']; ?></td>
                <td>
                    <a class="btn btn-danger" href="deletetheatreadmin.php?id=<?php echo $row['admin_id']; ?>">Remove</a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>
</div>
