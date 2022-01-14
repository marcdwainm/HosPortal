<?php

include 'db_conn.php';

$new_pass = $_POST['newPass'];
$reset_key = $_POST['resetKey'];
$hashed_pass = password_hash($new_pass, PASSWORD_DEFAULT);

// GET EMAIL FROM RESET KEYS TABLE
$query = "SELECT * FROM reset_pass_keys WHERE reset_key = '$reset_key'";
$result = mysqli_query($conn, $query);
$user_email = "";

while ($row = mysqli_fetch_array($result)) {
    $user_email = $row['user_email'];
}

// KNOW FIRST IF USER IS PATIENT OR EMPLOYEE, THEN UPDATE THE PASSWORD HIS/HER PASSWORD
$query = "SELECT * FROM user_table WHERE email = '$user_email'";
$result = mysqli_query($conn, $query);

// UPDATE IN USER TABLE
if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE user_table SET password = '$hashed_pass' WHERE email = '$user_email'";
    mysqli_query($conn, $query);
    echo $query;
}
//UPDATE PASS IN EMPLOYEE TABLE
else {
    $query = "UPDATE employee_table SET password = '$hashed_pass' WHERE email = '$user_email'";
    mysqli_query($conn, $query);
    echo $query;
}

//DELETE RESET KEY
$query = "DELETE FROM reset_pass_keys WHERE reset_key = '$reset_key'";
mysqli_query($conn, $query);
