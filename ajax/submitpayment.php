<?php
session_start();
ob_clean();
require_once('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

use Razorpay\Api\Api;
require('../vendor/autoload.php');

header('Content-Type: application/json');

if (isset($_POST['action']) && $_POST['action'] == 'payOrder') {

    $user_id = $_SESSION['id'];
    $bus_id = $_SESSION['bus']['id'];
    $source = $_SESSION['bus']['source'];
    $destination = $_SESSION['bus']['destination'];
    $date = $_SESSION['bus']['date'];
    $payAmount = $_POST['payAmount'];

    $seatIds = $_SESSION['user']['selectedSeatIds'];
    $seatNumbers = $_SESSION['user']['selectedSeatNumbers'];

    // Convert seatNumbers array to a comma-separated string
    $seatNumbersString = implode(',', $seatNumbers); // This converts the array to a string

    $order_id = 'ORD_' . uniqid();
    $_SESSION['ORDER_ID'] = $order_id;

    $query2 = "INSERT INTO booking (user_id, bus_id, user_name, phonenum, email, travel_date, seat_number, source, destination) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

    insert($query2, [
        $user_id,
        $bus_id,
        $_SESSION['user']['name'],
        $_SESSION['user']['number'],
        $_SESSION['user']['email'],
        $date,
        $seatNumbersString,  // Use the comma-separated string here
        $source,
        $destination
    ], 'iisssssss');

    $booking_id = mysqli_insert_id($con);

    $query1 = "INSERT INTO `payment` (`booking_id`, `trans_amt`, `order_id`) VALUES (?, ?, ?)";
    insert($query1, [$booking_id, $payAmount, $order_id], 'iis');

    $payment_id = mysqli_insert_id($con);

    // Razorpay API credentials
    $razorpay_key = 'rzp_test_dt8ARo16LbgcBt'; // Your Razorpay Key ID
$razorpay_secret = 'uEkLzjMFQIgSmMcwsFjg2TKy'; // Your Razorpay Secret Key


    // Initialize Razorpay API client
    $api = new Api($razorpay_key, $razorpay_secret);

    // Payment order details
    $orderData = [
        'amount' => $payAmount * 100, // Amount in paise (Razorpay expects amount in paise)
        'currency' => 'INR',
        'receipt' => $order_id,
        'notes' => [
            'note_key_1' => 'Payment for booking',
        ]
    ];

    try {
        // Create the order using Razorpay API
        $order = $api->order->create($orderData);
        
        // If order creation is successful, send response back to the client
        echo json_encode([
            'booking' => [
                'res' => 'success',
                'razorpay_key' => $razorpay_key, // Razorpay Key
                'message' => 'Booking details inserted successfully'
            ],
            'payment' => [
                'res' => 'success',
                'amount' => $payAmount,
                'description' => 'Payment for booking'
            ]
        ]);
        
    } catch (Exception $e) {
        // In case of failure, return the error message
        echo json_encode([
            'res' => 'failure',
            'info' => 'Payment Request Failed: ' . $e->getMessage()
        ]);
    }
}
?>
