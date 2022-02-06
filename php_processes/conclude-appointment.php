<?php

include 'db_conn.php';

$appnum = $_POST['appnum'];

$query = "UPDATE appointments SET `status` = 'appointed', `meet_link` = '' WHERE appointment_num = '$appnum'";
mysqli_query($conn, $query);

$pid = substr($appnum, -4);
mysqli_query($conn, "UPDATE user_table SET has_appointment = 0 WHERE patient_id = '$pid'");

mysqli_close($conn);
