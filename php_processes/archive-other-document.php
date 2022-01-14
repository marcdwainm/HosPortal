<?php

include 'db_conn.php';

$doc_num = $_POST['docnum'];
$pid = $_POST['pid'];

$result = mysqli_query($conn, "SELECT * FROM other_documents WHERE docnum = '$doc_num'");

//DOCUMENT DETAILS
$pdf_file = "";
$date_uploaded = "";
$file_ext = "";
$patient_fullname = "";
date_default_timezone_set('Asia/Manila');
$date_archived = date("Y-m-d H:i:s", time());

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $pdf_file = $row['pdf_file'];
        $date_uploaded = $row['date_uploaded'];
        $file_ext = $row['file_ext'];
    }
}

//GET PATIENT FULLNAME

$result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$pid'");
while ($row = mysqli_fetch_array($result)) {
    $first_name = $row['first_name'];
    $middle_name = substr($row['middle_name'], 0, 1) . ".";
    $last_name = $row['last_name'];

    $patient_fullname = "$first_name $middle_name $last_name";
}

//INSERT TO OTHERS ARCHIVE
mysqli_query($conn, "INSERT INTO archive_other (doc_num, pdf_file, patient_id, patient_name, date_uploaded, date_archived, file_ext) 
VALUES ('$doc_num', '$pdf_file', '$pid', '$patient_fullname', '$date_uploaded', '$date_archived', '$file_ext')");
echo mysqli_error($conn);

//DELETE FROM ORIG TABLE
mysqli_query($conn, "DELETE FROM other_documents WHERE docnum = '$doc_num'");

//UPDATE TABLE
$query_nest = "SELECT * FROM other_documents WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";
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

mysqli_close($conn);
