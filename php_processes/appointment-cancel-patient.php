<?php
session_start();
include 'db_conn.php';

$app_num = $_POST['app_num'];
$patientid = $_SESSION['patientid'];

//NOTIIFY USER ABOUT CANCELLATION
$query = "DELETE FROM notifications WHERE appointment_num = '$app_num'";
$result = mysqli_query($conn, $query);

$query = "UPDATE user_table SET num_of_appt = num_of_appt - 1 WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);

$query = "DELETE FROM appointments WHERE appointment_num = '$app_num'";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM appointments WHERE SUBSTRING(appointment_num, -4) = '$patientid'";
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
