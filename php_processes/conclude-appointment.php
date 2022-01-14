<?php

include 'db_conn.php';

$appnum = $_POST['appnum'];

$query = "UPDATE appointments SET `status` = 'appointed', `meet_link` = '' WHERE appointment_num = '$appnum'";
mysqli_query($conn, $query);

mysqli_close($conn);
