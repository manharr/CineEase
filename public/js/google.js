import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries
import { getAuth, signInWithPopup, GoogleAuthProvider, FacebookAuthProvider,sendPasswordResetEmail,fetchSignInMethodsForEmail  } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

const firebaseConfig = {
    apiKey: "API",
    authDomain: "login-form-c51dc.firebaseapp.com",
    projectId: "login-form-c51dc",
    storageBucket: "login-form-c51dc.appspot.com",
    messagingSenderId: "725345110293",
    appId: "1:7293:web:8590011c"
};

// Initialize Firebase
const app = initializeApp(firebaseConfig);


const auth = getAuth();

document.addEventListener('DOMContentLoaded', () => {
    const googleLogin = document.getElementById("googleid");
    const fbLogin = document.getElementById("fbid");
    const resetpwd = document.getElementById("resetid");
    const emailInput = document.getElementById("remail");

    googleLogin.addEventListener("click", function () {
        // Create an instance of GoogleAuthProvider
        const provider = new GoogleAuthProvider();

        // Sign in with Google popup
        signInWithPopup(auth, provider)
            .then((result) => {
                const credential = GoogleAuthProvider.credentialFromResult(result);
                const user = result.user;
                console.log("User signed in:", user);
                window.location.href = '/movie-booking/public/index.php'; // Redirect to main.html after successful sign-in
            })
            .catch((error) => {
                const errorCode = error.code;
                const errorMessage = error.message;
                console.error("Google sign-in error:", errorCode, errorMessage);
            });
    });
    fbLogin.addEventListener("click", function () {

        const provider = new FacebookAuthProvider();

        signInWithPopup(auth, provider)
            .then((result) => {
                const credential = FacebookAuthProvider.credentialFromResult(result);
                const user = result.user;
                console.log(user);
                // Redirect or do something after successful login
                window.location.href = '/movie-booking/public/index.php';
            })
            .catch((error) => {
                const errorCode = error.code;
                const errorMessage = error.message;
                console.error(errorMessage);
            });
    });
    resetpwd.addEventListener("click", function (e) {
        e.preventDefault(); // Prevent the default anchor behavior
        const email = emailInput.value;

        if (email) {
            sendPasswordResetEmail(auth, email)
                .then(() => {
                    showMessage("Password reset email sent!!!", 'signInMessage');
                })
                .catch((error) => {
                    const errorCode = error.code;
                    const errorMessage = error.message;
                    console.error("Password reset error:", errorCode, errorMessage);
                });
        } else {
            showMessage("Please enter your email address", 'signInMessage');
        }
    });
});


function showMessage(message, divId) {
    const messageDiv = document.getElementById(divId);
    messageDiv.innerHTML = message;
    messageDiv.style.display = 'block';
    messageDiv.style.opacity = 1;

    setTimeout(function () {
        messageDiv.style.opacity = 0;
        setTimeout(function () {
            messageDiv.style.display = 'none';
        },600); // Adjusted to match transition duration
    }, 2000); // Display message for 2 seconds
}

function updateProfileIcon(user) {
    const profileIcon = document.getElementById('profileIcon');
    const profilePhotoURL = user.photoURL;
    const displayName = user.displayName;
    const email = user.email;

    // Path to your downloaded animated character image
    const fallbackImageURL = "images/pfp.png"; // Adjust the path as needed

    if (profilePhotoURL) {
        console.log("Using profile photo URL:", profilePhotoURL);
        profileIcon.innerHTML = `<img src="${profilePhotoURL}" alt="Profile Photo" class="profile-image">`;
    } else if (displayName) {
        const initials = displayName.split(' ').map(name => name[0]).join('');
        console.log("Using initials:", initials);
        profileIcon.innerHTML = `<span class="profile-initials">${initials.toUpperCase()}</span>`;
    } else if (email) {
        const firstLetter = email.charAt(0).toUpperCase();
        console.log("Using first letter of email:", firstLetter);
        profileIcon.innerHTML = `<span class="profile-initials">${firstLetter}</span>`;
    } else {
        console.log("Using fallback image:", fallbackImageURL);
        // Use the animated character image as the fallback if no photo, display name, or email is available
        profileIcon.innerHTML = `<img src="${fallbackImageURL}" alt="Default Profile" class="profile-image">`;
    }
}
