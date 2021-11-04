<?php

include 'db_conn.php';

$required_fields = array('firstname', 'middlename', 'lastname', 'sex', 'bdate', 'telnum', 'email', 'pass', 'conf_pass');

$error = false;

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $error = true;
    }
}

$firstname = $_POST['firstname'];
$middlename = $_POST['middlename'];
$lastname = $_POST['lastname'];
$sex = $_POST['sex'];
$bdate = $_POST['bdate'];
$telnum = $_POST['telnum'];
$email = $_POST['email'];
$password = $_POST['pass'];
$conf_pass = $_POST['conf_pass'];
$position = $_POST['position'];


$hashed_pass = password_hash($password, PASSWORD_DEFAULT);
$formatted_bdate = date('Y-m-d', strtotime($bdate));


//!CHECK IF EMAIL IS ALREADY IN DATABASE
$email_taken = false;
$query = "SELECT * FROM user_table WHERE email = '$email'";
$query2 = "SELECT * FROM employee_table WHERE email = '$email'";

$result = mysqli_query($conn, $query);
$result2 = mysqli_query($conn, $query2);

if (mysqli_num_rows($result) > 0) {
    $email_taken = true;
}
if (mysqli_num_rows($result2) > 0) {
    $email_taken = true;
}

//!CHECK IF EMPLOYEE CODE ISSET
$emp_code_is_valid = false;
if (!empty($_POST['employee_code'])) {
    $code = $_POST['employee_code'];
    $query = "SELECT * FROM employee_code WHERE emp_code = '$code'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        $emp_code_is_valid = true;
    }
}

//! IF ONE OF THE FIELDS IS EMPTY
if ($error) {
    header("Location:../index.php?success=false&fields=empty&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! IF NAME HAS INVALID VALUES
else if (!preg_match('/^[A-Za-z ]+$/', $firstname) || !preg_match('/^[A-Za-z]+$/', $middlename) || !preg_match('/^[A-Za-z]+$/', $lastname)) {
    header("Location:../index.php?success=false&name=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! IF PHONE NUMBER IS CORRECT
else if (!preg_match('/^[0-9]+$/', $telnum)) {
    header("Location:../index.php?success=false&pnumber=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! VALIDATE IF EMAIL
else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location:../index.php?success=false&email=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! IF EMAIL IS TAKEN
else if ($email_taken) {
    header("Location:../index.php?success=false&email=taken&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! IF PASSWORD IS LESS THAN 8 CHARACTERS
else if (strlen($password) < 8) {
    header("Location:../index.php?success=false&pass=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! CHECK IF PASS AND CONF PASS IS THE SAME
else if ($password !== $conf_pass) {
    header("Location:../index.php?success=false&confpass=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! CHECK IF EMPLOYEE CODE IS VALID
else if (!empty($_POST['employee_code']) && !$emp_code_is_valid) {
    header("Location:../index.php?success=false&empcode=invalid&fname=$firstname&mname=$middlename&lname=$lastname&sex=$sex&bdate=$bdate&telnum=$telnum&email=$email");
    exit();
}

//! IF INPUTS HAVE NO INVALIDATIONS
else {
    if (empty($_POST['employee_code'])) {
        $query = "INSERT INTO `user_table`(`first_name`, `middle_name`, `last_name`, `email`, `password`, `contact_num`, `sex`, `birthdate`) 
            VALUES ('$firstname', '$middlename', '$lastname', '$email', '$hashed_pass', '$telnum', '$sex', '$formatted_bdate')";
    } else if (!empty($_POST['employee_code']) && $emp_code_is_valid) {
        $query = "INSERT INTO `employee_table`(`first_name`, `middle_name`, `last_name`, `email`, `password`, `contact_num`, `sex`, `position`)
            VALUES ('$firstname', '$middlename', '$lastname', '$email', '$hashed_pass', '$telnum', '$sex', '$position')";
    }

    $result = mysqli_query($conn, $query);
    mysqli_close($conn);

    header("Location:../index.php?success=true");
}
