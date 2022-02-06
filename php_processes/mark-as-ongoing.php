<?php

include 'db_conn.php';

//MARK AS ONGOING
$appnum = $_POST['appnum'];
$selected = $_POST['selected'];
$meetlink = $_POST['meetlink'];
$date = Date("Y-m-d H:i:s", time());
$appointment_date = '';
$pid = substr($appnum, -4);

$query = "UPDATE appointments SET status = 'ongoing', meet_link = '$meetlink' WHERE appointment_num = '$appnum'";
mysqli_query($conn, $query);

$query = "SELECT * FROM appointments WHERE appointment_num = '$appnum'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $appointment_date = $row['date_and_time'];
}

$query = "INSERT INTO patients_notifications (patient_id, notif_type, appointment_num, date_notified, appointment_date) 
VALUES ('$pid', 'chatroom', '$appnum', '$date', '$appointment_date')";
mysqli_query($conn, $query);

$to = $email;
$subject = 'Twin Care Portal | Online Appointment';
$headers = "Good day, our dear patient!";
$message = "The doctor has set-up a meeting room. Kindly log in to www.twincareportal.online and join the meeting room.";

mail($to, $subject, $message, $headers);

if ($selected == 'today') {
    $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE() ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'upcoming') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() + INTERVAL 1 DAY) AND (CURDATE() + INTERVAL 4 DAY) ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'recent') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 3 DAY) AND CURDATE() ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'lastweek') {
    $query = "SELECT * FROM appointments WHERE date_and_time BETWEEN (CURDATE() - INTERVAL 7 DAY) AND CURDATE() ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'pending') {
    $query = "SELECT * FROM appointments WHERE status = 'pending' ORDER BY date_and_time ASC LIMIT 0, 5";
} else if ($selected == 'appointed') {
    $query = "SELECT * FROM appointments WHERE status = 'appointed' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'cancelled') {
    $query = "SELECT * FROM appointments WHERE status = 'cancelled' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'missed') {
    $query = "SELECT * FROM appointments WHERE status = 'missed' ORDER BY date_and_time DESC LIMIT 0, 5";
} else if ($selected == 'all') {
    $query = "SELECT * FROM appointments ORDER BY date_and_time DESC LIMIT 0, 5";
}

$result = mysqli_query($conn, $query);

require 'employee-ajax-table.php';

mysqli_close($conn);
