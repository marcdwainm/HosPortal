<?php

include 'db_conn.php';
$bill_num = $_POST['billnum'];
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s", time());
$payLab = true;

mysqli_query($conn, "UPDATE bills SET paid = '1', date_of_payment = '$date' WHERE bill_num = '$bill_num'");
mysqli_query($conn, "UPDATE documents SET paid = '1' WHERE doc_num = '$bill_num'");

include 'patient-transaction-table.php';
