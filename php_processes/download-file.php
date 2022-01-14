<?php

include 'db_conn.php';

$doc_num = $_POST['docnum'];

$query = "SELECT * FROM documents WHERE doc_num = '$doc_num'";
$result = mysqli_query($conn, $query);
$base64 = "";
$file_ext = "";
$file = array();

while ($row = mysqli_fetch_array($result)) {
    $base64 = $row['pdf_file'];
    $file_ext = $row['file_ext'];

    $file = array(
        'base64' => $base64,
        'file_ext' => $file_ext
    );
}

echo json_encode($file);
