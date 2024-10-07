document.addEventListener('DOMContentLoaded', () => {
    const goldPrice = parseFloat(window.goldPrice) || 0;
    const platinumPrice = parseFloat(window.platinumPrice) || 0;
    const seats = window.seats || {};
  
    const container = document.querySelector('.container');
    const count = document.getElementById('count');
    const total = document.getElementById('total');
    const selectedSeatsContainer = document.getElementById('selected-seats');
    const bookingForm = document.getElementById('booking-form');
    const formSeats = document.getElementById('form-seats');
  
    const maxSeats = 6;
  
    function getSeatType(rowName) {
        if (['A', 'B', 'C', 'D', 'E', 'F'].includes(rowName)) return 'gold';
        if (['G', 'H'].includes(rowName)) return 'platinum';
        return null;
    }
  
    function updateSelectedCount() {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        const selectedSeatsCount = selectedSeats.length;
        let totalPrice = 0;
  
        selectedSeats.forEach(seat => {
            const rowElement = seat.closest('.row');
            if (rowElement) {
                const rowName = rowElement.getAttribute('data-row')?.trim();
                const seatType = getSeatType(rowName);
  
                if (seatType === 'gold') {
                    totalPrice += goldPrice;
                } else if (seatType === 'platinum') {
                    totalPrice += platinumPrice;
                }
            }
        });
  
        count.innerText = selectedSeatsCount;
        total.innerText = `₹${totalPrice.toFixed(1)}`;
  
        if (selectedSeatsCount > 0) {
            const seatNumbers = Array.from(selectedSeats).map(seat => seat.getAttribute('data-seat'));
            selectedSeatsContainer.innerText = seatNumbers.join(', ');
            formSeats.value = seatNumbers.join(','); // Update hidden field
        } else {
            selectedSeatsContainer.innerText = 'No seats selected';
            formSeats.value = ''; // Clear hidden field
        }
    }
  
    function renderSeats() {
        const goldContainer = document.getElementById('seats-container-gold');
        const platinumContainer = document.getElementById('seats-container-platinum');
  
        const goldRows = ['A', 'B', 'C', 'D', 'E','F'];
        const platinumRows = ['G', 'H'];
  
        let goldSeatHTML = '';
        let platinumSeatHTML = '';
  
        goldRows.forEach(row => {
            goldSeatHTML += `<div class="row" data-row="${row}"><div class="rname">${row}</div>`;
            for (let i = 1; i <= 11; i++) {
                const seatId = `${row}${i}`;
                const seatStatus = seats[seatId] || 'available';
                const seatClass = seatStatus === 'booked' ? 'seat occupied' : 'seat';
                goldSeatHTML += `<div class="${seatClass}" data-seat="${seatId}"></div>`;
            }
            goldSeatHTML += '</div>';
        });
  
        platinumRows.forEach(row => {
            platinumSeatHTML += `<div class="row" data-row="${row}"><div class="rname">${row}</div>`;
            for (let i = 1; i <= 11; i++) {
                const seatId = `${row}${i}`;
                const seatStatus = seats[seatId] || 'available';
                const seatClass = seatStatus === 'booked' ? 'seat occupied' : 'seat';
                platinumSeatHTML += `<div class="${seatClass}" data-seat="${seatId}"></div>`;
            }
            platinumSeatHTML += '</div>';
        });
  
        if (goldContainer) {
            goldContainer.innerHTML = goldSeatHTML;
        }
        if (platinumContainer) {
            platinumContainer.innerHTML = platinumSeatHTML;
        }
    }
  
    renderSeats();
    document.querySelectorAll('.seat.selected').forEach(seat => { seat.classList.remove('selected'); });
    
    container.addEventListener('click', (e) => {
        if (e.target.classList.contains('seat') && !e.target.classList.contains('occupied')) {
            const selectedSeatsCount = document.querySelectorAll('.seat.selected').length;
            if (selectedSeatsCount >= maxSeats && !e.target.classList.contains('selected')) {
                alert(`You cannot select more than ${maxSeats} seats.`);
                return; 
            }
  
            e.target.classList.toggle('selected');
            e.target.textContent = e.target.classList.contains('selected') ? e.target.getAttribute('data-seat') : '';
            updateSelectedCount();
        }
    });
    
  
    bookingForm.addEventListener('submit', async (e) => {
        const selectedSeats = document.querySelectorAll('.seat.selected');
        if (selectedSeats.length === 0) {
            alert('Please select at least one seat to book.');
            e.preventDefault(); 
            return;
        }
  
  
        const result = await response.text();
        if (result.includes("Error") || result.includes("already booked")) {
            alert(result); 
            e.preventDefault(); 
        }
    });
  
    updateSelectedCount();
  });
  