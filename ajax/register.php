<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_POST['name'], $_POST['email'], $_POST['phonenum'], $_POST['pass'], $_POST['cpass'])) {

    $data = filteration($_POST);

    if ($data['pass'] !== $data['cpass']) {
        exit('Passwords do not match.');
    }

    $u_exist = select("SELECT * FROM `users` WHERE `email` = ? OR `phonenum` = ? LIMIT 1", [$data['email'], $_POST['phonenum']], 'ss');

    if (mysqli_num_rows($u_exist) > 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);
        if ($u_exist_fetch['email'] === $data['email']) {
            exit('Email already registered.');
        } else {
            exit('Phone number already registered.');
        }
    }

    $hashedPassword = password_hash($data['pass'], PASSWORD_BCRYPT);

    $query = "INSERT INTO `users` (`name`, `email`, `password`, `phonenum`) VALUES (?, ?, ?, ?)";
    $values = [$data['name'], $data['email'], $hashedPassword, $_POST['phonenum']];

    if (insert($query, $values, 'sssi')) {

        $u_exist = select("SELECT * FROM `users` WHERE `email` = ?", [$data['email']], 's');
        if (mysqli_num_rows($u_exist) > 0) {
            $u_exist_fetch = mysqli_fetch_assoc($u_exist);

            session_start();
            $_SESSION['login'] = true;
            $_SESSION['id'] = $u_exist_fetch['id'];
            $_SESSION['email'] = $u_exist_fetch['email'];
            $_SESSION['name'] = $u_exist_fetch['name'];
            $_SESSION['phonenum'] = $u_exist_fetch['phonenum'];

            exit('success'); 
        }
    } else {
        exit('Failed to register. Try again later.');
    }
} else {
    exit('Invalid input.');
}

?>
