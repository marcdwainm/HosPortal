<?php

include 'db_conn.php';

$appnum = $_POST['appointmentNum'];

$query = "SELECT * FROM triage WHERE appointment_num = '$appnum'";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $appointment_num = $row['appointment_num'];
    $chief_complaint = $row['chief_complaint'];
    $height = $row['height'];
    $weight = $row['weight'];
    $blood_pressure = $row['blood_pressure'];
    $temperature = $row['temperature'];
    $past_surgery = $row['past_surgery'];
    $family_history = $row['family_history'];
    $allergies = $row['allergies'];
    $social_history = $row['social_history'];
    $current_medications = $row['current_medications'];
    $travel_history = $row['travel_history'];
    echo "
            <div class='triage-detail'>
                <span>Appointment No.:</span>
                <span>$appointment_num</span>
            </div>
            <div class='triage-detail'>
                <span>Chief Complaint:</span>
                <span>$chief_complaint</span>
            </div>
            <div class='triage-detail'>
                <span>Height:</span>
                <span>$height</span>
            </div>
            <div class='triage-detail'>
                <span>Weight:</span>
                <span>$weight</span>
            </div>
            <div class='triage-detail'>
                <span>Blood Pressure:</span>
                <span>$blood_pressure</span>
            </div>
            <div class='triage-detail'>
                <span>Temperature:</span>
                <span>$temperature</span>
            </div>
            <div class='triage-detail'>
                <span>Past Surgery:</span>
                <span>$past_surgery</span>
            </div>
            <div class='triage-detail'>
                <span>Family History:</span>
                <span>$family_history</span>
            </div>
            <div class='triage-detail'>
                <span>Allergies:</span>
                <span>$allergies</span>
            </div>
            <div class='triage-detail'>
                <span>Social History:</span>
                <span>$social_history</span>
            </div>
            <div class='triage-detail'>
                <span>Current Medications:</span>
                <span>$current_medications</span>
            </div>
            <div class='triage-detail'>
                <span>Travel History:</span>
                <span>$travel_history</span>
            </div>
        ";
}
