<?php

include 'db_conn.php';

$restore_id = $_POST['restoreId'];
$doc_type = $_POST['docType'];
$keyword = $_POST['keyword'];

// GET DETAILS OF THE ARCHIVED FILE
$query = "SELECT * FROM archive_documents WHERE doc_num = '$restore_id'";
$result = mysqli_query($conn, $query);
$pdf_file = "";
$sent_to = "";
$patient_name = "";
$date_uploaded = "";
$date_archived = "";
$emp_id = "";
$file_ext = "";

while ($row = mysqli_fetch_array($result)) {
    $pdf_file = $row['pdf_file'];
    $sent_to = $row['sent_to'];
    $patient_name = $row['patient_name'];
    $date_uploaded = $row['date_uploaded'];
    $date_archived = $row['date_archived'];
    $emp_id = $row['emp_id'];
    $file_ext = $row['file_ext'];
}

$query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext) 
VALUES ('$restore_id', '$doc_type', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$emp_id', '$file_ext')";
mysqli_query($conn, $query);

// DELETE FROM ARCHIVES
$query = "DELETE FROM archive_documents WHERE doc_num = '$restore_id'";
mysqli_query($conn, $query);

// UPDATE THE TABLE
$filter = "
AND (doc_num LIKE '%$keyword%' OR
patient_name LIKE '%$keyword%' OR
date_uploaded LIKE '%$keyword%' OR
date_archived LIKE '%$keyword%')
";
$like = $keyword == "" ? "" : $filter;

$query = "SELECT * FROM archive_documents WHERE doc_type = '$doc_type' $like LIMIT 0, 5";
$result = mysqli_query($conn, $query);
echo mysqli_error($conn);

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
                <button class = 'view-$doc_type' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                <button class = 'restore-$doc_type' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
            </div>
        </div>
        ";
    }
}
