<?php

include 'db_conn.php';

$billnum = $_POST['billnum'];

mysqli_query($conn, "DELETE FROM bills WHERE bill_num = '$billnum'");

mysqli_close($conn);
