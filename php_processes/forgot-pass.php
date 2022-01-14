<?php

include 'db_conn.php';

$email_input = $_POST['emailInput'];
$randomString = $_POST['randomString'];
$email_found = true;

//FIND EMAIL IN USER TABLE
$query = "SELECT * FROM user_table WHERE email = '$email_input'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    sendEmail($email_input, $randomString);
    // INSERT TO TABLE 
    $query = "INSERT INTO reset_pass_keys (reset_key, user_email) VALUES ('$randomString', '$email_input')";
    mysqli_query($conn, $query);

    exit();
}

//FIND EMAIL IN EMPLOYEE TABLE
$query = "SELECT * FROM employee_table WHERE email = '$email_input'";
$result = mysqli_query($conn, $query);

$query2 = "SELECT * FROM reset_pass_keys WHERE user_email = '$email_input'";
$result2 = mysqli_query($conn, $query2);

if (mysqli_num_rows($result) <= 0) {
    echo "email not found";
    exit();
} else if (mysqli_num_rows($result2) > 0) {
    echo "on reset";
    exit();
} else {
    sendEmail($email_input, $randomString);
    // INSERT TO TABLE 
    $query = "INSERT INTO reset_pass_keys (reset_key, user_email) VALUES ('$randomString', '$email_input')";
    mysqli_query($conn, $query);
    exit();
}


function sendEmail($email, $random)
{
    $to = $email;
    $subject = 'TwinCare Portal Reset Password';
    $headers = "Hello, We are sorry to hear that you have forgotten your password.";
    $message = "Go to the link below to have your password reset. Remember to never give the link to anyone.\n\ntwincareportal.online/index.php?resetPassword=true&resetKey=$random";

    mail($to, $subject, $message, $headers);
}
