<?php

include 'db_conn.php';

$pid = $_POST['patientId'];

$query = "SELECT * FROM `soap_notes` WHERE `patient_id` = '$pid' ORDER BY UNIX_TIMESTAMP(`appointment_date_time`) DESC LIMIT 0, 1";
$result = mysqli_query($conn, $query);

$soapId = "";

while ($row = mysqli_fetch_array($result)) {
    $soapId = $row['soap_id'];
}

echo $soapId;
