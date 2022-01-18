<?php
session_start();
include 'db_conn.php';

$appnum = $_POST['appnum'];
$answer = $_POST['answer'];
$query = "";

if ($answer == 'accept') {
    //UPDATE APPOINTMENT TO TYPE ONLINE
    $query = "UPDATE appointments SET `app_type` = 'online', `status` = 'pending' WHERE appointment_num = '$appnum'";
} else if ($answer == 'decline') {
    $query = "UPDATE appointments SET `app_type` = 'f2f', `status` = 'pending' WHERE appointment_num = '$appnum'";
}

mysqli_query($conn, $query);

//UPDATE BILLS TABLE
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s", time());
$query = "UPDATE bills SET paid = '1', date_of_payment = '$date' WHERE bill_num = '$appnum'";
mysqli_query($conn, $query);

//UPDATE PATIENTS NOTIFICATIONS

$query = "UPDATE patients_notifications SET `notif_type` = 'onlinereqanswered' WHERE `appointment_num` = '$appnum' AND notif_type = 'onlinereq'";
mysqli_query($conn, $query);


$emp_id = "";
$fullname = "";
$query = "SELECT * FROM patients_notifications WHERE appointment_num = '$appnum'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $emp_id = $row['emp_id'];
}

//GETTING PATIENT FULLNAME
$pid = $_SESSION['patientid'];
$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $lastname = $row['last_name'];
    $middlename = substr($row['middle_name'], 0, 1);
    $firstname = $row['first_name'];
    $fullname = "$firstname $middlename. $lastname";
}


//GET APPOINTMENT'S DATE AND TIME

$date_time = '';
$query = "SELECT * FROM appointments WHERE appointment_num = '$appnum'";
$result = mysqli_query($conn, $query);
while ($row = mysqli_fetch_array($result)) {
    $date_time = $row['date_and_time'];
}

$notiftype = "";
if ($answer == 'accept') {
    $notiftype = "onlineaccept";
} else if ($answer == 'decline') {
    $notiftype = "onlinedecline";
}

//NOTIFY THGE DOCTOR
$query = "INSERT INTO notifications (emp_id, notif_type, appointment_num, patient_fullname, date_time, seen) 
VALUES ('$emp_id', '$notiftype', '$appnum', '$fullname', '$date_time', '0')";
$result = mysqli_query($conn, $query);

date_default_timezone_set('Asia/Manila');
$current_date = date('Y-m-d H:i:s', time());
//PUT INTO BILLS TABLE
if ($answer == 'accept') {
    $query = "INSERT INTO bills (bill_num, names, prices, total, issued_to, date_issued, date_of_payment, paid) 
    VALUES ('$appnum', 'E-Consultation', '500', '500', '$pid', '$current_date', '$current_date', '1')";
    mysqli_query($conn, $query);
}

mysqli_close($conn);

include 'table-live-update-patient-first.php';
