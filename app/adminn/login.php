<?php 
session_start();
include 'ft.php';
include 'db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CineEase Admin Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 8px;
            background-color: #ffffff;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
        }
        .login-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .login-header h1 {
            font-size: 24px;
            color: #343a40;
        }
        .form-group label {
            font-weight: bold;
            color: #495057;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            border-radius: 4px;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
        .alert {
            margin-top: 20px;
        }
        .dropdown-container, #theatre-username-field {
            display: none;
        }
    </style>
    <script>
        function toggleInput() {
            var role = document.getElementById('role').value;
            var usernameField = document.getElementById('username-field');
            var theatreDropdown = document.getElementById('theatre-dropdown');
            var theatreUsernameField = document.getElementById('theatre-username-field');
            
            if (role === 'THEATRE_ADMIN') {
                usernameField.style.display = 'none';
                theatreDropdown.style.display = 'block';
                theatreUsernameField.style.display = 'block';
            } else {
                usernameField.style.display = 'block';
                theatreDropdown.style.display = 'none';
                theatreUsernameField.style.display = 'none';
            }
        }
    </script>
</head>
<body>

<div class="login-container">
    <div class="login-header">
        <h1>CineEase Admin Login</h1>
    </div>
    <form action="login.php" method="post">
        <div class="form-group">
            <label for="role">Select Role</label>
            <select id="role" name="role" class="form-control" onchange="toggleInput()" required>
                <option value="" disabled selected>Select Role</option>
                <option value="ADMIN">Main Admin</option>
                <option value="THEATRE_ADMIN">Theatre Admin</option>
            </select>
        </div>
        
        <!-- For normal admins -->
        <div id="username-field" class="form-group">
            <label for="username">Username</label>
            <input type="text" name="uname" class="form-control" id="username" placeholder="Username">
        </div>
        
        <!-- For theatre admins -->
        <div id="theatre-dropdown" class="form-group dropdown-container">
            <label for="theatre">Select Theatre</label>
            <select name="theatre" class="form-control">
                <?php
                $theatreQuery = "SELECT * FROM theatre";
                $theatreResult = mysqli_query($con, $theatreQuery);
                while ($theatre = mysqli_fetch_assoc($theatreResult)) {
                    echo "<option value='" . $theatre['id'] . "'>" . $theatre['theatre_name'] . "</option>";
                }
                ?>
            </select>
        </div>

        <!-- Theatre admin username input -->
        <div id="theatre-username-field" class="form-group">
            <label for="theatre-username">Username</label>
            <input type="text" name="theatre_uname" class="form-control" id="theatre-username" placeholder="Theatre Admin Username">
        </div>

        <div class="form-group">
            <label for="exampleInputPassword1">Password</label>
            <input type="password" name="pwd" class="form-control" id="exampleInputPassword1" placeholder="Password" required>
        </div>
        
        <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
    </form>
</div>

<!-- jQuery library -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<!-- Popper JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<!-- Latest compiled JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<?php 
if (isset($_POST['submit'])) {
    $role = $_POST['role'];
    $pwd = $_POST['pwd'];
    
    if ($role === 'ADMIN') {
        $user = $_POST['uname'];
        $query = "SELECT * FROM admin WHERE uname = '$user'";
        $run = mysqli_query($con, $query);
        
        if (mysqli_num_rows($run) > 0) {
            while ($row = mysqli_fetch_assoc($run)) {
                if (password_verify($pwd, $row['pwd'])) {
                    $_SESSION['loginsuccesfull'] = 1;
                    $_SESSION['user'] = $user;
                    $_SESSION['user_role'] = 'main_admin'; 
                    echo "<script>alert('Logged in Successfully'); window.location.href='stats.php';</script>";
                }
            }    
        } else {
            echo "<script>alert('Wrong Username or Password');</script>";
        }
    } else if ($role === 'THEATRE_ADMIN') {
        $theatre_id = $_POST['theatre'];
        $theatre_uname = $_POST['theatre_uname'];
        $query = "SELECT * FROM admin_theatre WHERE theatre_id = '$theatre_id' AND uname = '$theatre_uname'";
        $run = mysqli_query($con, $query);
        
        if (mysqli_num_rows($run) > 0) {
            while ($row = mysqli_fetch_assoc($run)) {
                if (password_verify($pwd, $row['pwd'])) {
                    $_SESSION['loginsuccesfull'] = 1;
                    $_SESSION['user'] = $theatre_uname;
                    $_SESSION['theatre_id'] = $theatre_id; // Set theatre_id in session
                    $_SESSION['user_role'] = 'theatre_admin'; // Set role for theatre admin
                    echo "<script>alert('Logged in Successfully'); window.location.href='theatre/theatreadmin.php';</script>";
                }
            }    
        } else {
            echo "<script>alert('Wrong Username or Password');</script>";
        }
    }
}
?>
</body>
</html>
