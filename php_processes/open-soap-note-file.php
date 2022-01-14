<?php

include 'db_conn.php';

$appnum = isset($_POST['appnum']) ? $_POST['appnum'] : "";
$soap_id = "";

if (isset($_POST['soap_id'])) {
    $soap_id = $_POST['soap_id'];

    $query = "SELECT * FROM soap_notes WHERE soap_id = '$soap_id'";
    $result = mysqli_query($conn, $query);
    $soap_note_content = "";

    while ($row = mysqli_fetch_array($result)) {
        $soap_note_content = $row['soap_note'];
    }
} else {
    $query = "SELECT * FROM soap_notes WHERE appointment_num = '$appnum'";
    $result = mysqli_query($conn, $query);
    $soap_note_content = "";

    while ($row = mysqli_fetch_array($result)) {
        $soap_note_content = $row['soap_note'];
    }
}


echo $soap_note_content;
