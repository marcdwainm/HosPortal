<?php

session_start();
include 'db_conn.php';

$emp_id = $_SESSION['emp_id'];
$unseen_count = 0;

$query = "SELECT * FROM notifications WHERE emp_id = '$emp_id' OR emp_id = 'all' AND seen = '0'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    if ($row['seen'] == '0') {
        $unseen_count += 1;
    }
}

if ($unseen_count > 30) {
    $unseen_count = 30;
}

echo $unseen_count;
