<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MYBUS - Confirm Bookings Details</title>
    <?php require('inc/links.php') ?>
    <style>
        .seats-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 5px;
            margin-top: 7px;
        }

        .seat-btn {
            position: relative;
            border: var(--bs-btn-border-width) solid var(--bs-btn-border-color);
            border-radius: var(--bs-btn-border-radius);
            background-color: var(--bs-btn-bg);
            transition: color 0.25s ease-in-out, background-color 0.25s ease-in-out, border-color 0.25s ease-in-out, box-shadow 0.25s ease-in-out;
            display: inline-block;
            justify-content: center;
            align-items: center;
            width: 50px;
            height: 50px;
        }

        .seat-btn:hover {
            transform: scale(1.1);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0);
        }

        .seat-number {
            position: absolute;
            top: 35%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 12px;
            font-weight: bold;
            color: black;
        }

        @media (max-width: 768px) {
            .seats-container {
                grid-template-columns: repeat(3, 1fr);
                /* 3 seats per row on tablets */
            }
        }

        @media (max-width: 480px) {
            .seats-container {
                grid-template-columns: repeat(2, 1fr);
                /* 2 seats per row on small screens */
            }
        }
    </style>
</head>

<?php require('inc/header.php'); ?>

<?php
if (!isset($_GET['id'])) {
    redirect('bus.php');
} else if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect('bus.php');
}

$data = filteration($_GET);

$bus_res = select("SELECT * FROM `buses` WHERE `id`=? ORDER BY `id` DESC", [$data['id']], 'i');

if (mysqli_num_rows($bus_res) == 0) {
    redirect('bus.php');
}

$bus_data = mysqli_fetch_assoc($bus_res);

$_SESSION['bus'] = [
    "id" => $bus_data['id'],
    "name" => $bus_data['bus_name'],
    "price" => $bus_data['price'],
    "payment" => null,
    "available" => false
];

$user_res = select("SELECT * FROM `users` WHERE `id` = ? LIMIT 1", [$_SESSION['id']], 'i');
$user_data = mysqli_fetch_assoc($user_res);
?>

<?php
$seat_res = select("SELECT * FROM `seats` WHERE `bus_id`=? ORDER BY `id` ASC", [$data['id']], 'i');

if (mysqli_num_rows($seat_res) > 0) {
    $seats = [];
    while ($seat_data = mysqli_fetch_assoc($seat_res)) {
        $seats[] = $seat_data;
    }
} else {
    redirect('bus.php');
}

?>


<div class="container">
    <div class="row">
        <div class="col-12 my-5 mb-4 px-4">
            <h2 class="fw-bold h-font">CONFIRM BOOKING</h2>
            <div style="font-size:14px;">
                <a href="index.php" class="text-secondary text-decoration-none">HOME</a>
                <span class="text-secondary"> > </span>
                <a href="bus.php" class="text-secondary text-decoration-none">BUSES</a>
                <span class="text-secondary"> > </span>
                <a href="confirm_booking.php" class="text-secondary text-decoration-none">CONFIRM</a>
            </div>
        </div>

        <div class="col-lg-4 col-md-12 px-4">
            <div class="card p-2 shadow-sm rounded">
                <div class="seats-container rounded mb-3 d-flex flex-wrap align-items-center justify-content-center">
                    <form style="margin-left: 35px;">
                        <?php
                        $seat_count = 0; // Track the number of seats in the current row
                        foreach ($seats as $seat) {
                            // Display the seat button
                            if ($seat['status'] == 'available') {
                                echo '<button type="button" class="seat-btn available overflow-hidden seat" data-seat-id="' . $seat['id'] . '" data-seat-number="' . $seat['seat_number'] . '">
                                        <img height="40px" src="images/seat.png">
                                        <span class="seat-number">' . $seat['seat_number'] . '</span>
                                    </button>';
                            } else {
                                echo '<button class="seat-btn booked seat overflow-hidden" disabled>
                                        <img height="40px" src="images/book-seat.png">
                                        <span class="seat-number">' . $seat['seat_number'] . '</span>
                                    </button>';
                            }
                            $seat_count++;
                            if ($seat_count % 2 == 0) {
                                echo '<span class="mx-3"></span>';
                            }
                            if ($seat_count % 4 == 0) {
                                echo '<div class="w-100"></div>';
                            }
                        }
                        ?>
                    </form>
                </div>

                <h5><?php echo $bus_data['bus_name']; ?></h5>
                <h6>₹<?php echo $bus_data['price']; ?></h6>
                <div class="selected-seats mt-3">
                    <h6 class="mb-2">Selected Seats</h6>
                    <div id="selectedSeats" class="mb-3">
                        <!-- The selected seats will be displayed here -->
                    </div>
                </div>

            </div>
            <?php $_SESSION['ORDER_ID'] = 'ORD_' . $_SESSION['id'] . random_int(11111, 9999999); ?>
        </div>

        <div class="col-lg-6 col-md-12 px-4">
            <div class="card mb-4 border-0 shadow-sm rounded-3">
                <div class="card-body">
                    <form id="booking_form">
                        <h6 class="mb-3">BOOKING DETAILS</h6>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">Name</label>
                                <input type="text" name="name" id="name" value="<?php echo $user_data['name'] ?>"
                                    class="form-control shadow-none" required>
                            </div>
                            <input type="hidden" id="email" name="email" value="<?php echo $user_data['email'] ?>">

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">Phone Number</label>
                                <input type="text" value="<?php echo $user_data['phonenum'] ?>" id="phonenum"
                                    name="phonenum" class="form-control shadow-none" onchange="check_availability()"
                                    required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">Source</label>
                                <input type="text" onchange="check_availability()"
                                    value="<?php echo $bus_data['source']; ?>" name="source" id="source"
                                    class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">Destination</label>
                                <input type="text" onchange="check_availability()"
                                    value="<?php echo $bus_data['destination']; ?>" name="destination" id="destination"
                                    class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">Date</label>
                                <input type="date" onchange="check_availability()"
                                    value="<?php echo $_SESSION['user']['date']; ?>" name="date" id="date"
                                    class="form-control shadow-none" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label mb-1">No. of Passengers</label>
                                <input type="text" onchange="check_availability()"
                                    value="<?php echo $_SESSION['user']['passengers']; ?>" name="passengers"
                                    id="passengers" class="form-control shadow-none" required>
                            </div>

                            <div class="col-12">
                                <div class="spinner-border text-info mb-3 d-none" id="info_loader" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                                <h6 class="mb-3 text-danger" id="pay_info">Please a select seats</h6>
                                <button disabled name="pay_now" id="PayNow"
                                    class="btn w-100 custom-bg shadow-none mb-1">Pay Now
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php require('inc/footer.php'); ?>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="scripts/confirm_booking.js"></script>

</body>

</html>