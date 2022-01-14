<?php

include 'db_conn.php';

$docnum = $_POST['docnum'];

$query = "SELECT * from lab_drafts WHERE doc_num = '$docnum'";

$result = mysqli_query($conn, $query);
$html_base64 = "";

while ($row = mysqli_fetch_array($result)) {
    $html_base64 = $row['html_draft'];
}

echo $html_base64;
