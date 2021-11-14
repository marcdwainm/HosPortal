<?php
session_start();
include 'db_conn.php';

$patientid = $_SESSION['patientid'];
$query = "SELECT * FROM appointments WHERE patient_id = '$patientid'";

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
