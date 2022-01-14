<?php

include 'db_conn.php';

$appnum = $_POST['appnum'];

$query = "SELECT * FROM appointments WHERE appointment_num = '$appnum'";
$meetlink = '';

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $meetlink = $row['meet_link'];
}

echo $meetlink;
