<?php

include 'db_conn.php';
session_start();

$names = $_POST['namesString'];
$prices = $_POST['pricesString'];
$total = $_POST['total'];
$bill_num = $_POST['billNum']; //SAME WITH APPOINTMENTNUM
$selected = $_POST['selected'];
$issued_to = substr($bill_num, -4);
$issued_by = $_SESSION['emp_id'];
$date_issued = Date("Y-m-d", time());
$from_online = isset($_POST['fromOnline']) ? '1' : '0';

$corresponding_doc = isset($_POST['correspondingDoc']) ? $_POST['correspondingDoc'] : "";
if ($_POST['labdraftissue'] == 'true') {
    $query = "SELECT * FROM lab_drafts WHERE corresponding_bill = '$bill_num'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $docnum = $row['doc_num'];

    $corresponding_doc = $docnum;
}

$query = "INSERT INTO bills (bill_num, corresponding_doc, names, prices, total, issued_to, issued_by, date_issued, tied_to_online_appt) 
VALUES ('$bill_num', '$corresponding_doc', '$names', '$prices', '$total', '$issued_to', '$issued_by', '$date_issued', '$from_online')";

mysqli_query($conn, $query);

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
