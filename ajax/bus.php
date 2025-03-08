<?php
session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Calcutta");

if (isset($_GET['fetch_bus'])) {
    $chk_avail = json_decode($_GET['chk_avail'], true);

    $source = $chk_avail['source'];
    $destination = $chk_avail['destination'];
    $date = $chk_avail['date'];
    $passengers = $chk_avail['passengers'];

    if ($source === '' || $destination === '' || $date === '' || $passengers === '') {
        echo "<h3 class='text-center text-danger'>Please Fill the information!</h3>";
        exit;
    }

    $_SESSION['user'] = [
        "passengers" => $passengers,
        "date" => $date
    ];

    $count_bus = 0;
    $output = "";

    $bus_res = select("SELECT * FROM `buses` WHERE `source`=? AND `destination`=?", [$source, $destination], 'ss');

    while ($bus_data = mysqli_fetch_assoc($bus_res)) {
        $login = 0;
        if (isset($_SESSION['login']) && $_SESSION['login'] == true) {
            $login = 1;
        }
        $book_btn = "<button class='btn btn-sm w-100 text-white custom-bg shadow-none mb-2' onclick='checkLoginToBook(" . $login . ", " . $bus_data['id'] . ")'>Book Now</button>";

        $departure_time = date("h:i A", strtotime($bus_data['departuretime']));
        $arrival_time = date("h:i A", strtotime($bus_data['arrivaltime']));

        $duration = calculateDuration($bus_data['departuretime'], $bus_data['arrivaltime']);

        $output .= "
            <div class='card mb-4 border-0 shadow'>
                <div class='row g-0 p-3 align-item-center'>

                    <div class='col-md-10 px-lg-1 px-md-1 px-0'>
                        <h5 class='mb-3 h-font'>$bus_data[bus_name]</h5>

                        <div class='row align-item-center'>
                            <div class='col-md-3 route mb-3'>
                                <h6 class='mb-1'>Routes</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $bus_data[source]
                                </span>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $bus_data[destination]
                                </span>
                            </div>

                            <div class='col-md-3 time mb-3'>
                                <h6 class='mb-1'>Bus Time</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $arrival_time
                                </span>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $departure_time
                                </span>
                            </div>

                            <div class='col-md-3 time mb-3'>
                                <h6 class='mb-1'>Duration</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $duration
                                </span>
                            </div>

                            <div class='col-md-3 capacity'>
                                <h6 class='mb-1'>Capacity</h6>
                                <span class='badge rounded-pill bg-light text-dark text-wrap'>
                                    $bus_data[capacity] Passengers
                                </span>
                            </div>
                        </div>

                    </div>

                    <div class='col-md-2 mt-4 text-center'>
                        <h6 class='mb-2'>Fare: ₹$bus_data[price]</h6>
                        $book_btn
                    </div>
                </div>
            </div>";
        $count_bus++;
    }

    if ($count_bus > 0) {
        echo $output;
    } else {
        echo "<h3 class='text-center text-danger'>No Buses to show!</h3>";
    }

    
}
?>
