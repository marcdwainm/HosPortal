<?php
include 'db_conn.php';

date_default_timezone_set('Asia/Manila');
$doc_num = date('Ymdhis', time());
$base64 = $_POST['base64'];
$doc_type = $_POST['doctype'];
$sent_to = $_POST['sentTo'];

$query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to) 
VALUES('$doc_num', '$doc_type', '$base64', '$sent_to')";

mysqli_query($conn, $query);

mysqli_close($conn);
