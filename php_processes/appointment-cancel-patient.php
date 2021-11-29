<?php
session_start();
include 'db_conn.php';

$app_num = $_POST['app_num'];
$patientid = $_SESSION['patientid'];

date_default_timezone_set('Asia/Manila');
$date_uploaded = date('Y-m-d H:i:s', time());
$date = date('Ymdhis', time());

$query = "UPDATE user_table SET num_of_appt = num_of_appt - 1 WHERE patient_id = '$patientid'";
mysqli_query($conn, $query);

$query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    if ($row['appointed_by'] == 'doctor') {
        $fullname = $_SESSION['fullname'];
        $query_nest = "INSERT INTO notifications (notif_type, appointment_num, patient_fullname, date_booked)
        VALUES('cancellation', '$app_num', '$fullname', '$date')";
        $result_nest = mysqli_query($conn, $query_nest);
    }
}

$query = "DELETE FROM appointments WHERE appointment_num = '$app_num'";
mysqli_query($conn, $query);

$query = "SELECT * FROM appointments WHERE SUBSTRING(appointment_num, -4) = '$patientid' AND status = 'pending'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $appointmentnum = $row['appointment_num'];
        $datetime = $row['date_and_time'];
        $dt = new DateTime($datetime);

        $date = $dt->format('F j, Y l');
        $time = $dt->format('h:i A');

        echo "
            <div class='table-content'>
                <span class='appointment-num'>$appointmentnum</span>
                <span>$date</span>
                <span class = 'e-num'>
                    $time
                    <button><i class='fas fa-ellipsis-v'></i></button>
                </span>
                <form class = 'dropdown'>
                    <button type = 'button' class = 'cancel-appointment-patient' value = '$appointmentnum'>Cancel Appointment</button>
                </form>
            </div>
        ";
    }
} else {
    echo '
        <span class = "no-appointments">You currently have no appointments</span>
    ';
}

mysqli_close($conn);
