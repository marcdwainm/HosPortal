<?php
session_start();
include 'db_conn.php';

$doctor_fullname = $_SESSION['fullname'];

if (!isset($_POST['fromLabRes'])) {
    $appnum = $_POST['appointmentNum'];
    $pid = substr($_POST['appointmentNum'], -4);
} else {
    date_default_timezone_set('Asia/Manila');
    $appnum = date("Ymdhis", time()) . $_POST['appointmentNum'];
    $pid = $_POST['appointmentNum'];
}

$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);
$patient_name = "";
$patient_id = "";

while ($row = mysqli_fetch_array($result)) {
    $first_name = $row['first_name'];
    $middle_name = $row['middle_name'];
    $last_name = $row['last_name'];

    $patient_name = "$first_name $middle_name[0]. $last_name";
}

$date = Date("m/d/Y", time());

echo "
    <span><b>Bill no.:</b> <span class = 'bill-num'>$appnum</span></span>
    <span><b>Patient Name:</b> $patient_name</span>
    <span><b>Issued by:</b> $doctor_fullname</span>
    <span><b>Date Issued:</b> $date</span>
";
