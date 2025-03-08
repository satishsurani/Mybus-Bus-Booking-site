const payNowButton = document.getElementById('PayNow');
let selectedSeats = [];
const seatButtons = document.querySelectorAll('.seat');
const selectedSeatsDiv = document.getElementById('selectedSeats');

let booking_form = document.getElementById('booking_form');
let info_loader = document.getElementById('info_loader');
let pay_info = document.getElementById('pay_info');

// Get maxSeats from the form input dynamically
const maxSeats = parseInt(booking_form.elements['passengers'].value);
console.log(maxSeats); // Check the value of maxSeats

// Seat selection logic
seatButtons.forEach(button => {
    button.addEventListener('click', function () {
        const seatId = this.getAttribute('data-seat-id');
        const seatNumber = this.getAttribute('data-seat-number');
        const seatImage = this.querySelector('img');

        // If the seat is already selected, deselect it
        if (selectedSeats.some(seat => seat.seatId === seatId)) {
            selectedSeats = selectedSeats.filter(seat => seat.seatId !== seatId);
            this.classList.remove('selected');
            removeSelectedSeat(seatNumber); // Remove the seat number from the div

            // Reset the image to the available seat
            seatImage.src = 'images/seat.png';
        }
        // If the seat is not selected and max seats limit is not reached, select it
        else if (selectedSeats.length < maxSeats) {
            selectedSeats.push({ seatId, seatNumber });
            this.classList.add('selected');
            addSelectedSeat(seatNumber); // Add the seat number to the div

            // Change the image to indicate booked seat
            seatImage.src = 'images/book-seat.png';
        }

        // Update the selected seats display
        selectedSeatsDiv.innerHTML = selectedSeats.map(seat => {
            return `<span class="badge badge-pill bg-light text-dark">Seat ${seat.seatNumber}</span>`;
        }).join('');
        
        updatePayButtonState();
        checkAvailability();
    });
});

// Enable or disable the Pay Now button based on selected seats
function updatePayButtonState() {
    console.log(selectedSeats.length); // Log the length of selectedSeats array

    if (selectedSeats.length === maxSeats) {
        payNowButton.removeAttribute('disabled');
    } else {
        payNowButton.setAttribute('disabled', true);
    }
}

// Add a selected seat to the display
function addSelectedSeat(seatNumber) {
    const seatElement = document.createElement('span');
    seatElement.classList.add('selected-seat-display', 'badge', 'rounded-pill', 'bg-light', 'text-dark');
    seatElement.textContent = `Seat ${seatNumber}`;
    selectedSeatsDiv.appendChild(seatElement);
}

// Remove a deselected seat from the display
function removeSelectedSeat(seatNumber) {
    const seatElements = selectedSeatsDiv.getElementsByClassName('selected-seat-display');
    for (let i = 0; i < seatElements.length; i++) {
        if (seatElements[i].textContent === `Seat ${seatNumber}`) {
            selectedSeatsDiv.removeChild(seatElements[i]);
            break;
        }
    }
}

let amount;

// Check availability of seats and calculate total amount
function checkAvailability() {
    let source_val = booking_form.elements['source'].value;
    let destination_val = booking_form.elements['destination'].value;
    let passengers_val = booking_form.elements['passengers'].value;
    let date = booking_form.elements['date'].value;
    let name = booking_form.elements['name'].value;
    let number = booking_form.elements['phonenum'].value;
    let email = booking_form.elements['email'].value;

    // Check if no seats are selected
    if (selectedSeats.length === 0) {
        pay_info.classList.remove('d-none');
        pay_info.classList.replace('text-dark', 'text-danger');
        pay_info.innerHTML = 'Please select seats.';
        return; 
    }

    let selectedSeatsString = selectedSeats.map(seat => seat.seatId + '-' + seat.seatNumber).join(',');

    // If all required fields are filled, make the availability request
    if (source_val !== '' && destination_val !== '' && passengers_val !== '' && selectedSeats.length > 0) {
        pay_info.classList.add('d-none');
        pay_info.classList.replace('text-dark', 'text-danger');
        info_loader.classList.remove('d-none');

        let data = new FormData();
        data.append('check_availability', '1');
        data.append('name', name);
        data.append('source', source_val);
        data.append('destination', destination_val);
        data.append('passengers', passengers_val);
        data.append('date', date);
        data.append('number', number);
        data.append('email', email);
        data.append('selectedSeats', selectedSeatsString);

        let xhr = new XMLHttpRequest();
        xhr.open('POST', 'ajax/confirm_booking.php', true);

        xhr.onload = function () {
            let response = JSON.parse(this.responseText);

            // Handle the response
            if (response) {
                amount = response.payment;
                pay_info.innerHTML = `Total Amount to Pay: ₹${response.payment}`;
                pay_info.classList.replace('text-danger', 'text-dark');
                pay_info.classList.remove('d-none');
            } else {
                pay_info.innerHTML = 'There was an issue with the availability check. Please try again later.';
                pay_info.classList.replace('text-dark', 'text-danger');
                pay_info.classList.remove('d-none');
            }

            info_loader.classList.add('d-none');
            updatePayButtonState();
        };

        xhr.send(data);
    }
}

// Handle the payment process when the "Pay Now" button is clicked
payNowButton.addEventListener('click', function (e) {
    e.preventDefault();

    // Payment method (you can change this if needed)
    let paymentOption = "netbanking";
    let payAmount = amount;

    let requestUrl = "ajax/submitpayment.php";
    let formData = new FormData();
    formData.append('paymentOption', paymentOption);
    formData.append('payAmount', payAmount);
    formData.append('action', 'payOrder');

    fetch(requestUrl, {
        method: 'POST',
        body: formData
    })
        .then(response => response.text())
        .then(data => {
            console.log("paymentdata:" + data);
            let jsonResponse = JSON.parse(data);
            let orderID = jsonResponse.payment.order_number;
            let paymentID = jsonResponse.payment.payment_id;

            let options = {
                "key": jsonResponse.payment.razorpay_key, // Your Razorpay Key
                "amount": jsonResponse.payment.amount * 100, // Amount in paise
                "currency": "INR",
                "name": "MYBUS",
                "description": jsonResponse.payment.description,
                "order_id": jsonResponse.payment.rpay_order_id, // Razorpay order ID
                "handler": function (response) {
                    window.location.replace("payment-success.php?oid=" + jsonResponse.payment.order_number + "&rp_payment_id=" + response.razorpay_payment_id + "&rp_signature=" + response.razorpay_signature + "&pid=" + jsonResponse.payment.payment_id);
                },
                "modal": {
                    "ondismiss": function () {
                        window.location.replace("payment-failed.php?oid=" + jsonResponse.payment.order_number);
                    }
                },
                "prefill": {
                    "name": jsonResponse.payment.name,
                    "email": jsonResponse.payment.email,
                    "mobile": jsonResponse.payment.number
                },
                "notes": {
                    "address": "MYBUS"
                },
                "theme": {
                    "color": "#AD8B3A"
                }
            };
            
            let rzp1 = new Razorpay(options);
            rzp1.open();
            
        })
        .catch(error => {
            console.error('Payment request failed:', error);
            alert('There was an error processing your payment request. Please try again later.');
        });
});
