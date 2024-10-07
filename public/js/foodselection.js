document.addEventListener('DOMContentLoaded', () => {
    const addButtonElements = document.querySelectorAll('.food-add-btn');
    const selectedFoodElement = document.getElementById('selected-food');
    const removeButtonElements = document.querySelectorAll('.food-remove-btn');
    const totalPriceElement = document.getElementById('total');
    const formFood = document.getElementById('form-food');
    const formTotalPrice = document.getElementById('form-total-price'); // Hidden input field
    const promoLink = document.getElementById('promo-link');
    const promoInputContainer = document.getElementById('promo-input-container');
    const applyPromoLink = document.getElementById('apply-promo');
    const promoCodeInput = document.getElementById('promo-code');
    const promoMessage = document.getElementById('promo-message');
    const usedPromoCodeContainer = document.querySelector('.used-promocode-container');
    const appliedPromoCodeElement = document.getElementById('applied-promo-code');
    const gstInfoElement = document.getElementById('gst-info'); // GST info display element

    let selectedFoodItems = [];
    const baseTicketPrice = parseFloat(window.totalPrice); // Use global variable for price
    let totalPrice = baseTicketPrice;
    const formScreen = document.getElementById('form-screen');
    const formTheater = document.getElementById('form-theater');

    // Get the screen and theater values from the page
    const screenValue = document.querySelector('.total-price #scr').nextElementSibling.textContent.trim();
    const theaterValue = document.querySelector('.ticket-details span:nth-child(4)').textContent.trim();

    // Update the hidden input fields with these values
    formScreen.value = screenValue;
    formTheater.value = theaterValue;

    const platformfee = 2;

    function calculateTicketGSTRate(price) {
        return price > 100 ? 18 : 12; // Apply 18% GST if totalPrice > 100, otherwise 12%
    }

    function calculateFoodGSTRate() {
        return 5; // Fixed 5% GST for food items
    }

    function updatePricing() {
        const ticketGSTRate = calculateTicketGSTRate(baseTicketPrice);
        const ticketGSTAmount = (baseTicketPrice * ticketGSTRate) / 100;
        const foodGSTRate = calculateFoodGSTRate();
        const foodTotalPrice = selectedFoodItems.reduce((acc, item) => acc + (item.foodPrice * item.quantity), 0);
        const foodGSTAmount = (foodTotalPrice * foodGSTRate) / 100;
        const finalPrice = baseTicketPrice + ticketGSTAmount + foodTotalPrice + foodGSTAmount + platformfee;

        // Update total price and GST information
        totalPriceElement.innerText = `₹${finalPrice.toFixed(2)}`;
        gstInfoElement.innerText = `Ticket GST (${ticketGSTRate}%): ₹${ticketGSTAmount.toFixed(2)} | Food GST (${foodGSTRate}%): ₹${foodGSTAmount.toFixed(2)}`;
        formTotalPrice.value = finalPrice.toFixed(2); // Store final price in hidden input
    }

    // Initial pricing update
    updatePricing();

    let validPromoCodes = {
        'FIRST50': 50, // 50% off
        'OFFER10': 10    // 10% off
    };

    addButtonElements.forEach(button => {
        button.addEventListener('click', () => {
            const foodName = button.getAttribute('data-food');
            const foodPrice = parseFloat(button.getAttribute('data-price'));

            // Check if food item is already in the list
            const existingItem = selectedFoodItems.find(item => item.foodName === foodName);
            if (existingItem) {
                // Increment quantity if the item already exists
                existingItem.quantity++;
            } else {
                // Add food item to the selected items list with quantity 1
                selectedFoodItems.push({ foodName, foodPrice, quantity: 1 });
            }

            // Update the selected food and total price display
            updateFoodSelection();
        });
    });

    removeButtonElements.forEach(button => {
        button.addEventListener('click', () => {
            const foodName = button.getAttribute('data-food');
            const foodPrice = parseFloat(button.getAttribute('data-price'));

            // Find the food item in the selected items list
            const existingItem = selectedFoodItems.find(item => item.foodName === foodName);
            if (existingItem && existingItem.quantity > 1) {
                // Decrease quantity if it's greater than 1
                existingItem.quantity--;
            } else {
                // Remove the food item if its quantity is 1 or less
                selectedFoodItems = selectedFoodItems.filter(item => item.foodName !== foodName);
            }

            // Update the display
            updateFoodSelection();
        });
    });

    function updateFoodSelection() {
        // Calculate total price of selected food items
        totalPrice = baseTicketPrice + selectedFoodItems.reduce((acc, item) => acc + (item.foodPrice * item.quantity), 0);

        // Update the selected food display
        if (selectedFoodItems.length > 0) {
            const foodNames = selectedFoodItems.map(item => `${item.foodName} x ${item.quantity}`);
            selectedFoodElement.innerText = foodNames.join(', ');
            formFood.value = selectedFoodItems.map(item => `${item.foodName} (${item.quantity})`).join(','); // Set the hidden input for food
        } else {
            selectedFoodElement.innerText = 'No food selected';
            formFood.value = ''; // Clear the hidden input if no food is selected
        }

        // Update pricing information
        updatePricing();
    }

    promoLink.addEventListener('click', (e) => {
        e.preventDefault();
        promoLink.style.display = 'none'; // Hide the 'Use Promocode' link
        promoInputContainer.style.display = 'block'; // Show the input field and 'Apply' button
    });

    // Apply promo code and update the total price
    applyPromoLink.addEventListener('click', (e) => {
        e.preventDefault(); // Prevent form submission
        const enteredCode = promoCodeInput.value.trim().toUpperCase();
        if (validPromoCodes.hasOwnProperty(enteredCode)) {
            const discount = validPromoCodes[enteredCode];

            // Calculate final price after GST and platform fee
            const ticketGSTRate = calculateTicketGSTRate(baseTicketPrice);
            const ticketGSTAmount = (baseTicketPrice * ticketGSTRate) / 100;
            const foodGSTRate = calculateFoodGSTRate();
            const foodTotalPrice = selectedFoodItems.reduce((acc, item) => acc + (item.foodPrice * item.quantity), 0);
            const foodGSTAmount = (foodTotalPrice * foodGSTRate) / 100;
            let finalPrice = baseTicketPrice + ticketGSTAmount + foodTotalPrice + foodGSTAmount + platformfee; // Update final price

            // Apply discount on the final price
            finalPrice = finalPrice - (finalPrice * (discount / 100));

            totalPriceElement.innerText = `₹${finalPrice.toFixed(2)}`;
            promoMessage.style.display = 'none'; // Hide error message

            // Hide promo input and show used promo code
            promoInputContainer.style.display = 'none';
            usedPromoCodeContainer.style.display = 'block';
            appliedPromoCodeElement.innerText = enteredCode; // Show the applied promo code

            formTotalPrice.value = finalPrice.toFixed(2); // Update hidden input with final price after discount
        } else {
            promoMessage.style.display = 'block'; // Show error message if invalid
        }
    });
});
