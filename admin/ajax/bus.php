<?php
require('../inc/db_config.php');
require('../inc/essentials.php');
adminLogin();

// Add Bus
if (isset($_POST['add_bus'])) {
    $frm_data = filteration($_POST);

    $q1 = "INSERT INTO `buses`(`bus_name`, `source`, `destination`, `price`, `arrivaltime`, `departuretime`, `capacity`) 
            VALUES (?,?,?,?,?,?,?)";

    $values = [
        $frm_data['name'],
        $frm_data['source'],
        $frm_data['destination'],
        $frm_data['price'],
        $frm_data['arrivaltime'],
        $frm_data['departuretime'],
        $frm_data['capacity']
    ];

    $res = insert($q1, $values, 'sssdsss');

    if ($res) {
        $bus_id = mysqli_insert_id($con); 

        $capacity = $frm_data['capacity'];
        $seat_inserts = [];

        for ($i = 1; $i <= $capacity; $i++) {
            $seat_inserts[] = "($bus_id, $i)"; 
        }

        $q2 = "INSERT INTO `seats` (`bus_id`, `seat_number`) VALUES " . implode(", ", $seat_inserts);

        $seat_res = mysqli_query($con, $q2); 

        if ($seat_res) {
            echo 1; 
        } else {
            echo 0; 
        }
    } else {
        echo 0; 
    }
}



// Get All Bus
if (isset($_POST['get_all_bus'])) {
    $res = selectAll('buses');
    $i = 1;

    $data = "";

    while ($row = mysqli_fetch_assoc($res)) {

        $data .= "
            <tr>
                <td>$i</td>
                <td>$row[bus_name]</td>
                <td>$row[source]</td>
                <td>$row[destination]</td>
                <td>₹$row[price]</td>
                <td>$row[arrivaltime]</td>
                <td>$row[departuretime]</td>
                <td>$row[capacity]</td>
                <td>
                    <button type='button' onclick='edit_details($row[id])' class='btn btn-primary shadow-none btn-sm' data-bs-toggle='modal' data-bs-target='#edit-bus'>
                        <i class='bi bi-pencil-square'></i>
                    </button>

                     <button type='button' onclick='remove_bus($row[id])' class='btn btn-danger shadow-none btn-sm'>
                        <i class='bi bi-trash'></i>
                     </button>
                </td>
            </tr>
        ";

        $i++;
    }
    echo $data;
}

// Get Bus by ID for Editing
if (isset($_POST['get_bus'])) {
    $frm_data = filteration($_POST);

    $res = select("SELECT * FROM `buses` WHERE `id`=?", [$frm_data['get_bus']], 'i');

    $data = mysqli_fetch_assoc($res);

    $data = json_encode($data);

    echo $data;
}

// Edit Bus
if (isset($_POST['edit_bus'])) {
    $frm_data = filteration($_POST);

    $q = "UPDATE `buses` SET `bus_name`=?, `source`=?, `destination`=?, `price`=?, `arrivaltime`=?, `departuretime`=?, `capacity`=? WHERE `id`=?";
    $values = [
        $frm_data['name'],
        $frm_data['source'],
        $frm_data['destination'],
        $frm_data['price'],
        $frm_data['arrivaltime'],
        $frm_data['departuretime'],
        $frm_data['capacity'],
        $frm_data['bus_id']
    ];

    if (update($q, $values, 'sssdsssi')) {
        echo 1;
    } else {
        echo 0;
    }
}

// Remove Bus
if (isset($_POST['remove_bus'])) {
    $frm_data = filteration($_POST);
    $bus_id = $frm_data['bus_id'];

    $q1 = "DELETE FROM `seats` WHERE `bus_id`=?";
    $seat_res = deletesql($q1, [$bus_id], 'i'); 

    if ($seat_res) {
        $q2 = "DELETE FROM `buses` WHERE `id`=?";
        $bus_res = deletesql($q2, [$bus_id], 'i');  

        if ($bus_res) {
            echo 1;  
        } else {
            echo 0; 
        }
    } else {
        echo 0; 
    }
}




?>