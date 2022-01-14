<?php
include 'db_conn.php';

$currentAppt = $_POST['currentAppt'];
$patientid = substr($currentAppt, -4);

$query = "SELECT * FROM soap_notes WHERE patient_id = '$patientid' ORDER BY UNIX_TIMESTAMP(appointment_date_time) DESC";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $date = strtotime($row['appointment_date_time']);

    $date = date("M d, Y / H:i A", $date);
    $other_app_num = $row['soap_id'];

    if ($row['appointment_num'] == $currentAppt) {
        echo "
                <button class = 'open-soap-note-file' value = '$other_app_num'>Current Appointment</button>                                        
            ";
    } else {
        echo "
                <button class = 'open-soap-note-file' value = '$other_app_num'>$date</button>                                      
            ";
    }
}
