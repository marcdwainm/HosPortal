<?php
include 'db_conn.php';

$app_num = $_GET['app_num'];
$selected = $_GET['selected'];

//NOTIIFY USER ABOUT CANCELLATION
$date_notified = date('Y-m-d H:i:s', time());
$userid = substr($app_num, -4);

$query = "INSERT INTO patients_notifications (`notif_type`, `patient_id`, `appointment_num`, `date_notified`) VALUES('cancellation', '$userid','$app_num', '$date_notified')";
$result = mysqli_query($conn, $query);

$query = "UPDATE user_table SET num_of_appt = 0 WHERE patient_id = '$userid'";
$result = mysqli_query($conn, $query);

$query = "UPDATE appointments SET `status` = 'cancelled' WHERE appointment_num = '$app_num'";
$result = mysqli_query($conn, $query);

if ($selected == 'today') {
    $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE() ORDER BY date_and_time ASC";
} else if ($selected == 'upcoming') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() + INTERVAL 1 DAY) AND (CURDATE() + INTERVAL 4 DAY) ORDER BY date_and_time ASC";
} else if ($selected == 'recent') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 3 DAY) AND CURDATE() ORDER BY date_and_time DESC";
} else if ($selected == 'lastweek') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 7 DAY) AND CURDATE() ORDER BY date_and_time DESC";
} else if ($selected == 'pending') {
    $query = "SELECT * FROM appointments WHERE status = 'pending' ORDER BY date_and_time ASC";
} else if ($selected == 'appointed') {
    $query = "SELECT * FROM appointments WHERE status = 'appointed' ORDER BY date_and_time DESC";
} else if ($selected == 'all') {
    $query = "SELECT * FROM appointments ORDER BY date_and_time DESC";
}

$result = mysqli_query($conn, $query);

require 'employee-ajax-table.php';

mysqli_close($conn);
