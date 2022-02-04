<?php
    include 'db_conn.php';

    session_start();
    $pid = $_SESSION['patientid'];

    $query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);

    if ($row['booking_tries'] >= 4){
        echo "1";
    }
    else{
        echo "0";
    }