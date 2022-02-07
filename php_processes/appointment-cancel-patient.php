<?php
session_start();
include 'db_conn.php';

$app_num = $_POST['app_num'];
$patientid = $_SESSION['patientid'];

date_default_timezone_set('Asia/Manila');
$date_uploaded = date('Y-m-d H:i:s', time());
$date = date('Ymdhis', time());

$query = "SELECT * FROM user_table WHERE patient_id = '$patientid'";
$result = mysqli_query($conn, $query);
$has_appointment = "";
while ($row = mysqli_fetch_array($result)) {
    $has_appointment = $row['has_appointment'];
}

//KAILANGAN 1 YUNG HAS_APPOINTMENT PARA GAWIN LAHAT TO 
if ($has_appointment == '0') {
} else {
    $query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        if ($row['appointed_by'] != 'user') {
            $emp_id = $row['appointed_by'];
            $date_and_time = $row['date_and_time'];
            $fullname = $_SESSION['fullname'];
            $query_nest = "INSERT INTO notifications (emp_id, notif_type, appointment_num, patient_fullname, date_time, date_booked)
            VALUES('$emp_id', 'cancellation', '$app_num', '$fullname', '$date_and_time', '$date')";
            $result_nest = mysqli_query($conn, $query_nest);
        }
    }

    $query = "UPDATE appointments SET status = 'cancelled' WHERE appointment_num = '$app_num'";
    mysqli_query($conn, $query);

    $query = "DELETE FROM bills WHERE bill_num = '$app_num'";
    mysqli_query($conn, $query);

    //IF APPOINTMENT IS ONLINE REQ, THE ONLINE REQ NOTIF MUST BE DELETED
    mysqli_query($conn, "DELETE FROM patients_notifications WHERE appointment_num = '$app_num' AND notif_type = 'onlinereq'");
}


//UPDATE TABLE
$query = "SELECT * FROM appointments WHERE SUBSTRING(appointment_num, -4) = '$patientid' AND (status != 'pending' AND status != 'onlinereq' AND status != 'ongoing') ORDER BY UNIX_TIMESTAMP(date_and_time) DESC LIMIT 0, 5";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $query = "UPDATE user_table SET has_appointment = 0 WHERE patient_id = '$patientid'";
    mysqli_query($conn, $query);

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
                        <button type = 'button' class = 'view-triage-patient' value = '$appointmentnum'>View Triage</button>
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
