<?php
session_start();
include 'db_conn.php';

$fullname_medtech = $_SESSION['fullname'];
$emp_id = $_SESSION['emp_id'];
$pname = ucwords($_POST['pname']);
$test_type = ucwords($_POST['testType']);
$collection_date = $_POST['collectionDate'];
$result_date = $_POST['resultDate'];
$pid = $_POST['pid'];
$html = "";
$base64 = "";
$corresponding_bill = isset($_POST['correspondingBill']) ? $_POST['correspondingBill'] : '';

$date = date('Ymdhis', time());
$doc_num = $date . $pid;

$date_uploaded = date('Y-m-d H:i:s', time());


if (isset($_POST['issuedByMedtech'])) {
    //IF ISSUED BY MEDTECH NOTIFY PATIENT ABOUT BILL ISSUE
    mysqli_query($conn, "INSERT INTO patients_notifications (emp_id, patient_id, notif_type, date_notified, seen) 
    VALUES ('$emp_id', '$pid', 'draftbill', '$date_uploaded', '0')");

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$pid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = 'Twin Care Portal | Laboratory Testing';
    $headers = "Good day, our dear patient!";
    $message = "Twin Care has issued you a bill for your laboratory testing. To proceed with your payment, kindly visit www.twincareportal.online, or contact 0925-734-7552 to further discuss your preferred mode of payment.";

    mail($to, $subject, $message, $headers);
}

//IF PATIENT IS PORTAL REGISTERED
if ($pid != '0000') {

    $query = "SELECT * FROM user_table WHERE patient_id = '$pid'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $sex = ucfirst($row['sex']);
        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;
        $date = date("m/d/Y", time());
        $address = $row['address'];

        $base64 = "";
    }
}

//IF PATIENT NOT PORTAL REGISTERED
else {
    $date = date("m/d/Y", time());
    $base64 = "";
}

$query = "INSERT INTO lab_drafts (`doc_num`, `emp_id`, `patient_id`, patient_fullname, `test_type`, `status`, `html_draft`, date_uploaded, collection_date, estimated_result, corresponding_bill)
    VALUES ('$doc_num', '$emp_id', '$pid', '$pname', '$test_type', 'pending', '$base64', '$date_uploaded', '$collection_date', '$result_date', '$corresponding_bill')";

mysqli_query($conn, $query);

include 'medtech-dynamic-table.php';
