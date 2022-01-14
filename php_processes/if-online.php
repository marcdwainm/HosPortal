<?php

include 'db_conn.php';

$pid = $_POST['pid'];
$online = false;

$query = "SELECT * FROM user_table WHERE patient_id = '$pid'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    if ($row['online'] == '1') {
        $online = true;
    } else {
        $online = false;
    }
}

if ($online == true) {
    echo "
        <div><i class='fas fa-check-circle fa-3x'></i></div>
        <span>
            The user is currently online. Click the button below to set-up a chat room between you and the patient.
        </span>
    ";
} else {
    echo "
        <div><i class='fas fa-times-circle fa-3x'></i></div>
        <span>
            The user is currently offline, but you may set-up a room now. If the patient fails to join within the given time, you may try again later or mark the appointment as missed.
        </span>
    ";
}
