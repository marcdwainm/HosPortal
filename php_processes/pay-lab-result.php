<?php

include 'db_conn.php';
$bill_num = $_POST['billnum'];
date_default_timezone_set('Asia/Manila');
$date = date("Y-m-d H:i:s", time());

//CHECK FIRST IF BILL IS TIED TO ONLINE APPT
$query = "SELECT * FROM bills WHERE bill_num = '$bill_num'";
$result = mysqli_query($conn, $query);
$row = mysqli_fetch_array($result);

if($row['tied_to_online_appt'] == '1'){
    mysqli_query($conn, "UPDATE appointments SET `app_type` = 'online', `status` = 'pending' WHERE appointment_num = '$bill_num'");
    mysqli_query($conn, "UPDATE bills SET paid = '1', date_of_payment = '$date' WHERE bill_num = '$bill_num'");

    //Notif must be answered
    mysqli_query($conn, "UPDATE patients_notifications SET notif_type = 'onlinereqanswered' WHERE appointment_num = '$bill_num'");
    
    //SEND notif to doctor that the appt was accepted
    $pid = substr($bill_num, -4);
    $result2 = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$pid'");
    $row2 = mysqli_fetch_array($result2);
    $pname = $row2['first_name'] . " " . substr($row2['middle_name'], 0, 1) . ". " . $row2['last_name'];

    //Get doctor's emp id
    $result3 = mysqli_query($conn, "SELECT * FROM appointments WHERE appointment_num = '$bill_num'");
    $row3 = mysqli_fetch_array($result3);
    $emp_id = $row3['appointed_by'];
    $appt_date = $row3['date_and_time'];

    $query = "INSERT INTO notifications (emp_id, notif_type, appointment_num, patient_fullname, date_time, seen) 
    VALUES ('$emp_id', 'onlineaccept', '$bill_num', '$pname', '$appt_date', '0')";
    $result = mysqli_query($conn, $query);
}
else{
    mysqli_query($conn, "UPDATE bills SET paid = '1', date_of_payment = '$date' WHERE bill_num = '$bill_num'");

    //GET CORESSPONGING DOC
    $query = "SELECT * FROM bills WHERE bill_num = '$bill_num'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $docnum = $row['corresponding_doc'];

    if($docnum == ''){
        mysqli_query($conn, "UPDATE documents SET paid = '1' WHERE doc_num = '$bill_num'");
        mysqli_query($conn, "UPDATE documents_patient_copy SET paid = '1' WHERE doc_num = '$bill_num'");
    }
    else{
        mysqli_query($conn, "UPDATE documents SET paid = '1' WHERE doc_num = '$docnum'");
        mysqli_query($conn, "UPDATE documents_patient_copy SET paid = '1' WHERE doc_num = '$docnum'");
    }

}



include 'patient-transaction-table.php';
