<?php

include 'db_conn.php';

$disabled_dates = array();
$query = "SELECT * FROM disabled_dates";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    array_push($disabled_dates, $row['date']);
}

echo implode(", ", $disabled_dates);
