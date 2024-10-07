import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
import { getAuth, signInWithEmailAndPassword  } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";
import { getFirestore, setDoc, doc, onSnapshot  } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-firestore.js";


const firebaseConfig = {
    apiKey: "AIzM",
    authDomain: "login-form-c51dc.firebaseapp.com",
    projectId: "login-form-c51dc",
    storageBucket: "login-form-c51dc.appspot.com",
    messagingSenderId: "725345110293",
    appId: "1:7293:web:8590011c"
};

const app = initializeApp(firebaseConfig);


console.log("Firebase initialized");

document.addEventListener('DOMContentLoaded', () => {
    // Login User
    const loginButton = document.getElementById('logbtn');
    loginButton.addEventListener('click', async (event) => {
        event.preventDefault();

        const email = document.getElementById('remail').value;
        const password = document.getElementById('rpasswd').value;

        // Validate email
        if (!email.trim()) {
            showMessage('Please enter an email address.', 'signInMessage');
            return;
        } else if (!isValidEmail(email)) {
            showMessage('Please enter a valid email address.', 'signInMessage');
            return;
        }

        if (!password.trim()) {
            showMessage('Please enter a password.', 'signInMessage');
            return;
        }
        
        const auth = getAuth();

        try {
            const userCredential = await signInWithEmailAndPassword(auth, email, password);
            console.log('User logged in:', userCredential.user);
            showMessage('Logged in successfully!', 'signInMessage');
            
            window.location.href = '/movie-booking/public/index.php'; 
        } catch (error) {
            console.error('Error signing in:', error);
            const errorCode = error.code;
            if (errorCode === 'auth/invalid-credential') {
                showMessage('Invalid password. Please try again.', 'signInMessage');
            }if (errorCode === 'auth/user-not-found') {
                showMessage('Email not registered. Please register.', 'signInMessage');
            } else {
                showMessage("Invalid email or password", 'signInMessage');
            }
        }
    });
});

function isValidEmail(email) {
    return /\S+@\S+\.\S+/.test(email);
}

// Function to display message in messageDiv
function showMessage(message, divId) {
    const messageDiv = document.getElementById(divId);
    messageDiv.innerHTML = message;
    messageDiv.style.display = 'block';
    messageDiv.style.opacity = 1;

    setTimeout(function () {
        messageDiv.style.opacity = 0;
        setTimeout(function () {
            messageDiv.style.display = 'none';
        },300); 
    }, 5000); 
}

function updateNavigationBar(loggedIn) {
    const auth = getAuth();
    const loginNavItem = document.getElementById('login');
    const profileMenu = document.getElementById('profile-menu');
    const profileDropdown = document.getElementById('profileDropdown');
    const profileIcon = document.getElementById('profileIcon');
    const logoutButton = document.getElementById('logout');
    const myBookingsItem = document.getElementById('my-bookings');
    const myBookingsItem2 = document.getElementById('my-bookings2');
    let dropdownVisible = false;

    if (loggedIn) {
        loginNavItem.style.display = 'none';

        profileMenu.style.display = 'block';

        auth.onAuthStateChanged((user) => {
            if (user) {
                updateProfileIcon(user);

                const db = getFirestore();
                onSnapshot(doc(db, "users", user.uid), (doc) => {
                    const data = doc.data();
                    if (data) {
                        user.reload().then(() => updateProfileIcon(user));
                    }
                });
            }
        });

        //show bookings and edit profile
        myBookingsItem.style.display = 'block';
        myBookingsItem2.style.display = 'block';
        profileIcon.addEventListener('click', (event) => {
            event.stopPropagation(); // Prevent click event from bubbling up
            dropdownVisible = !dropdownVisible;
            profileDropdown.style.display = dropdownVisible ? 'block' : 'none';
        });

        // Add event listener for logout
        logoutButton.addEventListener('click', (event) => {
            event.preventDefault();
            auth.signOut().then(() => {
                console.log('User signed out');
                updateNavigationBar(false);
                window.location.href = '/movie-booking/public/index.php'; 
            }).catch((error) => {
                console.error('Error signing out:', error);
                showMessage('Unable to logout. Please try again later.', 'signInMessage');
            });
        });

        // Close the dropdown menu if user clicks outside of it
        document.addEventListener('click', (event) => {
            if (!profileMenu.contains(event.target) && !profileDropdown.contains(event.target)) {
                profileDropdown.style.display = 'none';
                dropdownVisible = false;
            }
        });

    } else {
        loginNavItem.style.display = 'block';

        profileMenu.style.display = 'none';
    }
}

function updateProfileIcon(user) {
    const profileIcon = document.getElementById('profileIcon');
    const profilePhotoURL = user.photoURL;
    const displayName = user.displayName;
    const email = user.email;

    // console.log('Profile Photo URL:', profilePhotoURL);
    // console.log('Display Name:', displayName);
    // console.log('Email:', email);

    if (profilePhotoURL) {
        profileIcon.innerHTML = `<img src="${profilePhotoURL}" alt="Profile Photo" class="profile-image">`;
    } else if (displayName) {
        const initials = displayName.split(' ').map(name => name[0]).join('');
        profileIcon.innerHTML = `<span class="profile-initials">${initials.toUpperCase()}</span>`;
    } else if (email) {
        const firstLetter = email.charAt(0).toUpperCase();
        profileIcon.innerHTML = `<span class="profile-initials">${firstLetter}</span>`;
    } else {
        profileIcon.innerHTML = `<span class="profile-initials">?</span>`;
    }
}


// Check initial login status on page load
const auth = getAuth();
auth.onAuthStateChanged((user) => {
    if (user) {
        updateNavigationBar(true);
    } else {
        updateNavigationBar(false);
    }
});
