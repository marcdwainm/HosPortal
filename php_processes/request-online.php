<?php
session_start();
include 'db_conn.php';

$appnum = $_POST['appnum'];
$selected = $_POST['selected'];

//KNOW FIRST IF THE PATIENT HAS APPOINTMENT
$patientid = substr($appnum, -4);
$query = "SELECT * FROM user_table WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);
$has_appointment = "";
while ($row = mysqli_fetch_array($result)) {
    $has_appointment = $row['has_appointment'];
}

// IF THE PAITENT HAS NO APPOINTMENT (HAS BEEN CANCELLED), DO NOTHING JUST UPDATE TABLE
if ($has_appointment != "1") {
}
//IF PATIENT HAS APPOINTMENT, CONTINUE TO REQUEST ONLINE
else {
    //UPDATE STATUS OF APPOINTMENT
    $query = "UPDATE appointments SET `status` = 'onlinereq' WHERE appointment_num = '$appnum'";
    mysqli_query($conn, $query);

    //SEND NOTIF TO PATIENT
    $query = "SELECT * FROM appointments WHERE appointment_num = '$appnum'";
    $result = mysqli_query($conn, $query);
    $app_date = '';

    while ($row = mysqli_fetch_array($result)) {
        $app_date = $row['date_and_time'];
    }

    $userid = substr($appnum, -4);
    $date = Date("Y-m-d H:i:s", time());
    $emp_id = $_SESSION['emp_id'];
    $query = "INSERT INTO patients_notifications (emp_id, patient_id, notif_type, appointment_num, date_notified, appointment_date) 
    VALUES ('$emp_id', '$userid', 'onlinereq', '$appnum', '$date', '$app_date')";
    mysqli_query($conn, $query);

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$userid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = 'Twin Care Portal | Online Appointment Request';
    $headers = "Good day, our dear patient!";
    $message = "The doctor has requested you for an appointment to be conducted online. Kindly visit www.twincareportal.online to accept or decline the doctor's request.";

    mail($to, $subject, $message, $headers);
}

//UPDATE TABLE
if ($selected == 'today') {
    $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE() ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'upcoming') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() + INTERVAL 1 DAY) AND (CURDATE() + INTERVAL 4 DAY) ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'recent') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 3 DAY) AND CURDATE() ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'lastweek') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 7 DAY) AND CURDATE() ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'pending') {
    $query = "SELECT * FROM appointments WHERE status = 'pending' ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'appointed') {
    $query = "SELECT * FROM appointments WHERE status = 'appointed' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'cancelled') {
    $query = "SELECT * FROM appointments WHERE status = 'cancelled' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'missed') {
    $query = "SELECT * FROM appointments WHERE status = 'missed' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'all') {
    $query = "SELECT * FROM appointments ORDER BY date_and_time DESC LIMIT 0, 5";
}

$result = mysqli_query($conn, $query);

require 'employee-ajax-table.php';

mysqli_close($conn);
