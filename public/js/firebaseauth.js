
import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries
import { getAuth, createUserWithEmailAndPassword } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";
import { getFirestore, setDoc, doc } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-firestore.js";


// Your web app's Firebase configuration
const firebaseConfig = {
    apiKey: "AIzaSyDvk4BSP8zqonNGmYRUsqFbXuUTu8Kw0MM",
    authDomain: "login-form-c55dc.firebaseapp.com",
    projectId: "login-form-c55dc",
    storageBucket: "login-form-c55dc.appspot.com",
    messagingSenderId: "725345110293",
    appId: "1:725345110293:web:8590de145d691aefed011c"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);

console.log("Firebase initialized");

document.addEventListener('DOMContentLoaded', () => {
    // Register User
    const registerButton = document.getElementById('registeruser');
    registerButton.addEventListener('click', async (event) => {
        event.preventDefault();

        const email = document.getElementById('emailid').value;
        const password = document.getElementById('passwd').value;
        const confirmPassword = document.getElementById('confirmpasswd').value;

        // Validate email
        if (!email) {
            showMessage('Please enter an email address.', 'signUpMessage');
            return;
        } else if (!isValidEmail(email)) {
            showMessage('Please enter a valid email address.', 'signUpMessage');
            return;
        }

        // Check if passwords match
        if (password !== confirmPassword) {
            showMessage('Passwords do not match!', 'signUpMessage');
            return;
        }
        if (password.length < 6) {
            showMessage('Password must be at least 6 characters long!', 'signUpMessage');
            return;
        }
        const auth = getAuth();
        const db = getFirestore();

        try {
            const userCredential = await createUserWithEmailAndPassword(auth, email, password);
            const user = userCredential.user;
            const userData = { email: email };
            const docRef = doc(db, 'users', user.uid);
            await setDoc(docRef, userData);
            showMessage('Account Created Successfully', 'signUpMessage');
            window.location.href = '/movie-booking/public/index.php'; // Redirect to main page after successful registration
        } catch (error) {
            console.error('Error registering user:', error);
            const errorCode = error.code;
            if (errorCode === 'auth/email-already-in-use') {
                showMessage('Email Address Already Exists!', 'signUpMessage');
            } else {
                showMessage('Unable to create user', 'signUpMessage');
            }
        }
    });
});

// Function to validate email format
function isValidEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

// Function to display message in messageDiv
function showMessage(message, divId) {
    const messageDiv = document.getElementById(divId);
    messageDiv.innerHTML = message;
    messageDiv.style.display = "block";
    messageDiv.style.opacity = 1;

    setTimeout(function () {
        messageDiv.style.opacity = 0;
        setTimeout(function () {
            messageDiv.style.display = "none";
        }); 
    }, 5000); // Display message for 5 seconds
}


// Function to update the navigation bar based on login status
// function updateNavigationBar(loggedIn) {
//     const auth = getAuth();
//     const loginNavItem = document.getElementById('login');
//     const myBookingsItem = document.getElementById('my-bookings');
    

//     if (loggedIn) {
//         // Change "Sign IN" to "LOGOUT" with the logout button styling
//         loginNavItem.innerHTML = '<button class="logout-btn"><div class="sign"><svg viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path></svg></div><div class="text">Logout</div></button>';
//         // Show "My Bookings" menu item
//         myBookingsItem.style.display = 'block';

//         const logoutButton = document.querySelector('.logout-btn');
//         logoutButton.addEventListener('click', (event) => {
//             event.preventDefault();
//             // Handle logout logic here
//             auth.signOut().then(() => {
//                 console.log('User signed out');
//                 // After logout, update the navigation bar and redirect to home page
//                 updateNavigationBar(false);
//                 window.location.href = 'main.html'; // Assuming main.html is your home page
//             }).catch((error) => {
//                 console.error('Error signing out:', error);
//                 showMessage('Unable to logout. Please try again later.', 'signInMessage');
//             });
//         });
//     } else {
//         // Default: Show "Sign IN"
//         loginNavItem.innerHTML = '<a href="#">Sign IN <span class="arrow">&rarr;</span></a>';
//         // Hide "My Bookings" menu item
//         myBookingsItem.style.display = 'none';
//     }
// }

// // Check initial login status on page load
// const auth = getAuth();
// auth.onAuthStateChanged((user) => {
//     if (user) {
//         // User is signed in.
//         updateNavigationBar(true);
//     } else {
//         // No user is signed in.
//         updateNavigationBar(false);
//     }
// });








// const signUp = document.getElementById("registeruser");
// signUp.addEventListener('click', async (event) => {
//     event.preventDefault();
//     const email = document.getElementById("emailid").value;
//     const password = document.getElementById("passwd").value;

//     console.log("Email:", email);
//     console.log("Password:", password);

//     const auth = getAuth();
//     const db = getFirestore();

//     try {
//         const userCredential = await createUserWithEmailAndPassword(auth, email, password);
//         console.log("User created:", userCredential);

//         const user = userCredential.user;
//         const userData = {
//             email: email
//         };
//         const docRef = doc(db, "users", user.uid);

//         await setDoc(docRef, userData);
//         console.log("User data written to Firestore");

//         showMessage('Account Created Successfully', 'signUpMessage');
//         window.location.href = '/movie-booking/public/index.php';
//     } catch (error) {
//         console.error("Error during registration:", error);
//         const errorCode = error.code;
//         if (errorCode === 'auth/email-already-in-use') {
//             showMessage('Email Address Already Exists!', 'signUpMessage');
//         } else {
//             showMessage('Unable to create user', 'signUpMessage');
//         }
//     }
// });