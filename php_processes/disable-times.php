<?php

include 'db_conn.php';

$chosen_date = $_POST['chosen_date'];
$data = array();

$query = "SELECT * FROM appointments WHERE DATE(date_and_time) = '$chosen_date' AND (status = 'pending' OR status = 'ongoing')";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $data[] = array(
        'start' => substr($row['date_and_time'], -8),
        'end' => substr($row['date_and_time_finish'], -8)
    );
}

echo json_encode($data);
