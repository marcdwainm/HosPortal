<?php

include 'db_conn.php';
session_start();

$patient_id = $_POST['patientId'];
date_default_timezone_set('Asia/Manila');
$date_string = date("Ymdhis", time());
$docnum = $date_string . $patient_id;
$file_base_64 = $_POST['fileBase64'];
$file_ext = $_POST['fileExt'];
$date = date("Y-m-d H:i:s", time());
$emp_id = $_SESSION['emp_id'];

//INSERT FILE TO TABLE
$query = "INSERT INTO other_documents (patient_id, docnum, pdf_file, date_uploaded, emp_id, file_ext) 
VALUES ('$patient_id', '$docnum', '$file_base_64', '$date', '$emp_id', '$file_ext')";
mysqli_query($conn, $query);


//UPDATE THE OTHER DOCUMENTS TABLE
$query_nest = "SELECT * FROM other_documents WHERE patient_id = '$patient_id' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";
$result2 = mysqli_query($conn, $query_nest);

if (mysqli_num_rows($result2) > 0) {
    while ($row = mysqli_fetch_array($result2)) {
        $date_uploaded = strtotime($row['date_uploaded']);
        $date_uploaded = date("M d, Y / h:i A", $date_uploaded);
        $doc_num = $row['docnum'];

        echo "
            <div class = 'other-docs-table-content'>
                <span>$date_uploaded</span>
                <div>
                    <div class = 'soap-btns'>
                        <button class = 'view-other' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                        <button class = 'archive-other' value = '$doc_num'><i class='fas fa-archive fa-lg'></i></button>
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
