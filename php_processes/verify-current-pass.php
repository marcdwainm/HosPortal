<?php

include 'db_conn.php';

session_start();

$curr_pass = $_POST['currPass'];
$logged_in = $_SESSION['email'];

//IF POSITION IS NOT PATIENT, CHECK THE EMPLOYEE TABLE
if ($_SESSION['position'] != 'patient') {
    $result = mysqli_query($conn, "SELECT password FROM employee_table WHERE email = '$logged_in'");
} else {
    $result = mysqli_query($conn, "SELECT password FROM user_table WHERE email = '$logged_in'");
}

$row = mysqli_fetch_array($result);
echo (password_verify($curr_pass, $row['password'])) ? "password correct" : "password incorrect";
