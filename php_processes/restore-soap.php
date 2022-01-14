<?php

include 'db_conn.php';

$restore_id = $_POST['restoreId'];
$keyword = $_POST['keyword'];

$query = "SELECT * FROM archive_soap WHERE soap_id = '$restore_id'";
$result = mysqli_query($conn, $query);
$appointment_num = "";
$appointment_date_time = "";
$date_created = "";
$patient_id = "";
$patient_fullname = "";
$soap_note = "";

while ($row = mysqli_fetch_array($result)) {
    $appointment_num = $row['appointment_num'];
    $appointment_date_time = $row['appointment_date_time'];
    $date_created = $row['date_created'];
    $patient_id = $row['patient_id'];
    $patient_fullname = $row['patient_fullname'];
    $soap_note = $row['soap_note'];
}

//INSERT BACK TO SOAP TABLE
$query = "INSERT INTO soap_notes (soap_id, appointment_num, appointment_date_time, date_created, patient_id, soap_note) 
VALUES ('$restore_id', '$appointment_num', '$appointment_date_time', '$date_created', '$patient_id', '$soap_note')";
mysqli_query($conn, $query);

//DELETE FROM ARCHIVE TABLE
$query = "DELETE FROM archive_soap WHERE soap_id = '$restore_id'";
mysqli_query($conn, $query);

//UPDATE TABLE
$query = "SELECT * FROM archive_soap WHERE (appointment_date_time LIKE '%$keyword%' OR
patient_fullname LIKE '%$keyword%' OR
DATE_FORMAT(date_created, '%W %M %d, %Y') LIKE '%$keyword%' OR
DATE_FORMAT(date_created, '%h:%i') LIKE '%$keyword%' OR
DATE_FORMAT(date_archived, '%W %M %d, %Y') LIKE '%$keyword%' OR
DATE_FORMAT(date_archived, '%h:%i') LIKE '%$keyword%') LIMIT 0, 5";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) <= 0) {
    echo "<span class = 'no-appointments'>Archive Empty</span>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $patient_name = $row['patient_fullname'];
        $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_created']));
        $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
        $soap_id = $row['soap_id'];

        echo "
            <div class='archive-table-content four-fr' id = 'archive-soap-table'>
                <span>$patient_name</span>
                <span>$date_uploaded</span>
                <span>$date_archived</span>
                <div>
                    <button class = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                    <button class = 'restore-soap' value = '$soap_id'><i class='fas fa-trash-restore fa-lg'></i></button>
                </div>
            </div>
            ";
    }
}
