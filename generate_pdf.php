<?php
require('admin/inc/essentials.php');
require('admin/inc/db_config.php');
require_once 'vendor/fpdf/fpdf.php';

session_start();

if (!(isset($_SESSION['login']) && $_SESSION['login'] == true)) {
    redirect("index.php");
}

if (isset($_GET['gen_pdf']) && isset($_GET['id'])) {
    $frm_data = filteration($_GET);

    // Sanitize and escape the booking ID
    $booking_id = mysqli_real_escape_string($con, $frm_data['id']);

    // Define the query
    $query = "SELECT bo.*, bd.*, b.*, uc.email FROM `payment` bo
    INNER JOIN `booking` bd ON bo.payment_id = bd.payment_id
    INNER JOIN `users` uc ON bd.user_id = uc.id
    INNER JOIN `buses` b ON bd.bus_id = b.id
    WHERE ((bd.status='confirmed')
    OR (bd.status='cancelled')
    OR (bd.status='failed'))
    AND (bd.booking_id='$booking_id')";

    $res = mysqli_query($con, $query);

    if (!$res) {
        die('Query Failed: ' . mysqli_error($con));
    }

    $total_rows = mysqli_num_rows($res);

    if ($total_rows == 0) {
        header('location: index.php');
        exit;
    }

    $data = mysqli_fetch_assoc($res);

    // Formatting Dates
    $date = date("d-m-Y | h:ia", strtotime($data['datentime']));
    $arrivaltime =  date(" h:ia", strtotime($data['arrivaltime']));
    $departuretime =  date("h:ia", strtotime($data['departuretime']));

    // Create PDF instance
    $pdf = new FPDF();
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);
    
    // Title
    $pdf->Cell(0, 10, 'MYBUS', 0, 1, 'C');
    
    // Line Separator
    $pdf->Ln(5);
    $pdf->SetLineWidth(0.5);
    $pdf->Line(10, $pdf->GetY(), 200, $pdf->GetY()); // Horizontal line

    // Change font for content
    $pdf->SetFont('Arial', '', 12);

    // Booking Information
    $pdf->Cell(100, 10, "Order ID: {$data['order_id']}", 0, 1);
    $pdf->Cell(100, 10, "Booking Date: $date", 0, 1);
    $pdf->Cell(100, 10, "Status: {$data['status']}", 0, 1);
    $pdf->Cell(100, 10, "Name: {$data['user_name']}", 0, 1);
    $pdf->Cell(100, 10, "Email: {$data['email']}", 0, 1);
    $pdf->Cell(100, 10, "Phone Number: {$data['phonenum']}", 0, 1);

    // Bus Details
    $pdf->Cell(100, 10, "Bus Name: {$data['bus_name']}", 0, 1);
    $pdf->Cell(100, 10, "Seat Number: {$data['seat_number']}", 0, 1);
    $pdf->Cell(100, 10, "Cost:  {$data['trans_amt']}", 0, 1);
    $pdf->Cell(100, 10, "Source: {$data['source']}", 0, 1);
    $pdf->Cell(100, 10, "Destination: {$data['destination']}", 0, 1);
    $pdf->Cell(100, 10, "Arrival Time: $arrivaltime", 0, 1);
    $pdf->Cell(100, 10, "Departure Time: $departuretime", 0, 1);

    // If failed, add a note for transaction failure
    if ($data['status'] == 'failed') {
        $pdf->Cell(100, 10, "Transaction Amount:  {$data['trans_amt']}", 0, 1);
    } else {
        $pdf->Cell(100, 10, "Bus Number: {$data['bus_id']}", 0, 1);
        $pdf->Cell(100, 10, "Amount Paid:  {$data['trans_amt']}", 0, 1);
    }

    // Footer with company details
    $pdf->Ln(10);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(0, 10, "Thank you for booking with MYBUS! For any inquiries, contact support@mybus.com", 0, 1, 'C');

    // Output the PDF as a downloadable file
    $pdf->Output($data['order_id'] . '.pdf', 'D');
} else {
    header('location: index.php');
}
?>
