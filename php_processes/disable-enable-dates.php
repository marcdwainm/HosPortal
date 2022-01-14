<?php

include 'db_conn.php';
if ($_POST['type'] == 'enable') {
    $disabled_dates = $_POST['disabled_dates'];
    $dates = explode(", ", $disabled_dates);
    $start_date = $dates[0];
    $date = new DateTime("$dates[1]");
    $end_date = $date->format("Y-m-d");

    $dateArr = date_range($start_date, $end_date);

    foreach ($dateArr as $date_val) {
        $query_nest = "SELECT * FROM disabled_dates WHERE `date` = '$date_val'";
        $result = mysqli_query($conn, $query_nest);
        if (mysqli_num_rows($result) > 0) {
            $query = "DELETE FROM disabled_dates WHERE `date` = '$date_val'";
            mysqli_query($conn, $query);
        } else {
        }
    }
} else if ($_POST['type'] == 'disable') {
    $disabled_dates = $_POST['disabled_dates'];
    $dates = explode(", ", $disabled_dates);
    $start_date = $dates[0];
    $date = new DateTime("$dates[1]");
    $end_date = $date->format("Y-m-d");

    $dateArr = date_range($start_date, $end_date);

    foreach ($dateArr as $date_val) {
        $query_nest = "SELECT * FROM disabled_dates WHERE `date` = '$date_val'";
        $result = mysqli_query($conn, $query_nest);
        if (mysqli_num_rows($result) > 0) {
            //IF NAKADISABLE NA YUNG SELECTED DATE
        } else {
            $query = "INSERT INTO disabled_dates (`date`) VALUES ('$date_val')";
            mysqli_query($conn, $query);
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
