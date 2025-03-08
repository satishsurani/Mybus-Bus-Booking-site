<?php

require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

if (isset($_POST['get_bookings'])) {
    $frm_data = filteration($_POST);

    // Preserve date filter from the POST data
    $date_filter = " AND p.datentime >= CURDATE() - INTERVAL 30 DAY";  // Default filter

    if (isset($frm_data['date_filter'])) {
        if ($frm_data['date_filter'] == '30days') {
            $date_filter = " AND p.datentime >= CURDATE() - INTERVAL 30 DAY";
        } elseif ($frm_data['date_filter'] == '90days') {
            $date_filter = " AND p.datentime >= CURDATE() - INTERVAL 90 DAY";
        } elseif ($frm_data['date_filter'] == '1year') {
            $date_filter = " AND p.datentime >= CURDATE() - INTERVAL 1 YEAR";
        } elseif ($frm_data['date_filter'] == 'all') {
            $date_filter = '';  // No filter
        }
    }

    $limit = 10;
    $page = isset($frm_data['page']) ? $frm_data['page'] : 1;
    $start = ($page - 1) * $limit;

    // Initialize values and datatypes for query
    $values = [];
    $datatypes = '';

    // SQL query to fetch necessary booking data, applying filters
    $query = "SELECT p.order_id, b.user_name, b.phonenum, p.trans_amt, b.status, p.datentime, 
                     b.source, b.destination, b.seat_number, b.travel_date, bus.bus_name, bus.arrivaltime, bus.departuretime, b.email
              FROM `payment` p
              INNER JOIN `booking` b ON p.payment_id = b.payment_id
              INNER JOIN `buses` bus ON b.bus_id = bus.id
              WHERE (b.status = 'confirmed'     
                  OR b.status = 'cancelled') " . $date_filter . "
              ORDER BY b.booking_id DESC";

    // Execute query without limit for total rows
    $res = select($query, $values, $datatypes);

    // Apply limit for pagination
    $limit_query = $query . " LIMIT ?, ?";
    array_push($values, $start, $limit);  // Add pagination values
    $datatypes .= 'ii';  // Append data types for LIMIT
    $limit_res = select($limit_query, $values, $datatypes);

    $i = 1;
    $table_data = "";

    // Get the total number of rows for pagination
    $total_rows = mysqli_num_rows($res);
    $total_pages = ceil($total_rows / $limit);

    if ($total_rows == 0) {
        echo json_encode([
            'table_data' => "<b>No Data Found!</b>",
            'pagination' => ''
        ]);
        exit;
    }

    // Loop through the results and prepare the table data
    while ($data = mysqli_fetch_assoc($limit_res)) {
        $date = date("d-m-Y", strtotime($data['datentime']));

        // Check if 'email' is set, otherwise set it to an empty string
        $email = isset($data['email']) ? $data['email'] : '';

        // Status and badge color handling
        $status_bg = '';
        if ($data['status'] == 'confirmed') {
            $status_bg = 'bg-success';
        } else if ($data['status'] == 'cancelled') {
            $status_bg = 'bg-danger';
        } else {
            $status_bg = 'bg-warning text-dark';
        }

        $table_data .= "
            <tr>
                <td>$i</td>
                <td>
                    <span class='badge $status_bg'>
                        Order ID: $data[order_id]
                    </span>
                    <br>
                    <b>Name: </b> $data[user_name] <br>
                    <b>Phone No: </b> $data[phonenum] <br>
                    <b>Email: </b> $email <br>
                </td>
                <td>
                    <b>Bus Name: </b> $data[bus_name] <br>
                    <b>Source: </b> $data[source] <br>
                    <b>Destination: </b> $data[destination] <br>
                    <b>Seat Number: </b> $data[seat_number] <br>
                    <b>Travel Date: </b> $data[travel_date] <br>
                    <b>Arrival Time: </b> $data[arrivaltime] <br>
                    <b>Departure Time: </b> $data[departuretime] <br>
                </td>
                <td>
                    <b>Amount: </b> ₹$data[trans_amt] <br>
                    <b>Date: </b> $date <br>
                </td>
                <td>
                    <span class='badge $status_bg'>$data[status]</span>
                </td>
            </tr>
        ";
        $i++;
    }

    // Pagination logic
    $pagination = '';
    if ($total_pages > 1) {
        $pagination .= '<ul class="pagination justify-content-center">';

        // Previous button
        $pagination .= "<li class='page-item'>
                        <a class='page-link' href='javascript:get_bookings(" . ($page - 1) . ", \"$frm_data[date_filter]\")' aria-label='Previous' " . ($page == 1 ? 'disabled' : '') . ">
                            <span aria-hidden='true'>&laquo;</span>
                        </a>
                    </li>";

        // Page numbers
        for ($i = 1; $i <= $total_pages; $i++) {
            $pagination .= "<li class='page-item " . ($i == $page ? 'active' : '') . "'>
                            <a class='page-link' href='javascript:get_bookings($i, \"$frm_data[date_filter]\")'>$i</a>
                        </li>";
        }

        // Next button
        $pagination .= "<li class='page-item'>
                        <a class='page-link' href='javascript:get_bookings(" . ($page + 1) . ", \"$frm_data[date_filter]\")' aria-label='Next' " . ($page == $total_pages ? 'disabled' : '') . ">
                            <span aria-hidden='true'>&raquo;</span>
                        </a>
                    </li>";

        $pagination .= '</ul>';
    }

    // Return the JSON response
    echo json_encode([
        'table_data' => $table_data,
        'pagination' => $pagination
    ]);
    exit;
}
?>
