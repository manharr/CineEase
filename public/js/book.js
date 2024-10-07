import { initializeApp } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-app.js";
        import { getAuth, onAuthStateChanged } from "https://www.gstatic.com/firebasejs/10.12.3/firebase-auth.js";

        const firebaseConfig = {
    apiKey: "API",
    authDomain: "login-form-c51dc.firebaseapp.com",
    projectId: "login-form-c51dc",
    storageBucket: "login-form-c51dc.appspot.com",
    messagingSenderId: "725345110293",
    appId: "1:7293:web:8590011c"
};

        const app = initializeApp(firebaseConfig);
        const auth = getAuth(app);

        onAuthStateChanged(auth, (user) => {
            if (user) {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'bookings.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        document.body.innerHTML = xhr.responseText;
                    }
                };
                xhr.send('firebase_uid=' + user.uid);
            } else {
                window.location.href = '/movie-booking/public/index.php';
            }
        });
        
