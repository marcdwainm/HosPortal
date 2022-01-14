<?php
include 'db_conn.php';
session_start();

$base64 = $_POST['blob']; //base64 encoded
$doctype = $_POST['doctype'];
$pid = $_POST['pid'];
$p_name = ucwords($_POST['pname']);
$emp_id = $_SESSION['emp_id'];
$file_ext = $_POST['fileExt'];

date_default_timezone_set('Asia/Manila');
$date_uploaded = date('Y-m-d H:i:s', time());
$date = date('Ymdhis', time());
$docnum = $date . $pid;


$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $lastname = $row['last_name'];
    $middlename = substr($row['middle_name'], 0, 1);
    $firstname = $row['first_name'];
    $p_name = "$firstname $middlename. $lastname";
}

$query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext) 
VALUES('$docnum', '$doctype', '$base64', '$pid', '$p_name', '$date_uploaded', '$emp_id', '$file_ext')";

mysqli_query($conn, $query);

if ($pid != '0000') {
    $date_convert = strtotime($date);
    $date_notified = date('Y-m-d H:i:s', $date_convert);

    $query = "INSERT INTO patients_notifications (patient_id, notif_type, document_num, date_notified) VALUES('$pid', '$doctype', '$docnum', '$date_notified')";
    mysqli_query($conn, $query);
}

mysqli_close($conn);
