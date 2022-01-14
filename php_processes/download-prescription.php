<?php
include 'db_conn.php';

$docnum = $_POST['docnum'];

$query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
$result = mysqli_query($conn, $query);
$arr = array();

while ($row = mysqli_fetch_array($result)) {
    $base64 = $row['pdf_file'];
    $file_ext = $row['file_ext'];
    $doc_type = $row['doc_type'];

    $arr = array(
        'base64' => $base64,
        'file_ext' => $file_ext,
        'doctype' => $doc_type
    );
}

echo json_encode($arr);
