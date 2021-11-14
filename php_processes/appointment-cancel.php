<?php
include 'db_conn.php';

$app_num = $_POST['app_num'];

//NOTIIFY USER ABOUT CANCELLATION
$date_notified = date('Y-m-d h:i:s', time());
$userid = substr($app_num, -4);

$query = "INSERT INTO patients_notifications (`notif_type`, `patient_id`, `appointment_num`, `date_notified`) VALUES('cancellation', '$userid','$app_num', '$date_notified')";
$result = mysqli_query($conn, $query);

$query = "UPDATE user_table SET num_of_appt = 0 WHERE patient_id = '$userid'";
$result = mysqli_query($conn, $query);

$query = "DELETE FROM appointments WHERE appointment_num = '$app_num'";
$result = mysqli_query($conn, $query);

$query = "SELECT * FROM appointments";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $appointmentnum = $row['appointment_num'];
        $fullname = $row['patient_fullname'];
        $datetime = $row['date_and_time'];
        $dt = new DateTime($datetime);

        $date = $dt->format('F j, Y l');
        $time = $dt->format('h:i A');

        echo "
                <div class='e-contents'>
                    <span>$appointmentnum</span>
                    <span>$fullname</span>
                    <span>$datetime</span>
                    <span class = 'e-num'>
                        0998390813
                        <button><i class='fas fa-ellipsis-v'></i></button>
                    </span>
                    <form class = 'dropdown' target = 'dummyframe'>
                        <button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>
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
