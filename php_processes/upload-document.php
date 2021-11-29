<?php
include 'db_conn.php';

date_default_timezone_set('Asia/Manila');
$doc_num = date('Ymdhis', time());
$base64 = $_POST['base64'];
$doc_type = $_POST['doctype'];
$sent_to = $_POST['sentTo'];
$date_uploaded = date('Y-m-d h:i:s', time());

if (isset($_POST['pname'])) {
    $p_name = ucwords($_POST['pname']);
} else {
    $p_name = '';
}

$fullname = '';

if ($sent_to == '0000') {
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded) 
VALUES('$doc_num', '$doc_type', '$base64', '0000', '$p_name', '$date_uploaded')";
} else {
    $query = "SELECT * FROM user_table WHERE patient_id = '$sent_to'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
    }

    $p_name = $fullname;
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded) 
VALUES('$doc_num', '$doc_type', '$base64', '$sent_to', '$p_name', '$date_uploaded')";
}


mysqli_query($conn, $query);

mysqli_close($conn);
