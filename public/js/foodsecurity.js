import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

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
        let firebaseUid = null;

        onAuthStateChanged(auth, (user) => {
            if (user) {
                firebaseUid = user.uid;
                document.getElementById('form-firebase-uid').value = firebaseUid;
            } else {
                alert("Please Log in to continue");
                window.location.href = 'index.php';
            }
        });