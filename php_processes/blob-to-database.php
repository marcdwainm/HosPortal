<?php
include 'db_conn.php';
session_start();

$base64 = $_POST['blob']; //base64 encoded
$doctype = $_POST['doctype'];
$pid = $_POST['pid'];
$p_name = ucwords($_POST['pname']);
$emp_id = $_SESSION['emp_id'];
$file_ext = $_POST['fileExt'];
$withBill = isset($_POST['withBill']) ? '1' : '0';

$docnum = "";

date_default_timezone_set('Asia/Manila');
$date_uploaded = date('Y-m-d H:i:s', time());
$date = date('Ymdhis', time());

if ($_POST['docnum'] == "") {
    $docnum = $date . $pid;
} else {
    $docnum = $_POST['docnum'];
}

$paid_val = '0';
if ($doctype == 'labresult') {
    $paid_val = $withBill == '0' ? '1' : '0';
}


$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $lastname = $row['last_name'];
    $middlename = substr($row['middle_name'], 0, 1);
    $firstname = $row['first_name'];
    $p_name = "$firstname $middlename. $lastname";
}

$query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext, paid) 
VALUES('$docnum', '$doctype', '$base64', '$pid', '$p_name', '$date_uploaded', '$emp_id', '$file_ext', '$paid_val')";
mysqli_query($conn, $query);

$query = "INSERT INTO documents_patient_copy (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext, paid) 
VALUES('$docnum', '$doctype', '$base64', '$pid', '$p_name', '$date_uploaded', '$emp_id', '$file_ext', '$paid_val')";
mysqli_query($conn, $query);

echo $docnum;

if ($pid != '0000') {
    $date_convert = strtotime($date);
    $date_notified = date('Y-m-d H:i:s', $date_convert);

    $query = "INSERT INTO patients_notifications (patient_id, notif_type, document_num, date_notified, with_bill) VALUES('$pid', '$doctype', '$docnum', '$date_notified', '$withBill')";
    mysqli_query($conn, $query);

    $docmsg = $doctype == 'labresult' ? "Lab Result" : "Prescription";

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$pid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = "Twin Care Portal | New $docmsg";
    $headers = "Good day, our dear patient!";
    $message = "The doctor has sent you a $docmsg. Visit www.twincareportal.online to view your document.";

    mail($to, $subject, $message, $headers);
}

mysqli_close($conn);
