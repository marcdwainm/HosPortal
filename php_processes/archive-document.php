<?php

include 'db_conn.php';

$docnum = $_POST['docnum'];
$doctype = $_POST['doctype'];

//GET DETAILS OF PRESCRIPTION
$query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
$result = mysqli_query($conn, $query);
$pdf_file = "";
$sent_to = "";
$patient_name = "";
$date_uploaded = "";
$emp_id = "";
$file_ext = "";

while ($row = mysqli_fetch_array($result)) {
    $pdf_file = $row['pdf_file'];
    $sent_to = $row['sent_to'];
    $patient_name = $row['patient_name'];
    $date_uploaded = $row['date_uploaded'];
    $emp_id = $row['emp_id'];
    $file_ext = $row['file_ext'];
}

date_default_timezone_set('Asia/Manila');
$curr_date = date("Y-m-d H:i:s", time());

//PUT INTO DOCUMENT ARCHIVE
if ($doctype == 'prescription') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext)
    VALUES ('$docnum', 'prescription', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);
} else if ($doctype == 'labresult') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext) 
        VALUES ('$docnum', 'labresult', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);
}

//DELETE FROM DOCUMENT TABLE
$query = "DELETE FROM documents WHERE doc_num = '$docnum'";
mysqli_query($conn, $query);


//UPDATE TABLE

if ($doctype == 'prescription') {
    $query = "SELECT * FROM documents WHERE doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        echo "<div class = 'empty'>You don't have any Documents</div>";
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $doc_type = ucwords($row['doc_type']);
            $pname = $row['patient_name'];
            $date_uploaded = strtotime($row['date_uploaded']);
            $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
            $doc_num = $row['doc_num'];

            echo "
                <div class='e-contents three-fr'>
                    <span>$pname</span>
                    <span>$date_up_formatted</span>
                    <div class = 'test'>
                        <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                        <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                        <button class = 'archive-prescription' value = '$doc_num'><i class='fas fa-archive'></i></button>
                    </div>
                </div>
            ";
        }
    }
} else if ($doctype == 'labresult') {
    $query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 0) {
        echo "<div class = 'empty'>You don't have any Documents</div>";
    } else {
        while ($row = mysqli_fetch_array($result)) {
            $doc_type = ucwords($row['doc_type']);
            $pname = $row['patient_name'];
            $date_uploaded = strtotime($row['date_uploaded']);
            $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
            $doc_num = $row['doc_num'];

            echo "
                    <div class='e-contents three-fr'>
                        <span>$pname</span>
                        <span>$date_up_formatted</span>
                        <div class = 'test'>
                            <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                            <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                            <button class = 'archive-labresult' value = '$doc_num'><i class='fas fa-archive'></i></button>
                        </div>
                    </div>
                ";
        }
    }
}
