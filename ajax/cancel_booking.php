<?php
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

$response = 0; // Default to failure

if (isset($_POST['payment_id'])) {

    $payment_id = $_POST['payment_id'];

    $query = "UPDATE `booking` 
              SET `status` = 'cancelled' 
              WHERE `payment_id` = ?";
    $query1 = "UPDATE `payment` 
               SET `trans_status` = 'cancelled' 
               WHERE `payment_id` = ?";

    update($query, [$payment_id], "i");
    update($query1, [$payment_id], "i");

    $query2 = "SELECT * FROM booking WHERE payment_id = ?";
    $res = select($query2, [$payment_id], "i");

    if ($res->num_rows > 0) {

        while ($seat = mysqli_fetch_assoc($res)) {
            $seat_number = $seat['seat_number'];
            $seat_number = explode(",", $seat_number);
            $seat_number = array_map('intval', $seat_number);
            $bus_id = $seat['bus_id'];
            foreach ($seat_number as $seatnum) {
                $query4 = "UPDATE seats SET status = 'available', booking_id = NULL WHERE seat_number = ? AND bus_id = ?";
                update($query4, [$seatnum, $bus_id], "ii");
            }
        }

        $response = 1;
    }
}

echo $response;
?>