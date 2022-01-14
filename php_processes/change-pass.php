<?php

include 'db_conn.php';
session_start();

$position = $_SESSION['position'];
$logged_in = $_SESSION['email'];
$new_pass = $_POST['newPass'];
$new_pass = password_hash($new_pass, PASSWORD_DEFAULT);

if ($position != 'patient') {
    mysqli_query($conn, "UPDATE employee_table SET password = '$new_pass' WHERE email = '$logged_in'");
} else {
    mysqli_query($conn, "UPDATE user_table SET password = '$new_pass' WHERE email = '$logged_in'");
}
