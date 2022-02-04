<?php

include 'db_conn.php';

session_start();

$pid = $_SESSION['patientid'];
$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

$p_fullname = $row['first_name'] . " " . substr($row['middle_name'], 0, 1) . ". " . $row['last_name'];

$date = date('Y-m-d H:i:s', time());

$query = "INSERT INTO notifications (emp_id, notif_type, patient_fullname, date_time) VALUES ('all', 'payment', '$p_fullname', '$date')";
mysqli_query($conn, $query);
