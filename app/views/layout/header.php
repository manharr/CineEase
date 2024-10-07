<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js"></script>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
        integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
        <link rel="stylesheet" href="/movie-booking/public/css/style.css">
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="/movie-booking/public/js/script.js" ></script>
    <script src="/movie-booking/public/js/firebaseauth.js" type="module"></script>
    <script src="/movie-booking/public/js/login.js" type="module"></script>
    <script src="/movie-booking/public/js/google.js" type="module"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>CineEase</title>
</head>

<body>
    <div id="overlay" class="overlay"></div>
    <div id="contact-overlay" class="contact-overlay"></div>
    <div class="menu">
        <!-- menu bar -->
        <button class="setting-btn">
            <span class="bar bar1"></span>
            <span class="bar bar2"></span>
            <span class="bar bar3"></span>
        </button>
        <h1 class="logo">Cine<span>Ease</span></h1>
        <ul>
        <?php
        $theatres = $theatres ?? [];
        ?>
        <li class="dropdown-btn">
                <a href="#" id="theatreDropdown"> Theatre
                    <span class="material-icons-outlined dicon">
                        arrow_drop_down
                    </span>
                </a>
                <div class="dropdown">
                    <ul>
                        
                        <?php if (!empty($theatres)): ?>
                            <?php foreach ($theatres as $theatre): ?>
                                
                                <li>
                                    
                                    <a href="#" data-value="<?php echo htmlspecialchars($theatre['theatre_location']); ?>">
                                        <img src="/movie-booking/public/images/location.png" alt="Location Marker" class="location-icon">
                                        <?php echo htmlspecialchars($theatre['theatre_name']); ?>
                                        <span class="checkmark"></span>
                                    </a>
                                </li>
                                <?php if (next($theatres)): ?>
                                    <hr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <li>No theatres available</li>
                        <?php endif; ?>
                        
                    </ul>
                </div>
            </li>
            <li><a href="#about">About</a></li>
            <li><a href="#" id="contactUsLink">Contact Us</a></li>
            <div id="contactForm" class="contact-form-container">
            <script>
        document.addEventListener('DOMContentLoaded', function () {
            function getQueryParams() {
                const params = new URLSearchParams(window.location.search);
                return {
                    message: params.get('message'),
                    status: params.get('status')
                };
            }

            function clearQueryParams() {
                const url = new URL(window.location.href);
                url.search = ''; 
                window.history.replaceState({}, document.title, url.toString()); 
            }

            const { message, status } = getQueryParams();

            if (message && status) {
                Swal.fire({
                    title: status === 'success' ? 'Success!' : 'Error!',
                    text: message,
                    icon: status,
                    confirmButtonText: 'OK'
                }).then(() => {
                    clearQueryParams();
                });
            }
        });
    </script>
            <form id="contactFormContent" method="POST" action="index.php?url=Contact/submit">
            <h2>Contact Us</h2>
                <label for="name">Your Name:</label>
                <input type="text" id="name" name="name" placeholder="Enter your name" required>

                <label for="email">Your Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>

                <label for="message">Your Message:</label>
                <textarea id="message" name="message" rows="4" placeholder="Enter your message or suggestion" required></textarea>

                <button type="submit">Submit</button>
            </form>
        </div>
            <li id="profile-menu" class="dropdown-container" style="display: none;">
                <a href="#" id="profileIcon" class="profile-icon"></a>
                <div class="dropdown-menu" id="profileDropdown">
                    <ul>
                        <li><a href="/movie-booking/app/views/seat/editprofile.php" target="_self">
                            <i class="fas fa-user-edit"></i> Edit Profile
                        </a></li>
                        <li>
                            <a href="/movie-booking/app/views/seat/bookings.php" target="_blank">
                                <i class="fas fa-ticket-alt"></i> My Bookings
                            </a>
                        </li>
                        <li>
                            <a href="#" id="logout">
                                <i class="fas fa-sign-out-alt"></i> Logout
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li id="login"><a href="#">Sign IN <span class="arrow">&rarr;</span></a></li>
        </ul>
    </div>




    

    <!-- Sliding Menu -->
    <div class="sliding-menu">
        <span class="cross-icon" id="cross">&#10006;</span>
        <ul id="menu-items">
            <li><a href="index.php">Home</a></li>
            <li id="my-bookings" style="display: none;"><a href="/movie-booking/app/views/seat/editprofile.php">Edit Profile</a></li>
            <li id="my-bookings2" style="display: none;"><a target="_blank" href="/movie-booking/app/views/seat/bookings.php">My Bookings</a></li>
            <li><a href="/movie-booking/app/views/layout/offers.php" target="_blank">Offers</a></li>
            <li><button class="Btn" id="subscribeBtn"></button></li>
        </ul>
    </div>



    <!-- Subscription popup -->
    <div class="modal" id="subscriptionPopup">
        <div class="modal-content">
            <span class="close" id="closeSubscriptionPopup">&times;</span>
            <h2>Go Premium</h2>
            <p>Unlock premium features and content:</p>
            <ul>
                <li><i class="fas fa-check"></i> Exclusive access to new movies</li>
                <li><i class="fas fa-check"></i> Early ticket booking</li>
                <li><i class="fas fa-check"></i> Discounts on latest movies</li>
            </ul>
            <button class="Btn1">Upgrade Now</button>
        </div>
    </div>

    

 <!-- Login/Registration Form -->
 <div class="form">
        <div class="form-buttons">
            <div id="btn"></div>
            <button type="button" class="toggle-btn" onclick="loginpage()">Log In</button>
            <button type="button" class="toggle-btn" onclick="registerpage()">Register</button>
        </div>
        <div class="social-icon">
            <img src="/movie-booking/public/images/g2.png" alt="google.com" id="googleid">
            <img src="/movie-booking/public/images/facebook.png" alt="google.com" id="fbid">
        </div>
        <form method="post" action="" class="input-grp" id="loginpage">
            <div id="signInMessage" class="messageDiv" style="display: none;"></div>
            <input id="remail" class="input-field" type="email" name="email" placeholder="Email" required="">
            <input id="rpasswd" class="input-field" type="password" name="password" placeholder="Password" required="">
            <br> <br>
            <!-- Forgot Password Link -->
            <a href="#" class="forgot-password-link" id="resetid">Forgot Password?</a>
            <br> <br>
            <button type="submit" class="submit-btn" id="logbtn">Log In</button>
            <p class="form-text" id="signu">Don't have an account? <a href="#" id="signup-link">Sign Up</a></p>
        </form>

        <form method="post" action="" class="input-grp" id="registerpage">
            <div id="signUpMessage" class="messageDiv" style="display: none;"></div>
            <input class="input-field" id="emailid" type="email" name="email" placeholder="Email" required>
            <input class="input-field" id="passwd" type="password" name="password" placeholder="Password" required>
            <input class="input-field" id="confirmpasswd" type="password" name="repeat_password" placeholder="Confirm Password" required>
            <span id="passwordError"></span>
            <br> <br>
            <button type="submit" class="submit-btn" id="registeruser">Register</button>
            <p class="form-text">Already Registered? <a href="#" id="login-link">Log In</a></p>
        </form>


                        </div>
                        </div>


       

<!-- Loader -->
<div id="loader">
    <div class="traffic-loader"></div>
</div>

<div id="content" style="display: none;">
    <!-- Your existing content goes here -->
</div>


<?php
sleep(0.5);
?>




</body>

</html>
