<?php
include '../db_conn.php';
if (isset($_POST["id"])) {
    $start = $_POST['start'];
    $end = $_POST['end'];
    $id = $_POST['id'];

    $query = "UPDATE appointments SET date_and_time='$start', date_and_time_finish='$end' WHERE appointment_num = '$id'";
    mysqli_query($conn, $query);
}
