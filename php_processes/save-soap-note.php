<?php

include 'db_conn.php';

$app_num = isset($_POST['appNum']) ? $_POST['appNum'] : "";
$subjective_note = $_POST['subjectiveNote'];
$objective_note = $_POST['objectiveNote'];
$assessment_note = $_POST['assessmentNote'];
$plan_note = $_POST['planNote'];
$soap_all = "$subjective_note ### $objective_note ### $assessment_note ### $plan_note";

if (isset($_POST['soapId'])) {
    $soap_id = $_POST['soapId'];
    $query = "UPDATE soap_notes SET soap_note = '$soap_all' WHERE soap_id = '$soap_id'";
    mysqli_query($conn, $query);
} else {
    //GET APPOINTMENT DETAILS

    $query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);
    $appointment_start = "";
    $patient_id = "";

    while ($row = mysqli_fetch_array($result)) {
        $appointment_start = $row['date_and_time'];
        $patient_id = $row['patient_id'];
    }

    //FIRST, CHECK IF THE CURRENT APPOINTMENT ALREADY HAS SOAP NOTE
    $query = "SELECT * FROM soap_notes WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        // IF SOAP NOTE IS FOUND, UPDATE
        $query = "UPDATE soap_notes SET soap_note = '$soap_all' WHERE appointment_num = '$app_num'";
        mysqli_query($conn, $query);
    } else {
        //IF SOAP NOTE NOT FOUND, INSERT
        $query = "INSERT INTO soap_notes (appointment_num, appointment_date_time, patient_id, soap_note) 
        VALUES ('$app_num', '$appointment_start', '$patient_id', '$soap_all')";

        mysqli_query($conn, $query);
    }
}
