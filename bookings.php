<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYBUS - Bookings Details</title>
    <?php require('inc/links.php') ?>
</head>

<body class="bg-light">
    <?php
    require('inc/header.php');
    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }
    ?>

    <div class="container">
        <div class="row">
            <div class="col-12 my-5 px-4">
                <h2 class="fw-bold h-font">BOOKINGS</h2>
                <div style="font-size:14px;">
                    <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                    <span class="text-secondary"> > </span>
                    <a href="#" class="text-secondary text-decoration-none">BOOKINGS</a>
                </div>
            </div>

            <?php
            // Query to get booking details for the logged-in user
            $query = "SELECT p.*, bd.*, b.* 
                FROM `payment` p
                INNER JOIN `booking` bd ON p.payment_id = bd.payment_id
                INNER JOIN `buses` b ON bd.bus_id = b.id
                WHERE (bd.status IN ('confirmed', 'pending', 'failed', 'cancelled'))
                AND (bd.user_id = ?)
                ORDER BY bd.booking_id DESC";

            $result = select($query, [$_SESSION['id']], 'i');

            // Display each booking
            while ($data = mysqli_fetch_assoc($result)) {
                $departuretime = date(" h:ia", strtotime($data['departuretime']));
                $arrivaltime = date(" h:ia", strtotime($data['arrivaltime']));
                $date = date("d-m-y | h:ia", strtotime($data['datentime']));

                $status_bg = "";
                $btn = "";
                $cancelbtn = "";

                if ($data['status'] == 'confirmed') {
                    $status_bg = "bg-success";
                    $btn = "<a href='generate_pdf.php?gen_pdf&id=$data[booking_id]' class='btn btn-dark btn-sm shadow-none'>Download PDF</a>";
                    $cancelbtn = "<button class='btn btn-danger btn-sm shadow-none cancel-booking' data-payment-id='$data[payment_id]'>Cancel Booking</button>";
                } else if ($data['status'] == 'pending') {
                    $status_bg = "bg-warning";
                } else if ($data['status'] == 'cancelled') {
                    $status_bg = "bg-danger";
                } else {
                    $status_bg = "bg-info";
                }

                echo <<<bookings
                <div class="col-md-4 mb-4">
                    <div class="card shadow-lg rounded bg-white" style="max-width: 100%;" data-payment-id="{$data['payment_id']}">
                        <div class="card-body">
                            <h5 class="card-title fw-bold">{$data['bus_name']}</h5>
                            <p class="card-text">
                                <b>Source: </b>{$data['source']}  <br>
                                <b>Destination:</b> {$data['destination']} <br>
                                <b>Arrival Time: </b>$arrivaltime <br>
                                <b>Departure Time: </b> $departuretime <br>
                            </p>
        
                            <p class="card-text">
                                <b>Seat Number: </b> {$data['seat_number']} <br>
                                <b>Amount: </b> ₹{$data['trans_amt']} <br>
                                <b>Order ID: </b> {$data['order_id']} <br>
                                <b>Booking Date: </b> $date
                            </p>
        
                            <p>
                                <span class="badge $status_bg">{$data['status']}</span>
                            </p>
        
                            <div class="d-flex justify-content-between">
                                $btn
                                $cancelbtn
                            </div>
                        </div>
                    </div>
                </div>
                bookings;
            }
            ?>
        </div>
    </div>

    <!-- Bootstrap Modal for Confirmation -->
    <div class="modal fade" id="cancelBookingModal" tabindex="-1" aria-labelledby="cancelBookingModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cancelBookingModalLabel">Confirm Cancellation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to cancel this booking?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <button type="button" class="btn btn-danger" id="confirmCancelBtn">Yes, Cancel Booking</button>
                </div>
            </div>
        </div>
    </div>


    <?php require('inc/footer.php'); ?>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const cancelButtons = document.querySelectorAll('.cancel-booking');
            let paymentIdToCancel = null;  // Variable to store the paymentId of the booking to cancel

            cancelButtons.forEach(button => {
                button.addEventListener('click', function () {
                    paymentIdToCancel = this.getAttribute('data-payment-id');
                    // Show the modal
                    const cancelModal = new bootstrap.Modal(document.getElementById('cancelBookingModal'));
                    cancelModal.show();
                });
            });

            // When the user confirms the cancellation
            document.getElementById('confirmCancelBtn').addEventListener('click', function () {
                cancelBooking(paymentIdToCancel);
                const cancelModal = bootstrap.Modal.getInstance(document.getElementById('cancelBookingModal'));
                cancelModal.hide();  // Close the modal after confirmation
            });

            function cancelBooking(paymentId) {
                let xhr = new XMLHttpRequest();
                xhr.open("POST", "ajax/cancel_booking.php", true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

                xhr.onload = function () {
                    console.log('Server Response:', this.responseText);

                    // Handle response as a simple text (either 1 or 0)
                    const response = this.responseText.trim(); // trim in case there's extra whitespace

                    if (response === "1") {
                        showAlert('success', 'Booking cancelled successfully!');

                        // Find the closest card to update
                        const bookingCard = document.querySelector(`[data-payment-id='${paymentId}']`);

                        if (bookingCard) {
                            const badge = bookingCard.querySelector('.badge');
                            badge.classList.replace('bg-warning', 'bg-danger');
                            badge.classList.replace('bg-success', 'bg-danger');
                            badge.textContent = 'Cancelled';

                            const amountText = bookingCard.querySelector('p b:nth-child(3)');
                            amountText.innerHTML = `<b>Transaction Status: </b> Cancelled`;

                            const cancelButton = bookingCard.querySelector('.cancel-booking');
                            cancelButton.remove();

                            const downloadButton = bookingCard.querySelector('.btn-dark');
                            if (downloadButton) {
                                downloadButton.remove();
                            }
                        } else {
                            console.error(`Booking card with paymentId ${paymentId} not found.`);
                        }

                    } else {
                        showAlert('danger', 'Failed to cancel booking.');
                    }
                };
                xhr.send('payment_id=' + paymentId);
            };
        });

    </script>
</body>

</html>