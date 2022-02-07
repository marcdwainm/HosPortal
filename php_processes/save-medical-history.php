<?php

include 'db_conn.php';

session_start();
$pid = $_SESSION['patientid'];

$familyHistoryCb = str_replace("'", "", $_POST['familyHistoryCb']);
$allergiesCb = str_replace("'", "", $_POST['allergiesCb']);
$socHistoryCb = str_replace("'", "", $_POST['socHistoryCb']);
$familyHistoryOthers = str_replace("'", "", $_POST['familyHistoryOthers']);
$allergiesOthers = str_replace("'", "", $_POST['allergiesOthers']);
$socialHistoryOthers = str_replace("'", "", $_POST['socialHistoryOthers']);

// IF PATIENT FOUND, UPDATE
// IF PATIENT NOT FOUND, INSERT
$query = "SELECT * FROM triage_initial WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $query = "UPDATE triage_initial SET 
    fam_history_cb = '$familyHistoryCb',
    allergies_cb = '$allergiesCb',
    soc_history_cb = '$socHistoryCb',
    fam_history_others = '$familyHistoryOthers',
    allergies_others = '$allergiesOthers',
    soc_history_others = '$socialHistoryOthers'
    WHERE patient_id = '$pid'";

    mysqli_query($conn, $query);
    echo mysqli_error($conn);
}
else{
    $query = "INSERT INTO triage_initial 
    (
        patient_id,
        fam_history_cb,
        allergies_cb,
        soc_history_cb,
        fam_history_others,
        allergies_others,
        soc_history_others
    )
    VALUES ('$pid', '$familyHistoryCb', '$allergiesCb', '$socHistoryCb', '$familyHistoryOthers', '$allergiesOthers', '$socialHistoryOthers')";

    mysqli_query($conn, $query);
    echo mysqli_error($conn);
    echo $query;
}
