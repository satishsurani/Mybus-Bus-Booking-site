<?php

require('../admin/inc/db_config.php');
require('../admin/inc/essentials.php');

if (isset($_POST['email'], $_POST['pass'])) {

    $data = filteration($_POST);

    $u_exist = select("SELECT * FROM `users` WHERE `email` = ?", [$data['email']], 's');
    if (mysqli_num_rows($u_exist) > 0) {
        $u_exist_fetch = mysqli_fetch_assoc($u_exist);

        if (password_verify($data['pass'], $u_exist_fetch['password'])) {
            session_start();
            $_SESSION['login'] = true;
            $_SESSION['id'] = $u_exist_fetch['id'];
            $_SESSION['email'] = $u_exist_fetch['email'];
            $_SESSION['name'] = $u_exist_fetch['name'];
            $_SESSION['phonenum'] = $u_exist_fetch['phonenum'];
            exit('success');
        } else {
            exit('Invalid password.');
        }

    } else {
        exit('Email not registered.');
    }
}  else {
    exit('Invalid input.');
}

?>