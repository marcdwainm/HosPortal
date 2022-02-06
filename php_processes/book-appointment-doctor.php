<?php
include 'db_conn.php';

session_start();
$emp_id = $_SESSION['emp_id'];

//Set timezone then get current date and time
date_default_timezone_set('Asia/Manila');
$date = date('Ymdhis', time());
$date_booked = date('Y-m-d H:i:s', time());

// Triage Details
$chief_complaint = ucfirst($_POST['chief-complaint']);
$height = $_POST['height'];
$weight = $_POST['weight'];
$blood_pressure = $_POST['blood-pressure'];
$temperature = $_POST['temperature'];
$past_surgery = ucfirst($_POST['past-surgery']);
$family_history = $_POST['family-history'];
$allergies = $_POST['allergies'];
$social_history = $_POST['social-history'];
$current_medications = ucfirst($_POST['current-medications']);
$travel_history = ucfirst($_POST['travel-history']);

//Check first if patient is in database or not
$appointment_date_time = $_POST['appointment-date-time'];
$datetime_finish = strtotime($appointment_date_time . '+ 30 minutes');
$datetime_finish = date('Y-m-d H:i:s', $datetime_finish);
$patient_name = ucwords($_POST['patient-name']);
$patient_contact = $_POST['patient-contact'];
$app_type = $_POST['app-type'];
$selected = $_POST['selected'];
$pid = $_POST['pid'];

$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    //IF USER IS IN DATABASE
    while ($row = mysqli_fetch_array($result)) {
        //IF TRUE YUNG HAS APPOINTMENT, SWEET ALERT NA MAY APPOINTMENT CURRENTLY YUNG PATIENT
        if ($row['has_appointment'] == '1') {
            echo 'has appointment';
            exit();
        }
        //ELSE BOOK THE PATIENT
        else {
            $patientid = $row['patient_id'];
            $appointmentnum = $date . $patientid;

            //INSERT APPOINTMENT TO DATABSE
            $query = "INSERT INTO `appointments` (`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `date_and_time_finish`, `contact`, `app_type`, `appointed_by`, `status`) 
            VALUES ('$appointmentnum', '$patientid', '$patient_name', '$appointment_date_time', '$datetime_finish', '$patient_contact', '$app_type', '$emp_id', 'pending')";
            mysqli_query($conn, $query);

            //INSERT DETAILS TO TRIAGE
            $query = "INSERT INTO `triage`(`appointment_num`, `chief_complaint`, `height`, `weight`, `blood_pressure`, `temperature`, `past_surgery`, `family_history`, `allergies`, `social_history`, `current_medications`, `travel_history`) 
            VALUES('$appointmentnum', '$chief_complaint', \"$height\", '$weight', '$blood_pressure', '$temperature', '$past_surgery', '$family_history', '$allergies', '$social_history', '$current_medications', '$travel_history')";
            mysqli_query($conn, $query);

            //NOTIFY THE PATIENT
            $query = "INSERT INTO patients_notifications(patient_id, notif_type, appointment_num, date_notified, appointment_date)
            VALUES('$pid', 'appointment', '$appointmentnum', '$date_booked', '$appointment_date_time')";
            mysqli_query($conn, $query);

            $to = $email;
            $subject = 'Twin Care Portal | New Appointment';
            $headers = "Good day, our dear patient!";
            $message = "The doctor has booked an appointment for you. Kindly visit www.twincareportal.online for more details.";
        
            mail($to, $subject, $message, $headers);

            //UPDATE HAS_APPOINTMENT TO TRUE
            $query = "UPDATE user_table SET has_appointment = 1 WHERE CONCAT(first_name, ' ', LEFT(middle_name, 1), '. ', last_name) = '$patient_name' AND contact_num = '$patient_contact'";
            mysqli_query($conn, $query);
        }
    }
} else {
    //IF USER IS NOT IN DATABASE
    $appointmentnum = $date . '0000';

    //INSERT TO APPOINTMENTS TABLE
    $query = "INSERT INTO `appointments` (`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `date_and_time_finish`, `contact`, `app_type`, `appointed_by`, `status`) 
    VALUES ('$appointmentnum', '0000', '$patient_name', '$appointment_date_time', '$datetime_finish', '$patient_contact', 'f2f', 'doctor', 'pending')";
    $result2 = mysqli_query($conn, $query);

    //INSERT DETAILS TO TRIAGE
    $query = "INSERT INTO `triage`(`appointment_num`, `chief_complaint`, `height`, `weight`, `blood_pressure`, `temperature`, `past_surgery`, `family_history`, `allergies`, `social_history`, `current_medications`, `travel_history`) 
    VALUES('$appointmentnum', '$chief_complaint', \"$height\", '$weight', '$blood_pressure', '$temperature', '$past_surgery', '$family_history', '$allergies', '$social_history', '$current_medications', '$travel_history')";
    $result = mysqli_query($conn, $query);
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
