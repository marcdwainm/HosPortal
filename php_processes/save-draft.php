<?php

include 'db_conn.php';

$contents = base64_encode($_POST['savedContents']);
$docnum = $_POST['docnum'];

$query = "UPDATE lab_drafts SET html_draft = '$contents' WHERE doc_num = '$docnum'";


if (!mysqli_query($conn, $query)) {
    mysqli_error($conn);
}
