<?php
include 'db_conn.php';

$data = array();

$show_cancelled = true;
$result2 = mysqli_query($conn, 'SELECT toggle FROM show_cancelled_appointments');
$row2 = mysqli_fetch_array($result2);
$show_cancelled = $row2['toggle'] == '0' ? false : true;

$query = "SELECT * FROM appointments ORDER BY appointment_num";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $app_type = $row['app_type']; //online f2f
    $status = $row['status']; //pending appointed cancelled missed ongoing onlinereq
    $time_start = substr($row['date_and_time'], -8);
    $time_end = substr($row['date_and_time_finish'], -8);

    if ($status == 'pending' || $status == 'ongoing' || $status == 'onlinereq') {
        $data[] = array(
            'id' => $row["appointment_num"],
            'title' => $row["patient_fullname"],
            'start' => $row["date_and_time"],
            'end' => $row["date_and_time_finish"],
            'backgroundColor' => '#FFCC00',
            'textColor' => '#663300',
            'overlap' => true,
            'extendedProps' => [
                'id' => $row["appointment_num"],
                'timeStart' => $time_start,
                'timeEnd' => $time_end,
                'patientName' => $row['patient_fullname'],
                'contact' => $row['contact'],
                'appType' => $row['app_type'],
                'status' => $status,
            ]
        );
    } else if ($status == 'appointed') {
        $data[] = array(
            'id' => $row["appointment_num"],
            'title' => $row["patient_fullname"],
            'start' => $row["date_and_time"],
            'end' => $row["date_and_time_finish"],
            'backgroundColor' => '#33CC33',
            'textColor' => 'white',
            'editable' => false,
            'overlap' => true,
            'extendedProps' => [
                'id' => $row["appointment_num"],
                'timeStart' => $time_start,
                'timeEnd' => $time_end,
                'patientName' => $row['patient_fullname'],
                'contact' => $row['contact'],
                'appType' => $row['app_type'],
                'status' => $status
            ]
        );
    } else if ($status == 'missed') {
        $data[] = array(
            'id' => $row["appointment_num"],
            'title' => $row["patient_fullname"],
            'start' => $row["date_and_time"],
            'end' => $row["date_and_time_finish"],
            'backgroundColor' => '#FF0000',
            'textColor' => '#FFFFFF',
            'overlap' => true,
            'editable' => false,
            'extendedProps' => [
                'id' => $row["appointment_num"],
                'timeStart' => $time_start,
                'timeEnd' => $time_end,
                'patientName' => $row['patient_fullname'],
                'contact' => $row['contact'],
                'appType' => $row['app_type'],
                'status' => $status
            ]
        );
    } else if ($status == 'cancelled' && $show_cancelled) {
        $data[] = array(
            'id' => $row["appointment_num"],
            'title' => $row["patient_fullname"],
            'start' => $row["date_and_time"],
            'end' => $row["date_and_time_finish"],
            'backgroundColor' => '#FF0000',
            'textColor' => '#FFFFFF',
            'overlap' => true,
            'editable' => false,
            'extendedProps' => [
                'id' => $row["appointment_num"],
                'timeStart' => $time_start,
                'timeEnd' => $time_end,
                'patientName' => $row['patient_fullname'],
                'contact' => $row['contact'],
                'appType' => $row['app_type'],
                'status' => $status
            ]
        );
    }
}

$query = "SELECT * FROM disabled_dates";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $data[] = array(
            'id' => $row['p_key'],
            'title' => 'Disabled',
            'start' => $row['date'],
            'end' => $row['date'],
            'backgroundColor' => 'rgb(185, 185, 185)',
            'display' => 'background',
            'extendedProps' => [
                'id' => $row['p_key'],
                'title' => 'disabled',
                'status' => 'disabled'
            ]
        );
    }
}

echo json_encode($data);
