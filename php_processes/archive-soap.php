<?php

include 'db_conn.php';

session_start();
$soapid = $_POST['soapid'];
$pid = $_POST['pid'];
$position = $_SESSION['position'];

// GET THE DETAILS OF THE SOAP NOTE
$query = "SELECT * FROM soap_notes WHERE soap_id = '$soapid'";
$result = mysqli_query($conn, $query);
$appointment_num = "";
$appointment_date_time = "";
$date_created = "";
$patient_id = "";
$soap_note = "";
$patient_fullname = "";
date_default_timezone_set("Asia/Manila");
$date_archived = date("Y-m-d H:i:s", time());


while ($row = mysqli_fetch_array($result)) {
    $appointment_num = $row['appointment_num'];
    $appointment_date_time = $row['appointment_date_time'];
    $date_created = $row['date_created'];
    $patient_id = $row['patient_id'];
    $soap_note = $row['soap_note'];
}

$query = "SELECT * FROM user_table WHERE patient_id = '$patient_id'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $patient_fullname = $row['first_name'] . " " . substr($row['middle_name'], 0, 1) . ". " . $row['last_name'];
}

// PUT THE DETAILS ON THE SOAP ARCHIVE

$query = "INSERT INTO archive_soap (soap_id, appointment_num, appointment_date_time, date_created, patient_id, patient_fullname, soap_note, date_archived) 
VALUES ('$soapid', '$appointment_num', '$appointment_date_time', '$date_created', '$patient_id', '$patient_fullname', '$soap_note', '$date_archived')";

mysqli_query($conn, $query);

// DELETE THE SOAP FROM THE ORIG TABLE

$query = "DELETE FROM soap_notes WHERE soap_id = '$soapid'";
mysqli_query($conn, $query);

// ECHO THE TABLE OF THE PATIENT

$query_nest = "SELECT * FROM soap_notes WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_created) DESC";
$result2 = mysqli_query($conn, $query_nest);

if (mysqli_num_rows($result2) > 0) {
    while ($row = mysqli_fetch_array($result2)) {
        $date_time = $row['appointment_date_time'];
        $date_time = strtotime($date_time);
        $date_time = date("M d, Y / h:i A", $date_time);
        $date_created = $row['date_created'];
        $date_created = strtotime($date_created);
        $date_created = date("M d, Y / h:i A", $date_created);
        $app_num = $row['appointment_num'];
        $soap_id = $row['soap_id'];

        echo "
            <div class = 'soap-table-content'>
                <span>$date_created</span>
                <span>$date_time</span>
                <div>
                    <div class = 'soap-btns'>
                        <button id = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>";

        if ($position == 'doctor') {
            echo "<button id = 'edit-soap' value = '$soap_id'><i class='far fa-edit fa-lg'></i></button>";
        }

        echo "
                        <button class = 'archive-soap' value = '$soap_id'><i class='fas fa-archive fa-lg'></i></button>
                    </div>
                </div>
            </div>
            ";
    }
} else {
    echo '
        <span class = "no-appointments font-size-bigger">No records yet</span>
    ';
}
