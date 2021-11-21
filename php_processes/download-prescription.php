<?php
include 'db_conn.php';

$query = "SELECT * FROM documents WHERE doc_num = '20211120022836'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $base64 = $row['pdf_file'];
    echo $base64;
}
