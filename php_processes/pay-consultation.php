<?php

session_start();
include 'db_conn.php';

$appnum = $_POST['appnum'];
$patientid = $_SESSION['patientid'];
date_default_timezone_set('Asia/Manila');
$date_of_payment = date('Y-m-d H:i:s', time());

$query = "INSERT INTO bills (bill_num, names, prices, total, issued_to, date_of_payment, paid) 
VALUES ('$appnum', 'E-Consultation', '500', '500.00', '$patientid', '$date_of_payment', '1')";
mysqli_query($conn, $query);


include 'patient-transaction-table.php';


mysqli_close($conn);
