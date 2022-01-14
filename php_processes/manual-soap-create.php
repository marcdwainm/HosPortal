<?php

include 'db_conn.php';

$pid = $_POST['pid'];

date_default_timezone_set('Asia/Manila');
$date_created = date("Y-m-d H:i:s", time());

$query = "INSERT INTO soap_notes (appointment_num, appointment_date_time, date_created, patient_id, soap_note) 
VALUES ('manual', '$date_created', '$date_created', '$pid', ' ###  ###  ### ')";
mysqli_query($conn, $query);

$query = "SELECT * FROM soap_notes WHERE date_created = '$date_created'";
$result = mysqli_query($conn, $query);
$soapid = "";

while ($row = mysqli_fetch_array($result)) {
    $soapid = $row['soap_id'];
}

echo $soapid;

mysqli_close($conn);
