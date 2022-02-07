<?php
include 'db_conn.php';

$app_num = $_GET['app_num'];
$selected = $_GET['selected'];
$cancel_type = $_GET['cancel_type'];

//NOTIIFY USER ABOUT CANCELLATION
$date_notified = date('Y-m-d H:i:s', time());
$userid = substr($app_num, -4);

if ($cancel_type == 'cancel') {
    $query = "INSERT INTO patients_notifications (`notif_type`, `patient_id`, `appointment_num`, `date_notified`) VALUES('cancellation', '$userid','$app_num', '$date_notified')";
    $result = mysqli_query($conn, $query);

    $query = "DELETE FROM bills WHERE bill_num = '$app_num'";
    mysqli_query($conn, $query);

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$userid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = 'Twin Care Portal | Appointment Cancellation';
    $headers = "Good day, our dear patient!";
    $message = "We are sorry to have your current appointment cancelled. Contact 0925-734-7552 for more information.";

    mail($to, $subject, $message, $headers);

    $query = "UPDATE appointments SET `status` = 'cancelled' WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);

    
} else if ($cancel_type == 'miss') {
    $query = "INSERT INTO patients_notifications (`notif_type`, `patient_id`, `appointment_num`, `date_notified`) VALUES('miss', '$userid','$app_num', '$date_notified')";
    $result = mysqli_query($conn, $query);

    $query = "DELETE FROM bills WHERE bill_num = '$app_num'";
    mysqli_query($conn, $query);

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$userid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = 'Twin Care Portal | Appointment Missed';
    $headers = "Good day, our dear patient!";
    $message = "You have received this e-mail due to your missed appointment. We are here to remind you that you may book again and pursue the missed appointment with your doctor. Regards.";

    mail($to, $subject, $message, $headers);

    $query = "UPDATE appointments SET `status` = 'missed' WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);
}

$query = "UPDATE user_table SET has_appointment = 0 WHERE patient_id = '$userid'";
$result = mysqli_query($conn, $query);



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
