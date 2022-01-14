<?php
include 'db_conn.php';

$offset = 0;

if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
}

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$patientid = $_SESSION['patientid'];
$query = "SELECT * FROM appointments WHERE patient_id = '$patientid' AND (status != 'pending' AND status != 'onlinereq' AND status != 'ongoing') ORDER BY UNIX_TIMESTAMP(date_and_time) DESC LIMIT $offset, 5";

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
                    <button type = 'button' class = 'view-triage-patient' value = '$appointmentnum'>View Triage</button>
                    $cancel_btn
                    $join_btn
                    $online_req
                </form>
            </div>
        ";
    }
} else {
    echo "<span class = 'no-appointments'>You currently have no appointments</span>";
}
