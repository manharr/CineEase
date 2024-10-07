import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
import { getAuth, onAuthStateChanged, updateProfile } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";
import { getFirestore, doc, updateDoc, getDoc, setDoc } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-firestore.js";

const firebaseConfig = {
    apiKey: "AIzaSyDvk4BSP8zqonNGmYRUsqFbXuUTu8Kw0MM",
    authDomain: "login-form-c55dc.firebaseapp.com",
    projectId: "login-form-c55dc",
    storageBucket: "login-form-c55dc.appspot.com",
    messagingSenderId: "725345110293",
    appId: "1:725345110293:web:8590de145d691aefed011c"
};

const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
const db = getFirestore(app);

const firstName = document.getElementById('firstName');
const lastName = document.getElementById('lastName');
const phone = document.getElementById('phone');
const email = document.getElementById('email');
const saveChanges = document.getElementById('saveChanges');
const message = document.getElementById('message');

onAuthStateChanged(auth, async (user) => {
if (user) {
const userDocRef = doc(db, 'users', user.uid);
const userDoc = await getDoc(userDocRef);

if (userDoc.exists()) {
    // Populate the form with user data if the document exists
    const userData = userDoc.data();
    firstName.value = userData.firstName || '';
    lastName.value = userData.lastName || '';
    phone.value = userData.phone || '';
    email.value = user.email;
} else {
    // If no document exists, initialize fields with empty values
    firstName.value = '';
    lastName.value = '';
    phone.value = '';
    email.value = user.email;
}

saveChanges.addEventListener('click', async () => {
    const newFirstName = firstName.value.trim();
    const newLastName = lastName.value.trim();
    const newPhone = phone.value.trim();

    try {
        // Use set() instead of update() to create a new document if it doesn't exist
        await setDoc(userDocRef, {
            firstName: newFirstName,
            lastName: newLastName,
            phone: newPhone,
            email: user.email  // store email too in case needed
        }, { merge: true });  // merge: true ensures existing fields are not overwritten
        
        showMessage('Profile updated successfully!', 'success');
    } catch (error) {
        console.error('Error updating profile:', error);
        showMessage('Error updating profile: ' + error.message, 'error');
    }
});

function showMessage(msg, type) {
    message.textContent = msg;
    message.className = 'message ' + type;
    message.style.display = 'block';
    setTimeout(() => {
        message.style.display = 'none';
    }, 4000);
}
} else {
// Redirect to login page if not logged in
window.location.href = '/movie-booking/public/index.php';
}
});
