<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYBUS - Payment Failed</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-light">
    <?php require('inc/header.php'); ?>

    <?php
    require_once('admin/inc/db_config.php');
    require('admin/inc/essentials.php');
    date_default_timezone_set("Asia/Calcutta");

    if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
        redirect('index.php');
    }

    if (isset($_GET['oid']) && isset($_GET['paymentid'])) {
        $order_id = $_GET['oid'];
        $payment_id = $_GET['pid'];
        $reason = isset($_GET['reason']) ? $_GET['reason'] : 'Unknown error';

        $query = "SELECT * FROM payment WHERE order_id = ?";
        $booking_res = select($query, [$order_id], 's');

        if (mysqli_num_rows($booking_res) > 0) {
            $booking_data = mysqli_fetch_assoc($booking_res);
            $booking_id = $booking_data['booking_id'];
            $update_query = "UPDATE payment 
                             SET trans_status = 'failed'
                             WHERE order_id = ?";

            update($update_query, [$order_id], 's');

            $update_query = "UPDATE booking 
                             SET status = 'failed', payment_id =?
                             WHERE booking_id = ?";

            update($update_query, [$payment_id, $booking_id], 'ii');



            echo <<<data
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-5 mb-3 px-4">
                            <h2 class="fw-bold h-font">Payment Failed</h2>
                        </div>
                        <div class="col-12 px-4">
                            <p class="fw-bold alert alert-danger">
                                <i class="bi bi-x-circle-fill"></i>
                                Oops! Something went wrong. Your payment could not be processed.
                                <br><br>
                                Reason: $reason
                                <br><br>
                                Please try again or contact support if the issue persists.
                                <br><br>
                                <a href="bus.php" class="btn btn-primary">Try Again</a>
                                <br><br>
                                <a href="index.php" class="btn btn-secondary">Go to Home Page</a>
                            </p>
                        </div>
                    </div>
                </div>
            data;
        } else {
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
        echo <<<data
                <div class="container">
                    <div class="row">
                        <div class="col-12 my-5 mb-3 px-4">
                            <h2 class="fw-bold h-font">Payment Failed</h2>
                        </div>
                        <div class="col-12 px-4">
                            <p class="fw-bold alert alert-danger">
                                <i class="bi bi-x-circle-fill"></i>
                                Required parameters are missing! Please try again.
                                <br><br>
                                <a href='bus.php'>Try Again</a>
                            </p>
                        </div>
                    </div>
                </div>
            data;
    }
    ?>

    <?php require('inc/footer.php'); ?>
</body>

</html>