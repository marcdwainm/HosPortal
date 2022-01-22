<?php

include 'db_conn.php';

$doc_num = $_POST['docnum'];

$query = "SELECT * FROM lab_drafts WHERE doc_num = '$doc_num'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_array($result);

$billnum = $row['corresponding_bill'];

if ($billnum != '') {
    //check if bill is paid
    $query = "SELECT * FROM bills WHERE bill_num = '$billnum'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    if ($row['paid'] == '0') {
        echo '&withBill=true';
    } else {
        echo '';
    }
}
