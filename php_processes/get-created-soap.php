<?php

include 'db_conn.php';

$appnum = $_POST['appnum'];

$query = "SELECT * FROM soap_notes WHERE appointment_num = '$appnum'";
$result = mysqli_query($conn, $query);
$soapid = "";

while ($row = mysqli_fetch_array($result)) {
    $soapid = $row['soap_id'];
}

echo $soapid;
