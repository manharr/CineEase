document.addEventListener('DOMContentLoaded', function () {
    // Dropdown functionality
    const dropdownItems = document.querySelectorAll('.dropdown ul li a');

    function handleSelection(item) {
        dropdownItems.forEach(otherItem => {
            otherItem.classList.remove('selected');
            otherItem.querySelector('.checkmark').style.display = 'none';
        });

        item.classList.add('selected');
        item.querySelector('.checkmark').style.display = 'inline-block';

        const selectedValue = item.getAttribute('data-value');
        localStorage.setItem('selectedTheatre', selectedValue);
    }

    function updateUI() {
        const storedValue = localStorage.getItem('selectedTheatre');
        if (storedValue) {
            dropdownItems.forEach(item => {
                if (item.getAttribute('data-value') === storedValue) {
                    handleSelection(item);
                }
            });
        }
    }

    updateUI();

    dropdownItems.forEach(item => {
        item.addEventListener('click', function (event) {
            event.preventDefault();
            handleSelection(item);
            reloadWithIndicator();
        });
    });

    function reloadWithIndicator() {
        const loadingOverlay = document.createElement('div');
        loadingOverlay.classList.add('loading-overlay');
        document.body.appendChild(loadingOverlay);

        setTimeout(() => {
            location.reload();
        }, 1000);
    }

    // Dropdown button functionality
    const dropdownBtn = document.querySelector('.dropdown-btn');
    const dropdown = document.querySelector('.dropdown');

    dropdownBtn.addEventListener('click', function (event) {
        event.stopPropagation();
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });

    document.addEventListener('click', function (event) {
        if (!dropdownBtn.contains(event.target) && !dropdown.contains(event.target)) {
            dropdown.style.display = 'none';
        }
    });

    dropdown.addEventListener('click', function (event) {
        event.stopPropagation();
    });






    // Sliding menu functionality
    const menuButton = document.querySelector('.setting-btn');
    const slidingMenu = document.querySelector('.sliding-menu');
    const closeButton = document.getElementById('cross');

    // Toggle sliding menu when menu button is clicked
    menuButton.addEventListener('click', function (event) {
        event.stopPropagation();
        slidingMenu.classList.toggle('active');
    });

    // Close sliding menu when clicking outside of it or on the close button
    document.addEventListener('click', function (event) {
        const target = event.target;
        if (!slidingMenu.contains(target) && !menuButton.contains(target)) {
            slidingMenu.classList.remove('active');
        }
    });

    closeButton.addEventListener('click', function () {
        slidingMenu.classList.remove('active');
    });



    // Login/Register form functionality
    const x = document.getElementById("loginpage");
    const y = document.getElementById("registerpage");
    const z = document.getElementById("btn");
    const overlay = document.getElementById("overlay");
    const signupLink = document.getElementById("signup-link");
    const loginLink = document.getElementById("login-link");

    function registerpage() {
        x.style.left = "-400px";
        y.style.left = "0px";
        z.style.left = "110px";
        overlay.style.display = "block";
    }

    function loginpage() {
        x.style.left = "0";
        y.style.left = "450px";
        z.style.left = "0";
        overlay.style.display = "block";
    }

    // Event listeners for login and register buttons
    const loginBtn = document.querySelector('.toggle-btn:nth-of-type(1)');
    const registerBtn = document.querySelector('.toggle-btn:nth-of-type(2)');

    loginBtn.addEventListener('click', function () {
        loginpage();
    });

    registerBtn.addEventListener('click', function () {
        registerpage();
    });

    signupLink.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default behavior of anchor tag
        registerpage();
    });

    loginLink.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent default behavior of anchor tag
        loginpage();
    });

    // Event listener for clicking on the overlay to hide the form
    overlay.addEventListener("click", function (e) {
        if (e.target === overlay) {
            toggleForm(false);
        }
    });

    // New code to handle showing and hiding the form
    const loginNavBtn = document.getElementById("login");
    const formContainer = document.querySelector(".form");

    const toggleForm = (show) => {
        if (show) {
            formContainer.style.display = "block";
            overlay.style.display = "block";
        } else {
            formContainer.style.display = "none";
            overlay.style.display = "none";
        }
    };

    //SUBSCRIPTION POPUP
    const subscribeBtn = document.getElementById('subscribeBtn');
    const subscriptionPopup = document.getElementById('subscriptionPopup');
    const closeSubscriptionPopup = document.getElementById('closeSubscriptionPopup');

    // Show subscription popup when subscribe button is clicked
    subscribeBtn.addEventListener('click', function () {
        subscriptionPopup.style.display = 'block';
    });

    // Close subscription popup when close button (X) is clicked
    closeSubscriptionPopup.addEventListener('click', function () {
        subscriptionPopup.style.display = 'none';
    });

    // Close subscription popup if user clicks outside of it
    window.addEventListener('click', function (event) {
        if (event.target === subscriptionPopup) {
            subscriptionPopup.style.display = 'none';
        }
    });



    // Password match validation
    const form = document.getElementById('registerpage');
    const passwordInput = form.querySelector('input[name="password"]');
    const repeatPasswordInput = form.querySelector('input[name="repeat_password"]');
    const passwordError = document.getElementById('passwordError');

    form.addEventListener('submit', function (event) {
        const password = passwordInput.value;
        const repeatPassword = repeatPasswordInput.value;

        if (password !== repeatPassword) {
            passwordError.textContent = 'Passwords do not match!';
            event.preventDefault(); // Prevent form submission
        } else {
            passwordError.textContent = ''; // Clear error message if passwords match
        }
    });

    passwordInput.addEventListener('input', function () {
        if (passwordInput.value.trim() === '') {
            passwordError.textContent = ''; // Clear error message if password field is empty
        }
    });



    // Event listener for the login button in the navigation to show the form
    loginNavBtn.addEventListener("click", (e) => {
        e.preventDefault();
        toggleForm(true);
    });


    //contact us
    // Contact Us form functionality
    const contactUsLink = document.getElementById('contactUsLink');
    const contactForm = document.getElementById('contactForm');
    const overlay2 = document.createElement('div'); // Create a new overlay element
    overlay2.classList.add('contact-overlay'); // Add appropriate class
    document.body.appendChild(overlay2); // Add overlay to the body

    // Toggle display of the contact form and overlay
    const toggleContactForm = () => {
        if (contactForm.style.display === "block") {
            contactForm.style.display = "none";
            overlay2.style.display = "none"; // Hide overlay
        } else {
            contactForm.style.display = "block";
            overlay2.style.display = "block"; // Show overlay
        }
    };

    // Event listener to show/hide the Contact Us form
    contactUsLink.addEventListener('click', function (event) {
        event.preventDefault();
        toggleContactForm(); // Toggle form and overlay
    });

    // Close the contact form and overlay when clicking outside
    window.addEventListener('click', function (event) {
        if (event.target === overlay2 || (event.target !== contactForm && !contactForm.contains(event.target) && event.target !== contactUsLink)) {
            contactForm.style.display = "none";
            overlay2.style.display = "none"; // Hide overlay when clicking outside
        }
    });








    // LOADER
    function showLoader() {
        document.getElementById('loader').style.display = 'flex';
    }

    // Function to hide the loader
    function hideLoader() {
        document.getElementById('loader').style.display = 'none';
    }

    // Attach event listener to handle navigation links
    document.addEventListener('click', function (event) {
        // Check if the clicked element is a navigation link
        let target = event.target;
        while (target && target.tagName !== 'A') {
            target = target.parentElement;
        }

        if (target && target.tagName === 'A') {
            // Add a condition to check if the URL should show the loader
            if (target.classList.contains('nav-link')) {
                showLoader();
            }
        }
    });

    // Hide loader on page load
    window.addEventListener('load', function () {
        hideLoader();
    });
});
