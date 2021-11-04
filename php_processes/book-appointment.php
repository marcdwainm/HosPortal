<?php
session_start();
include 'db_conn.php';

$required_fields = array('appointment-date-time', 'description');
$error = false;


foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $error = true;
    }
}

//Set timezone then get current date and time
date_default_timezone_set('Asia/Manila');
$date = date('Ymdhis', time());

//Logged in Patient ID
$patientid = $_SESSION['patientid'];
$fullname = $_SESSION['fullname'];

// Appointment num = datetime user registered appointment + patientid
$appointmentnum = $date . $patientid;

// Formatted as 2021-11-17 09:00:00
$datetime = $_POST['appointment-date-time'];

$desc = $_POST['description'];
$email = $_SESSION['email'];
$position = $_SESSION['position'];

$query = "INSERT INTO `appointments`(`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `description`, `status`) 
            VALUES ('$appointmentnum', '$patientid', '$fullname', '$datetime', '$desc', 'pending')";

$result = mysqli_query($conn, $query);
mysqli_close($conn);
