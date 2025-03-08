<?php
session_start();
require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

date_default_timezone_set("Asia/Calcutta");
header('Content-Type: application/json');

if (isset($_POST['check_availability'])) {
    
    $frm_data = filteration($_POST);

    // Validate input
    if (empty($frm_data['source']) || empty($frm_data['destination']) || empty($frm_data['passengers']) || empty($frm_data['date'])) {
        throw new Exception("Invalid input data.");
    }

    // Extract values
    $source = $frm_data['source'];
    $destination = $frm_data['destination'];
    $passengers = $frm_data['passengers'];
    $date = $frm_data['date'];

    // Calculate payment
    $_SESSION['bus']['available'] = true;
    $_SESSION['bus']['source'] = $source;
    $_SESSION['bus']['destination'] = $destination;
    $_SESSION['bus']['passengers'] = $passengers;
    $_SESSION['bus']['date'] = $date;
    $_SESSION['user']['name'] = $frm_data['name'];
    $_SESSION['user']['email'] = $frm_data['email'];
    $_SESSION['user']['number'] = $frm_data['number'];

    // Convert the string back into an array
    $selectedSeatsString = $_POST['selectedSeats'];

    // Initialize separate arrays for seat IDs and seat numbers
    $seatIds = [];
    $seatNumbers = [];

    $seatDetails = []; // You can keep this for debugging purposes if you still want to store the details.

    // Split the selected seats string into individual seats
    $selectedSeatsArray = explode(',', $selectedSeatsString);

    // Ensure the array is correctly formatted
    foreach ($selectedSeatsArray as $seat) {
        // Ensure the seat has both seatId and seatNumber separated by a '-'
        $seatParts = explode('-', $seat);

        if (count($seatParts) === 2) {
            $seatId = $seatParts[0];
            $seatNumber = $seatParts[1];

            // Store seat IDs and seat numbers separately
            $seatIds[] = $seatId;
            $seatNumbers[] = $seatNumber;

            // You can still keep the seatDetails if needed for debugging purposes or future use
            $seatDetails[] = ['seatId' => $seatId, 'seatNumber' => $seatNumber];
        } else {
            // Handle the error where the seat format is not correct
            echo json_encode(["error" => "Invalid seat format for: $seat"]);
            exit;
        }
    }

    // Store the arrays in the session without converting to strings
    $_SESSION['user']['selectedSeatIds'] = $seatIds;
    $_SESSION['user']['selectedSeatNumbers'] = $seatNumbers;

    // Optionally, if you still want to store the full details (with both seatId and seatNumber) in the session
    $_SESSION['user']['selectedSeats'] = $seatDetails;

    // Calculate payment based on the number of seats selected
    $payment = $_SESSION['bus']['price'] * count($seatNumbers);  // Use the array directly

    $_SESSION['bus']['payment'] = $payment;

    // Make sure this is the only response being sent
    $result = json_encode([
        "payment" => $payment,
        "name" => $frm_data['name'],
        "email" => $frm_data['email'],
        "number" => $frm_data['number'],
        "selectedSeatIds" => implode(',', $seatIds),  // This will convert the seat IDs array to a string for the response
        "selectedSeats" => implode(',', $seatNumbers)  // This will convert the seat numbers array to a string for the response
    ]);

    // Don't use var_dump() or print_r() here
    echo $result;
}
?>
