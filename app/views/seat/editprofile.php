<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/10.12.3/firebase-firestore.js"></script>
    <link rel="icon" href="/movie-booking/app/views/img/cin.png" sizes="16x16" type="image/png">

    <title>Update Profile</title>
    <style>
        body {
    font-family: 'Poppins', sans-serif; /* A more modern font */
    background-color: #0d0d0d; /* Dark background for contrast */
    color: #e0e0e0;
    margin: 0;
    padding: 0;
}

.container {
    width: 90%;
    max-width: 600px; /* Added max-width for better responsiveness */
    margin: auto;
    background: #1e1e1e;
    padding: 30px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.5);
    border-radius: 15px;
    margin-top: 50px;
    animation: fadeIn 0.5s ease-in-out; /* Fade-in effect */
}

.container h2 {
    text-align: center;
    margin-bottom: 30px;
    color: #ffffff;
    font-size: 28px; /* Increased font size for emphasis */
    letter-spacing: 1px; /* Spacing for better readability */
}

.form-group {
    margin-bottom: 20px; /* Increased space for clarity */
}

.form-group label {
    display: block;
    margin-bottom: 5px;
    color: #bdbdbd;
    font-size: 16px; /* Slightly larger for better legibility */
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="tel"] {
    width: 100%;
    padding: 12px; /* Increased padding for comfort */
    box-sizing: border-box;
    border: 1px solid #424242;
    border-radius: 10px; /* Softer corners */
    background-color: #303030;
    color: #e0e0e0;
    transition: border-color 0.3s; /* Transition effect for border */
}

.form-group input[type="text"]:focus,
.form-group input[type="email"]:focus,
.form-group input[type="tel"]:focus {
    border-color: #6200ea; /* Color on focus for interaction */
    outline: none; /* Remove outline for a cleaner look */
}

.btn {
    display: block;
    width: 100%;
    padding: 12px;
    background-color: #6200ea;
    color: #ffffff;
    border: none;
    border-radius: 10px; /* Softer corners */
    cursor: pointer;
    text-align: center;
    font-size: 18px; /* Larger button text */
    transition: background-color 0.3s ease, transform 0.3s ease; /* Transition for hover effects */
}

.btn:hover {
    background-color: #3700b3; /* Darker color on hover */
    transform: translateY(-2px); /* Slight lift effect */
}

.message {
    display: none;
    text-align: center;
    padding: 12px;
    margin-top: 20px;
    border-radius: 5px;
    font-size: 16px; /* Consistent text size */
}

.message.success {
    background-color: #388e3c;
    color: #ffffff;
}

.message.error {
    background-color: #d32f2f;
    color: #ffffff;
}

/* Fade-in animation */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

    </style>
</head>
<body>
    <div class="container">
        <h2>Update Profile</h2>
        <div class="form-group">
            <label for="firstName">First Name</label>
            <input type="text" id="firstName">
        </div>
        <div class="form-group">
            <label for="lastName">Last Name</label>
            <input type="text" id="lastName">
        </div>
        <div class="form-group">
            <label for="phone">Phone Number</label>
            <input type="tel" id="phone">
        </div>
        <div class="form-group">
            <label for="email">Email (cannot be changed)</label>
            <input type="email" id="email" readonly>
        </div>
        <button class="btn" id="saveChanges">Save Changes</button>
        <div id="message" class="message"></div>
    </div>
    <script src="/movie-booking/public/js/edit.js" type="module">
    </script>
</body>
</html>
