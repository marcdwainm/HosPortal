<?php

include 'db_conn.php';

$query = "SELECT toggle FROM appointment_booking_toggle";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

$query = $row['toggle'] == '0' ? "UPDATE appointment_booking_toggle SET toggle = '1'" : "UPDATE appointment_booking_toggle SET toggle = '0'";
mysqli_query($conn, $query);

$result = $query == "UPDATE appointment_booking_toggle SET toggle = '1'" ? 'set to 1' : 'set to 0';

echo $result;
