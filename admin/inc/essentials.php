<?php

if (!defined('SITE_URL')) {
    define('SITE_URL', 'http://127.0.0.1/mybus/');
}

if (!function_exists('adminLogin')) {
    function adminLogin()
    {
        session_start();
        if (!(isset($_SESSION['adminLogin']) && $_SESSION['adminLogin'] == true)) {
            echo "<script>
                    window.location.href = 'index.php';
                </script>";
            exit;
        }
    }
}

if (!function_exists('redirect')) {
    function redirect($url)
    {
        echo "<script>
                    window.location.href = '$url';
                </script>";
        exit;
    }
}

if (!function_exists('alert')) {
    function alert($type, $msg)
    {
        $bs_class = ($type == 'success') ? 'alert-success' : 'alert-danger';
        echo <<<alert
                        <div class="alert $bs_class alert-dismissible custom-alert fade show" role="alert" >
                            <strong class='me-3'>$msg</strong>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>       
                        </div> 
            alert;
    }
}

if (!function_exists('calculateDuration')) {
function calculateDuration($departure_time, $arrival_time) {
    $departure = new DateTime($departure_time);
    $arrival = new DateTime($arrival_time);

    $interval = $departure->diff($arrival);

    $hours = $interval->h;
    $minutes = $interval->i;

    return "{$hours} hours {$minutes} minutes";
}
}

?>
