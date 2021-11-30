<?php
include 'db_conn.php';

//Set timezone then get current date and time
date_default_timezone_set('Asia/Manila');
$date = date('Ymdhis', time());
$date_booked = date('Y-m-d H:i:s', time());

//Check first if patient is in database or not

$appointment_date_time = $_POST['appointment-date-time'];
$patient_name = ucwords($_POST['patient-name']);
$patient_contact = $_POST['patient-contact'];
if ($patient_contact === '') {
    $patient_contact = 'Not Specified';
}
$app_type = $_POST['app-type'];
$desc = $_POST['desc'];
if ($desc == '') {
    $desc = 'N/A';
}
$selected = $_POST['selected'];
$pid = $_POST['pid'];

$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    //IF USER IS IN DATABASE
    while ($row = mysqli_fetch_array($result)) {
        $patientid = $row['patient_id'];
        $appointmentnum = $date . $patientid;

        $query = "INSERT INTO `appointments` (`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `description`, `contact`, `app_type`, `appointed_by`, `status`) 
        VALUES ('$appointmentnum', '$patientid', '$patient_name', '$appointment_date_time', '$desc', '$patient_contact', '$app_type', 'doctor', 'pending')";
        mysqli_query($conn, $query);

        $query = "INSERT INTO patients_notifications(patient_id, notif_type, appointment_num, date_notified)
        VALUES('$pid', 'appointment', '$appointmentnum', '$date_booked')";
        mysqli_query($conn, $query);

        $query = "UPDATE user_table SET num_of_appt = num_of_appt + 1 WHERE CONCAT(first_name, ' ', LEFT(middle_name, 1), '. ', last_name) = '$patient_name' AND contact_num = '$patient_contact'";
        mysqli_query($conn, $query);
    }
} else {
    //IF USER IS NOT IN DATABASE
    $appointmentnum = $date . '0000';

    $query = "INSERT INTO `appointments` (`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `description`, `contact`, `app_type`, `appointed_by`, `status`) 
    VALUES ('$appointmentnum', '0000', '$patient_name', '$appointment_date_time', '$desc', '$patient_contact', '$app_type', 'doctor', 'pending')";
    $result2 = mysqli_query($conn, $query);
}

$selected = $_POST['selected'];

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
} else if ($selected == 'all') {
    $query = "SELECT * FROM appointments ORDER BY date_and_time DESC LIMIT 0, 5";
}

$result = mysqli_query($conn, $query);

require 'employee-ajax-table.php';

mysqli_close($conn);
