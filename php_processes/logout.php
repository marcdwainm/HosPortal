<?php

session_start();

include 'db_conn.php';

$patientid = $_SESSION['patientid'];

$query = "UPDATE user_table SET online = '0' WHERE patient_id = '$patientid'";
mysqli_query($conn, $query);

mysqli_close($conn);

session_destroy();
