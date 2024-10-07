document.addEventListener('DOMContentLoaded', () => {
    const goldPrice = parseFloat(window.goldPrice) || 0;
    const platinumPrice = parseFloat(window.platinumPrice) || 0;
    const seats = window.seats || {}; 

    const container = document.querySelector('.container');
    const count = document.getElementById('count');
    const total = document.getElementById('total');
    const selectedSeatsContainer = document.getElementById('selected-seats');
    const bookingForm = document.getElementById('food-form');
    const formFood = document.getElementById('form-food');

    function updateSelectedCount() {
        const selectedFoods = document.querySelectorAll('input[name="food[]"]:checked');
        const selectedFoodsCount = selectedFoods.length;
        let totalPrice = 0;

        selectedFoods.forEach(food => {
            const price = parseFloat(food.getAttribute('data-price')) || 0;
            totalPrice += price;
        });

        count.innerText = selectedFoodsCount;
        total.innerText = `₹${totalPrice.toFixed(1)}`;  

        if (selectedFoodsCount > 0) {
            const foodIds = Array.from(selectedFoods).map(food => food.value);
            selectedSeatsContainer.innerText = foodIds.join(', ');
            formFood.value = foodIds.join(','); 
        } else {
            selectedSeatsContainer.innerText = 'No food selected';
            formFood.value = ''; 
        }
    }

    document.querySelectorAll('input[name="food[]"]').forEach(food => {
        food.addEventListener('change', updateSelectedCount);
    });

    updateSelectedCount();

    bookingForm.addEventListener('submit', async (e) => {
        const selectedFoods = document.querySelectorAll('input[name="food[]"]:checked');
        if (selectedFoods.length === 0) {
            alert('Please select at least one food item to book.');
            e.preventDefault(); 
            return;
        }

    });
});
