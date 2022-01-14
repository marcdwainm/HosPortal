<?php
session_start();
include 'db_conn.php';

if ($_POST['appointment_num'] == "") {
    $disabled_flag = false;
    $enabled_flag = false;

    $start_date = $_POST['startDate'];
    $end_date = $_POST['endDate'];
    $dates = "$start_date, $end_date";


    $disabled_dates = $dates;
    $dates = explode(", ", $disabled_dates);
    $start_date = $dates[0];
    $date = new DateTime("$dates[1]");
    $date->modify("-1 day");
    $end_date = $date->format("Y-m-d");

    $dateArr = date_range($start_date, $end_date);

    foreach ($dateArr as $date_val) {
        $query_nest = "SELECT * FROM disabled_dates WHERE `date` = '$date_val'";
        $result = mysqli_query($conn, $query_nest);

        if (mysqli_num_rows($result) > 0) {
            $enabled_flag = true;
        } else {
            $disabled_flag = true;
        }
    }

    $enable_date = $enabled_flag == "1" ? "<button type = 'button' class = 'enable-dates' value = '$start_date, $end_date'>Enable date/s</button>" : "";
    $disable_date = $disabled_flag == "1" ? "<button type = 'button' class = 'disable-dates' value = '$start_date, $end_date'>Disable date/s</button>" : "";

    echo "
        $enable_date
        $disable_date
    ";
} else {
    $app_num = $_POST['appointment_num'];

    $query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);

    if (0 < mysqli_num_rows($result)) {
        while ($row = mysqli_fetch_array($result)) {
            $appointmentnum = $row['appointment_num'];
            $status = ucwords($row['status']);
            $app_type = ucfirst($row['app_type']);

            $finish_btn = "<button type = 'button' id = 'finish-appointment' value = '$appointmentnum'>Finish Appointment</button>";
            $cancel_btn = "<button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>";
            $missed_btn = "<button type = 'button' class = 'missed' value = '$appointmentnum'>Mark as missed</button>";
            $call_btn = "<button type = 'button' class = 'consult-online-calendar' value = '$appointmentnum' data-value = '$status'>Set-up Chat Room</button>";
            $details_btn = "<button type = 'button' class = 'view-triage' value = '$appointmentnum'>View Triage</button>";
            $online_btn = "<button type = 'button' class = 'request-online' value = '$appointmentnum'>Request Online</button>";

            if ($status == 'Pending' && $app_type == 'Online') {
                $online_btn = "";
            } else if ($status == 'Pending' && $app_type == 'F2f') {
                $call_btn = "";
            } else if ($status == 'Appointed') {
                $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
            } else if ($status == 'Missed' || $status == 'Cancelled') {
                $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
            } else if ($status == 'Ongoing') {
                $call_btn = "<button type = 'button' value = '$appointmentnum' class = 'consult-online-calendar' data-value = '$status'>Rejoin Meeting</button>";
                $online_btn = "";
            } else if ($status == 'Onlinereq') {
                $call_btn = "";
                $online_btn = "";
            }

            if ($_SESSION['position'] == 'nurse') {
                $call_btn = "";
            }

            echo "
            $details_btn
            $online_btn
            $finish_btn
            $missed_btn
            $cancel_btn
            $call_btn
        ";
        }
    }
}


function date_range($first, $last, $step = '+1 day', $output_format = 'Y-m-d')
{
    $dates = array();
    $current = strtotime($first);
    $last = strtotime($last);

    while ($current <= $last) {

        $dates[] = date($output_format, $current);
        $current = strtotime($step, $current);
    }

    return $dates;
}
