<?php
include 'db_conn.php';
session_start();
$value = $_POST['value'];
$pid = $_SESSION['patientid'];

$query = "UPDATE patients_notifications SET seen = '1' WHERE patient_id = '$pid' AND (appointment_num = '$value' OR document_num = '$value')";

mysqli_query($conn, $query);

echo mysqli_error($conn);

mysqli_close($conn);
