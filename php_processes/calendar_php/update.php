<?php
include '../db_conn.php';
if (isset($_POST["id"])) {
    $start = $_POST['start'];
    $end = $_POST['end'];
    $id = $_POST['id'];

    //Variables for notifs
    $pid = substr($id, -4);
    date_default_timezone_set('Asia/Manila');
    $date = date("Y-m-d H:i:s", time());

    $query = "UPDATE appointments SET date_and_time='$start', date_and_time_finish='$end' WHERE appointment_num = '$id'";
    mysqli_query($conn, $query);

    $query = "INSERT INTO patients_notifications (patient_id, notif_type, appointment_num, date_notified, appointment_date) VALUES ('$pid', 'resched', '$id', '$date', '$start')";
    mysqli_query($conn, $query);
}
