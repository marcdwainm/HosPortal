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
// 30 MINUTES AFTER THE TIME
$datetime_finish = strtotime($datetime);
$datetime_finish = date('Y-m-d H:i:s', strtotime('+30 minutes', $datetime_finish));

$email = $_SESSION['email'];
$position = $_SESSION['position'];

// Triage Details
$chief_complaint = ucfirst($_POST['chief-complaint']);
$height = $_POST['height'];
$weight = $_POST['weight'];
$blood_pressure = $_POST['blood-pressure'];
$temperature = $_POST['temperature'];
$past_surgery = ucfirst($_POST['past-surgery']);
$family_history = $_POST['family-history'];
$allergies = $_POST['allergies'];
$social_history = $_POST['social-history'];
$current_medications = ucfirst($_POST['current-medications']);
$travel_history = ucfirst($_POST['travel-history']);

$birthdate = $_SESSION['birthdate'];
$from = new DateTime($birthdate);
$to = new DateTime('today');
$age = $from->diff($to)->y;

//INSERT APPOINTMENT TO DATABSE
$query = "INSERT INTO `appointments`(`appointment_num`, `patient_id`, `patient_fullname`, `date_and_time`, `date_and_time_finish`, `contact`, `app_type`, `appointed_by`, `status`) 
            VALUES ('$appointmentnum', '$patientid', '$fullname', '$datetime', '$datetime_finish', '$contact', 'f2f', 'user', 'pending')";
$result = mysqli_query($conn, $query);


//INSERT DETAILS TO TRIAGE
$query = "INSERT INTO `triage`(`appointment_num`, `chief_complaint`, `age`, `height`, `weight`, `blood_pressure`, `temperature`, `past_surgery`, `family_history`, `allergies`, `social_history`, `current_medications`, `travel_history`) 
            VALUES('$appointmentnum', '$chief_complaint', '$age', \"$height\", '$weight', '$blood_pressure', '$temperature', '$past_surgery', '$family_history', '$allergies', '$social_history', '$current_medications', '$travel_history')";
$result = mysqli_query($conn, $query);

//UPDATE HAS_APPOINTMENT TO 1
$query = "UPDATE user_table SET has_appointment = 1 WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);

//ADD +1 APPOINTMENT NUMBER TRIES
$query = "UPDATE user_table SET booking_tries = booking_tries + 1 WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);

//INSERT APPOINTMENT TO NOTIFICATIONS
$query = "INSERT INTO `notifications`(`emp_id`, `notif_type`, `appointment_num`, `patient_fullname`, `date_time`, `date_booked`) 
VALUES ('all', 'appointment', '$appointmentnum', '$fullname', '$datetime', '$date_booked')";
$result = mysqli_query($conn, $query);


//AJAX

$query = "SELECT * FROM appointments WHERE patient_id = '$patientid' AND (status = 'pending' OR status = 'onlinereq' OR status = 'ongoing') ORDER BY UNIX_TIMESTAMP(date_and_time) DESC LIMIT 0, 5";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $appointmentnum = $row['appointment_num'];
        $datetime = $row['date_and_time'];
        $status = ucwords($row['status']);
        $dt = new DateTime($datetime);
        $type = ucwords($row['app_type']);
        $text_color = "orange-text";

        $cancel_btn = "<button type = 'button' class = 'cancel-appointment-patient' value = '$appointmentnum'>Cancel Appointment</button>";
        $join_btn = "<button type = 'button' id = 'join-chatroom' value = '$appointmentnum'>Join Chatroom</button>";
        $online_req = "<div class = 'online-req'>
                                <span>The doctor requested for the appointment to be conducted online. Do you accept? By accepting, you also agree that you have to pay online via PayPal. Decline to keep the Face-to-Face setup. 
                                </span>
                                <div>
                                    <button type = 'button' class = 'accept-online' value = '$appointmentnum'>Accept</button>
                                    <button type = 'button' class = 'decline-online' value = '$appointmentnum'>Decline</button>
                                </div>
                            </div>";

        $date = $dt->format('F j, Y, l');
        $time = $dt->format('h:i A');

        switch ($status) {
            case 'Ongoing':
                $cancel_btn = "";
                $online_req = "";
                break;
            case 'Pending':
                $join_btn = "";
                $online_req = "";
                break;
            case 'Onlinereq':
                $join_btn = "";
                $status = "Pending (Online Request)";
                break;
            case 'Cancelled':
                $join_btn = "";
                $cancel_btn = "";
                $online_req = "";
                $text_color = 'red-text';
                break;
            case 'Missed':
                $join_btn = "";
                $cancel_btn = "";
                $online_req = "";
                $text_color = 'red-text';
                break;
            case 'Appointed':
                $join_btn = "";
                $cancel_btn = "";
                $online_req = "";
                $text_color = 'green-text';
                break;
        }

        echo "
                <div class='table-content four-fr'>
                    <span class='appointment-num'>$appointmentnum</span>
                    <span class = '$text_color'>$status</span>
                    <span>$date / $time</span>
                    <span class = 'e-num'>
                        $type
                        <button><i class='fas fa-ellipsis-v'></i></button>
                    </span>
                    <form class = 'dropdown'>
                        $cancel_btn
                        $join_btn
                        $online_req
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
