<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYBUS - Payment Status</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <?php
    // Include PHPMailer
    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;

    require 'vendor/autoload.php';
    require_once('admin/inc/db_config.php');
    require('admin/inc/essentials.php');
    date_default_timezone_set("Asia/Calcutta");

    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }

    if (isset($_GET['oid']) && isset($_GET['rp_payment_id']) && isset($_GET['rp_signature'])) {
        $order_id = $_GET['oid'];
        $payment_id = $_GET['pid'];
        $signature = $_GET['rp_signature'];

        $query = "SELECT * FROM payment WHERE order_id = ?";
        $booking_res = select($query, [$order_id], 's');

        if (mysqli_num_rows($booking_res) > 0) {
            $booking_data = mysqli_fetch_assoc($booking_res);
            $booking_id = $booking_data['booking_id'];
            $update_query = "UPDATE payment 
                         SET trans_status = 'success'
                         WHERE order_id = ?";

            update($update_query, [$order_id], 's');

            $update_query = "UPDATE booking 
                         SET status = 'confirmed', payment_id =?
                         WHERE booking_id = ?";

            update($update_query, [$payment_id, $booking_id], 'ii');

            $seatIds = $_SESSION['user']['selectedSeatIds'];

            if (is_array($seatIds)) {
                foreach ($seatIds as $seatId) {
                    $update_seat_query = "UPDATE seats SET status = 'booked', booking_id = ? WHERE id = ?";
                    update($update_seat_query, [$booking_id, $seatId], 'ii');
                }
            } else {
                $seatIdsArray = explode(',', $seatIds);
                foreach ($seatIdsArray as $seatId) {
                    $update_seat_query = "UPDATE seats SET status = 'booked', booking_id = ? WHERE id = ?";
                    update($update_seat_query, [$booking_id, $seatId], 'ii');
                }
            }
            $seatNumbers = $_SESSION['user']['selectedSeatNumbers'];
            $seatNumbers = implode(',', $seatNumbers);

            $mail = new PHPMailer(true);
            try {
                // Server settings
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'luxavenu@gmail.com';
                $mail->Password = 'bloe aqhl orwb bovj';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                // Recipients
                $mail->setFrom('services@mybus.com', 'MYBUS');
                $mail->addAddress($_SESSION['user']['email'], $_SESSION['user']['name']);

                // Content
                $mail->isHTML(true);  // Tell PHPMailer to send as HTML
                $mail->CharSet = 'UTF-8';  // Set the charset to UTF-8
                $mail->Subject = 'Booking Confirmation - MYBUS';

                // Make sure we are closing HTML tags properly and escaping dynamic data
                $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body {
                                font-family: 'Arial', sans-serif;
                                color: white;
                                background-color: #f4f4f4;
                                margin: 0;
                                padding: 0;
                            }
                            .email-container {
                                max-width: 600px;
                                margin: 0 auto;
                                background-color: #ffffff;
                                padding: 30px;
                                border-radius: 12px;
                                box-shadow: 0 8px 12px rgba(0, 0, 0, 0.1);
                            }
                            h3 {
                                color: #AD8B3A;
                                text-align: center;
                                font-size: 24px;
                            }
                            p {
                                font-size: 16px;
                                line-height: 1.6;
                                margin-bottom: 20px;
                                color: black;
                            }
                            .booking-details {
                                background-color: #e8f4e8;
                                padding: 20px;
                                margin-top: 20px;
                                border-radius: 8px;
                                border: 1px solid #ddd;
                            }
                            .booking-details p {
                                margin: 8px 0;
                                font-size: 14px;
                            }
                            .button {
                                background-color: #AD8B3A;
                                color: white;
                                padding: 12px 20px;
                                text-align: center;
                                display: inline-block;
                                text-decoration: none;
                                border-radius: 6px;
                                margin-top: 30px;
                                font-size: 16px;
                            }
                            .footer {
                                font-size: 14px;
                                color: black;
                                text-align: center;
                                margin-top: 30px;
                                padding-top: 10px;
                                border-top: 1px solid #ddd;
                            }
                            .footer a {
                                color: #AD8B3A;
                                text-decoration: none;
                                font-weight: bold;
                            }
                        </style>
                    </head>
                    <body>
                        <div class='email-container'>
                            <h3>Dear " . htmlspecialchars($_SESSION['user']['name'], ENT_QUOTES, 'UTF-8') . ",</h3>
                            <p>We are excited to inform you that your booking has been successfully confirmed!</p>
                            
                            <div class='booking-details'>
                                <p><strong>Booking ID:</strong> " . htmlspecialchars($order_id, ENT_QUOTES, 'UTF-8') . "</p>
                                <p><strong>Bus Name:</strong> " . htmlspecialchars($_SESSION['bus']['name'], ENT_QUOTES, 'UTF-8') . "</p>
                                <p><strong>Amount Paid:</strong> ₹" . htmlspecialchars($_SESSION['bus']['payment'], ENT_QUOTES, 'UTF-8') . "</p>
                                <p><strong>Bus Route:</strong> " . htmlspecialchars($_SESSION['bus']['source'], ENT_QUOTES, 'UTF-8') . " to " . htmlspecialchars($_SESSION['bus']['destination'], ENT_QUOTES, 'UTF-8') . "</p>
                                <p><strong>Departure Date:</strong> " . htmlspecialchars($_SESSION['bus']['date'], ENT_QUOTES, 'UTF-8') . "</p>
                                <p><strong>Seats:</strong> " . htmlspecialchars($seatNumbers, ENT_QUOTES, 'UTF-8') . "</p>
                            </div>
                            
                            <p>If you have any questions, feel free to reach out to us at any time.</p>
                            
                            <p>Thank you for choosing MYBUS! We look forward to having you on board.</p>

                            <div class='footer'>
                                <p>&copy; " . date("Y") . " MYBUS. All rights reserved.</p>
                                <p>Want to unsubscribe from our emails? <a href='#'>Click here</a></p>
                            </div>
                        </div>
                    </body>
                    </html>
                ";

                // Send the email
                $mail->send();
            } catch (Exception $e) {
                alert('failure', 'Booking successful, but email failed to send. Error: ' . $mail->ErrorInfo);
            }


            echo <<<data
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-5 mb-3 px-4">
                            <h2 class="fw-bold h-font">Payment Success</h2>
                        </div>
                        <div class="col-12 px-4">
                            <p class="fw-bold alert alert-success">
                                <i class="bi bi-check-circle-fill"></i>
                                Payment successful! Your booking has been confirmed and Email has been sent successfully
                                <br><br>
                                <a href='bookings.php'>Go to My Bookings</a>
                            </p>
                        </div>
                    </div>
                </div>
            data;

        } else {
            $update_query = "UPDATE payment 
                         SET trans_status = 'failed'
                         WHERE order_id = ?";
            update($update_query, [$order_id], 's');

            echo <<<data
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-5 mb-3 px-4">
                            <h2 class="fw-bold h-font">Payment Failed</h2>
                        </div>
                        <div class="col-12 px-4">
                            <p class="fw-bold alert alert-danger">
                                <i class="bi bi-x-circle-fill"></i>
                                Payment failed! Your booking could not be confirmed.
                                <br><br>
                                <a href='bus.php'>Try Again</a>
                            </p>
                        </div>
                    </div>
                </div>
            data;
        }
    } else {
        redirect('payment-failed.php');
    }
    ?>

    <?php require('inc/footer.php'); ?>
</body>

</html>