<?php
include 'db_conn.php';

$docnum = $_POST['docnum'];

$query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $base64 = $row['pdf_file'];
    echo $base64;
}
