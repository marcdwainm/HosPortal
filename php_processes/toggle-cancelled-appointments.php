<?php

include 'db_conn.php';

$query = "SELECT toggle FROM show_cancelled_appointments";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

$query = $row['toggle'] == '0' ? "UPDATE show_cancelled_appointments SET toggle = '1'" : "UPDATE show_cancelled_appointments SET toggle = '0'";
mysqli_query($conn, $query);

$result = $query == "UPDATE show_cancelled_appointments SET toggle = '1'" ? 'set to 1' : 'set to 0';

echo $result;
