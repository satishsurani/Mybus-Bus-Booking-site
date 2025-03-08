<?php
require('inc/essentials.php');
adminLogin();
require('inc/db_config.php');

// Fetch the booking data
$bookings = mysqli_fetch_assoc(mysqli_query($con, "SELECT
    COUNT(CASE WHEN status != 'pending' THEN 1 END) AS `all_bookings`,
    COUNT(CASE WHEN status = 'confirmed' THEN 1 END) AS `confirm_bookings`,
    COUNT(CASE WHEN status = 'cancelled' THEN 1 END) AS `cancelled_bookings`,
    COUNT(CASE WHEN status != 'failed' THEN 1 END) AS `failed_bookings`
    FROM `booking`"));

$buses = mysqli_fetch_assoc(mysqli_query($con, "SELECT COUNT(id) AS `all_bus` FROM `buses`"));
    
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - Dashboard</title>
    <?php require('inc/links.php'); ?>
</head>

<body class="bg-white">

    <?php require('inc/header.php'); ?>

    <div class="container-fluid" id="main-contain">
        <div class="row">
            <div class="col-lg-10 ms-auto p-4 overflow-hidden">
                <div class="d-flex align-items-center mb-4">
                    <h3 class="h-font">Dashboard</h3>
                </div>

                <div class="row mb-4">
                    <!-- All Bookings Card -->
                    <div class="col-md-3 mb-4">
                        <a href="bookings.php" class="text-decoration-none">
                            <div class="card text-center text-secondary p-3">
                                <h6>All Bookings</h6>
                                <h1 class="mt-2 mb-0"><?php echo $bookings['all_bookings']; ?></h1>
                            </div>
                        </a>
                    </div>

                    <!-- Confirmed Bookings Card -->
                    <div class="col-md-3 mb-4">
                        <a href="bookings.php" class="text-decoration-none">
                            <div class="card text-center text-success p-3">
                                <h6>Confirmed Bookings</h6>
                                <h1 class="mt-2 mb-0"><?php echo $bookings['confirm_bookings']; ?></h1>
                            </div>
                        </a>
                    </div>

                    <!-- Cancelled Bookings Card -->
                    <div class="col-md-3 mb-4">
                        <a href="bookings.php" class="text-decoration-none">
                            <div class="card text-center text-danger p-3">
                                <h6>Cancelled Bookings</h6>
                                <h1 class="mt-2 mb-0"><?php echo $bookings['cancelled_bookings']; ?></h1>
                            </div>
                        </a>
                    </div>

                    <div class="col-md-3 mb-4">
                        <a href="bus.php" class="text-decoration-none">
                            <div class="card text-center text-dark p-3">
                                <h6>Total Buses</h6>
                                <h1 class="mt-2 mb-0"><?php echo $buses['all_bus']; ?></h1>
                            </div>
                        </a>
                    </div>
                </div>

                <!-- Analytics Section -->
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <h3 class="h-font">Booking Analytics</h3>
                    <select class="form-select shadow-none bg-light w-auto" onchange="booking_analytics(this.value)">
                        <option value="1">Past 30 Days</option>
                        <option value="2">Past 90 Days</option>
                        <option value="3">Past 1 Year</option>
                        <option value="4">All time</option>
                    </select>
                </div>

                <div class="row mb-4">
                    <!-- Total Bookings Card -->
                    <div class="col-md-3 mb-3">
                        <div class="card text-center text-secondary p-3">
                            <h6>Total Bookings</h6>
                            <h1 class="mt-2 mb-0" id="total_bookings"></h1>
                            <h4 class="mt-2 mb-0" id="total_amt">₹</h4>
                        </div>
                    </div>

                    <!-- Active Bookings Card -->
                    <div class="col-md-3 mb-3">
                        <div class="card text-center text-success p-3">
                            <h6>Confirm Bookings</h6>
                            <h1 class="mt-2 mb-0" id="confirmed_bookings"></h1>
                            <h4 class="mt-2 mb-0" id="confirmed_amt">₹</h4>
                        </div>
                    </div>

                    <!-- Cancelled Bookings Card -->
                    <div class="col-md-3 mb-3">
                        <div class="card text-center text-danger p-3">
                            <h6>Cancelled Bookings</h6>
                            <h1 class="mt-2 mb-0" id="cancelled_bookings"></h1>
                            <h4 class="mt-2 mb-0" id="cancelled_amt">₹</h4>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <?php require('inc/scripts.php'); ?>

    <script>
        function booking_analytics(period = 1) {
            let xhr = new XMLHttpRequest();
            xhr.open("POST", "ajax/dashboard.php", true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function () {
                try {
                    let data = JSON.parse(this.responseText);

                    document.getElementById('total_bookings').textContent = data.total_bookings;
                    document.getElementById('total_amt').textContent = "₹" + data.total_amt;

                    document.getElementById('confirmed_bookings').textContent = data.confirmed_bookings;
                    document.getElementById('confirmed_amt').textContent = "₹" + data.confirmed_amt;

                    document.getElementById('cancelled_bookings').textContent = data.cancelled_bookings;
                    document.getElementById('cancelled_amt').textContent = "₹" + data.cancelled_amt;

                    document.getElementById('failed_bookings').textContent = data.failed_bookings;
                    document.getElementById('failed_amt').textContent = "₹" + data.failed_amt;

                } catch (e) {
                    console.error("Error parsing JSON:", e);
                    console.log("Response:", this.responseText);
                }
            };
            xhr.send('booking_analytics=true&period=' + period);

        }

        window.onload =function(){
            booking_analytics();
        }
    </script>


</body>

</html>