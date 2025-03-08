<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if (isset($_POST['booking_analytics'])) {
    $frm_data = filteration($_POST);

    $conditon = "";

    if($frm_data['period'] == 1)
    {
        $conditon = "WHERE datentime BETWEEN NOW() - INTERVAL 30 DAY AND NOW()";
    }
    else if($frm_data['period'] == 2)
    {
        $conditon = "WHERE datentime BETWEEN NOW() - INTERVAL 90 DAY AND NOW()";
    }
    else if($frm_data['period'] == 3)
    {
        $conditon = "WHERE datentime BETWEEN NOW() - INTERVAL 1 YEAR AND NOW()";
    }

    $query = "SELECT
        COUNT(CASE WHEN trans_status != 'pending' THEN 1 END) AS `total_bookings`,
        COALESCE(SUM(CASE WHEN trans_status != 'pending' THEN `trans_amt` END), 0) AS `total_amt`,
        
        COUNT(CASE WHEN trans_status = 'success' THEN 1 END) AS `confirmed_bookings`,
        COALESCE(SUM(CASE WHEN trans_status = 'success' THEN `trans_amt` END), 0) AS `confirmed_amt`,
        
        COUNT(CASE WHEN trans_status = 'cancelled' THEN 1 END) AS `cancelled_bookings`,
        COALESCE(SUM(CASE WHEN trans_status = 'cancelled' THEN `trans_amt` END), 0) AS `cancelled_amt`,
        
        COUNT(CASE WHEN trans_status = 'failed' THEN 1 END) AS `failed_bookings`,
        COALESCE(SUM(CASE WHEN trans_status = 'failed' THEN `trans_amt` END), 0) AS `failed_amt`
        FROM `payment` $conditon";

    $result = mysqli_query($con, $query);

    if (!$result) {
        die('Query Failed: ' . mysqli_error($con));
    }

    $data = mysqli_fetch_assoc($result);

    echo json_encode($data);
}
?>