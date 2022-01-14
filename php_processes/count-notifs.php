<?php

session_start();
include 'db_conn.php';

$patient_id = $_SESSION['patientid'];
$unseen_count = 0;

$query = "SELECT * FROM patients_notifications WHERE patient_id = '$patient_id' AND seen = '0'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    if ($row['seen'] == '0') {
        $unseen_count += 1;
    }
}

if ($unseen_count > 30) {
    $unseen_count = 30;
}

echo $unseen_count;
