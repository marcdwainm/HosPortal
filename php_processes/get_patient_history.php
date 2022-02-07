<?php

include 'db_conn.php';

session_start();
$pid = isset($_POST['pid']) ? $_POST['pid'] : $_SESSION['patientid'];
$history_arr = array();

$query = "SELECT * FROM triage_initial WHERE patient_id = '$pid'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) > 0){
    $row = mysqli_fetch_array($result);
    
    $history_arr = array(
        "fam_history_cb" => $row['fam_history_cb'],
        "fam_history_others" => $row['fam_history_others'],
        "allergies_cb" => $row['allergies_cb'],
        "allergies_others" => $row['allergies_others'],
        "soc_history_cb" => $row['soc_history_cb'],
        "soc_history_others" => $row['soc_history_others'] 
    );
    
    echo json_encode($history_arr);
}
else{
    echo "";
}
