<?php

include 'db_conn.php';

$keyword = $_POST['keyword'];
$table_type = $_POST['tableType'];

if ($table_type != 'soap' && $table_type != 'other') {
    $query = "SELECT * FROM archive_documents WHERE doc_type = '$table_type' AND (doc_num LIKE '%$keyword%' OR
    patient_name LIKE '%$keyword%' OR
    DATE_FORMAT(date_uploaded, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_uploaded, '%h:%i') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%h:%i,') LIKE '%$keyword%') ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) <= 0) {
        echo "<span class = 'no-appointments'>Not Found</span>";
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $patient_name = $row['patient_name'];
            $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_uploaded']));
            $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
            $doc_num = $row['doc_num'];

            echo "
            <div class='archive-table-content four-fr'>
                <span>$patient_name</span>
                <span>$date_uploaded</span>
                <span>$date_archived</span>
                <div>
                    <button class = 'view-$table_type' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                    <button class = 'restore-$table_type' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
                </div>
            </div>
            ";
        }
    }
} else if ($table_type == 'other') {
    $query = "SELECT * FROM archive_other 
    WHERE doc_num LIKE '%$keyword%' OR
    patient_id LIKE '%$keyword%' OR
    patient_name LIKE '%$keyword%' OR
    DATE_FORMAT(date_uploaded, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_uploaded, '%h:%i') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%h:%i') LIKE '%$keyword%' ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) <= 0) {
        echo "<span class = 'no-appointments'>Archive Empty</span>";
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $patient_name = $row['patient_name'];
            $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_uploaded']));
            $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
            $doc_num = $row['doc_num'];

            echo "
            <div class='archive-table-content four-fr'>
                <span>$patient_name</span>
                <span>$date_uploaded</span>
                <span>$date_archived</span>
                <div>
                    <button class = 'view-other' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                    <button class = 'restore-other' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
                </div>
            </div>
            ";
        }
    }
} else if ($table_type == 'soap') {
    $query = "SELECT * FROM archive_soap WHERE (appointment_date_time LIKE '%$keyword%' OR
    patient_fullname LIKE '%$keyword%' OR
    DATE_FORMAT(date_created, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_created, '%h:%i') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%W %M %d, %Y') LIKE '%$keyword%' OR
    DATE_FORMAT(date_archived, '%h:%i') LIKE '%$keyword%') ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) <= 0) {
        echo "<span class = 'no-appointments'>Not Found</span>";
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $patient_name = $row['patient_fullname'];
            $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_created']));
            $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
            $soap_id = $row['soap_id'];

            echo "
            <div class='archive-table-content four-fr'>
                <span>$patient_name</span>
                <span>$date_uploaded</span>
                <span>$date_archived</span>
                <div>
                    <button class = 'view-$table_type' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                    <button class = 'restore-$table_type' value = '$soap_id'><i class='fas fa-trash-restore fa-lg'></i></button>
                </div>
            </div>
            ";
        }
    }
}
