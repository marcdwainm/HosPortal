<?php
include 'db_conn.php';
session_start();
$value = $_POST['value'];
$eid = $_SESSION['emp_id'];

$query = "UPDATE notifications SET seen = '1' WHERE emp_id = '$eid' AND appointment_num = '$value'";

mysqli_query($conn, $query);

echo mysqli_error($conn);

mysqli_close($conn);
