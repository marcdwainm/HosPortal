<?php
session_start();
include 'db_conn.php';

//Set timezone then get current date and time
date_default_timezone_set('Asia/Manila');
$date = date('Ymdhis', time());
$date_booked = date('Y-m-d H:i:s', time());

//Logged in Patient ID
$patientid = $_SESSION['patientid'];
$fullname = $_SESSION['fullname'];
$contact = $_SESSION['contact'];

// Appointment num = datetime user registered appointment + patientid
$appointmentnum = $date . $patientid;

// Formatted as 2021-11-17 09:00:00
$datetime = $_POST['appointment-date-time'];

$desc = $_POST['description'];
$email = $_SESSION['email'];
$position = $_SESSION['position'];

if($desc == ''){
    $desc = 'N/A';
}

//INSERT APPOINTMENT TO DATABSE
$query = "INSERT INTO `appointments`(`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `description`, `contact`, `app_type`, `appointed_by`, `status`) 
            VALUES ('$appointmentnum', '$patientid', '$fullname', '$datetime', '$desc', '$contact', 'f2f', 'user', 'pending')";
$result = mysqli_query($conn, $query);


//INSERT APPOINTMENT TO USER_TABLE
$query = "UPDATE user_table SET num_of_appt = num_of_appt + 1 WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);


//INSERT APPOINTMENT TO NOTIFICATIONS
$query = "INSERT INTO `notifications`(`notif_type`, `appointment_num`, `patient_fullname`, `date_time`, `date_booked`) 
VALUES ('appointment', '$appointmentnum', '$fullname', '$datetime', '$date_booked')";
$result = mysqli_query($conn, $query);


//AJAX

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
