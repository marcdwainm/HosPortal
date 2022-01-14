<?php
include 'db_conn.php';
session_start();
date_default_timezone_set('Asia/Manila');
$base64 = $_POST['base64'];
$doc_type = $_POST['doctype'];
$sent_to = $_POST['sentTo'];
$doc_num = date('Ymdhis', time()) . $sent_to;
$date_uploaded = date('Y-m-d H:i:s', time());
$file_ext = $_POST['fileExt'];
$emp_id = $_SESSION['emp_id'];
$withBill = isset($_POST['withBill']) ? '1' : '0';

if (isset($_POST['pname'])) {
    $p_name = ucwords($_POST['pname']);
} else {
    $p_name = '';
}



$fullname = '';

if ($sent_to == '0000') {
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, file_ext) 
VALUES('$doc_num', '$doc_type', '$base64', '0000', '$p_name', '$date_uploaded', '$file_ext')";

    $result = mysqli_query($conn, $query);

    if ($result === false) {
        echo "Error while uploading the file: " . mysqli_error($conn);
    }
} else {
    $query = "SELECT * FROM user_table WHERE patient_id = '$sent_to'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
    }

    $p_name = $fullname;
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext) 
VALUES('$doc_num', '$doc_type', '$base64', '$sent_to', '$p_name', '$date_uploaded', '$emp_id', '$file_ext')";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        echo "Error while uploading the file: " . mysqli_error($conn);
    }

    if (isset($_POST['issuedByMedtech'])) {
        //IF ISSUED BY MEDTECH NOTIFY PATIENT ABOUT BILL ISSUE
        mysqli_query($conn, "INSERT INTO patients_notifications (emp_id, patient_id, notif_type, date_notified, seen) 
        VALUES ('$emp_id', '$sent_to', 'draftbill', '$date_uploaded', '0')");
    } else {
        $query = "INSERT INTO patients_notifications (patient_id, notif_type, document_num, date_notified, with_bill)
    VALUES  ('$sent_to', '$doc_type', '$doc_num', '$date_uploaded', '$withBill')";
        mysqli_query($conn, $query);
    }
}

mysqli_close($conn);
