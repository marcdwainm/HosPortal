<?php

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $appointmentnum = $row['appointment_num'];
        $fullname = ucwords($row['patient_fullname']);
        $datetime = Date("D, M d, h:i A", strtotime($row['date_and_time']));
        $status = ucfirst($row['status']);
        $app_type = ucfirst($row['app_type']);
        $status_div;
        $finish_btn = "<button type = 'button' id = 'finish-appointment' value = '$appointmentnum'>Finish Appointment</button>";
        $cancel_btn = "<button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>";
        $missed_btn = "<button type = 'button' class = 'missed' value = '$appointmentnum'>Mark as missed</button>";
        $call_btn = "<button type = 'button' class = 'consult-online' value = '$appointmentnum'>Set-up Chat Room</button>";
        $details_btn = "<button type = 'button' class = 'view-triage' value = '$appointmentnum'>View Triage</button>";
        $online_btn = "<button type = 'button' class = 'request-online' value = '$appointmentnum'>Request Online</button>";

        if ($status == 'Pending' && $app_type == 'Online') {
            $status_div = "<span class = 'orange-text'>$status</span>";
            $online_btn = "";
        } else if ($status == 'Pending' && $app_type == 'F2f') {
            $status_div = "<span class = 'orange-text'>$status</span>";
            $call_btn = "";
        } else if ($status == 'Appointed') {
            $status_div = "<span class = 'green-text'>$status</span>";
            $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
        } else if ($status == 'Missed' || $status == 'Cancelled') {
            $status_div = "<span class = 'red-text'>$status</span>";
            $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
        } else if ($status == 'Ongoing') {
            $status_div = "<span class = 'orange-text'>$status</span>";
            $call_btn = "<button type = 'button' value = '$appointmentnum' class = 'consult-online'>Rejoin Meeting</button>";
            $online_btn = "";
        } else if ($status == 'Onlinereq') {
            $status_div = "<span class = 'orange-text'>F2f (Online Requested)</span>";
            $online_btn = "";
        }

        $dt = new DateTime($datetime);

        $date = $dt->format('F j, Y l');
        $time = $dt->format('h:i A');

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if ($_SESSION['position'] == 'nurse') {
            $call_btn = "";
        }

        echo "
            <div class='e-contents'>
                    <span>$appointmentnum</span>
                    <span>$fullname</span>
                    <span>$datetime</span>
                    $status_div
                    <span>$app_type</span>
                    <span class = 'e-num'>
                        0998390813
                        <button><i class='fas fa-ellipsis-v'></i></button>
                    </span>
                    <form class = 'dropdown' target = 'dummyframe'>
                        $details_btn
                        $online_btn   
                        $finish_btn
                        $missed_btn
                        $cancel_btn
                        $call_btn
                    </form>
                </div>
            ";
    }
} else {
    echo "<span class = 'no-appointments'>No Appointments Found</span>";
}
