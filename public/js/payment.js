document.addEventListener('DOMContentLoaded', () => {
    const proceedPaymentButton = document.getElementById('proceed-payment-btn');

    proceedPaymentButton.addEventListener('click', function (e) {
        e.preventDefault(); // Prevent the default form submission

        // Get the total price and convert to paise (smallest currency unit)
        const totalPriceText = document.getElementById('total').innerText.replace('₹', '').trim();
        const totalPrice = Math.round(parseFloat(totalPriceText) * 100); // Convert to paise and ensure integer value

        var options = {
            "key": "rzp_test_IfnJTvD7OUgclb", // Enter the Key ID generated from the Dashboard
            "amount": totalPrice, // Amount in paise
            "currency": "INR",
            "name": "CineEase",
            "description": "Test Transaction",
            "image": "https://img.freepik.com/premium-photo/colorful-abstract-design-with-letter-c-it_1221953-46453.jpg",
            "handler": function (response) {
                // Extract payment details
                const paymentId = response.razorpay_payment_id;
                const orderId = response.razorpay_order_id;
                const signature = response.razorpay_signature;

                // Send the payment details to your server
                fetch('index.php?url=SeatBooking/confirmPayment', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: new URLSearchParams({
                        razorpay_payment_id: paymentId,
                        razorpay_order_id: orderId,
                        razorpay_signature: signature
                    })
                }).then(response => {
                    if (response.ok) {
                        // After payment confirmation, submit the seat booking form
                        const form = document.getElementById('food-form');
                        const formData = new FormData(form);

                        return fetch(form.action, {
                            method: 'POST',
                            body: formData
                        });
                    } else {
                        throw new Error('Payment confirmation failed');
                    }
                }).then(response => {
                    if (response.ok) {
                        // Redirect to confirmation page if seat booking is successful
                        window.location.href = "index.php?url=confirmation";
                    } else {
                        throw new Error('Seat booking failed');
                    }
                }).catch(error => {
                    console.log("Error:", error);
                });

            },
            "prefill": {
                "name": "",
                "email": "gaurav.kumar@example.com",
                "contact": "9000090000"
            },
            "notes": {
                "address": "Razorpay Corporate Office"
            },
            "theme": {
                "color": "#3399cc"
            }
        };

        var rzp1 = new Razorpay(options);
        rzp1.on('payment.failed', function (response) {
            console.log(response.error.code);
            console.log(response.error.description);
            console.log(response.error.source);
        });

        rzp1.open();
    });
});


