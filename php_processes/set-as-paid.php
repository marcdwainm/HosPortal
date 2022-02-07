<?php

include 'db_conn.php';

$bill_num = $_POST['billnum'];

date_default_timezone_set('Asia/Manila');
$date_paid = date("Y-m-d H:i:s", time());

$query = "SELECT * FROM bills WHERE bill_num = '$bill_num'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_array($result);
$tied_to_online = $row['tied_to_online_appt'];

if($tied_to_online == '1'){
    $query = "UPDATE appointments SET app_type = 'online', `status` = 'pending' WHERE appointment_num = '$bill_num'";
    mysqli_query($conn, $query);

    $query = "UPDATE bills SET paid = '1', date_of_payment = '$date_paid' WHERE bill_num = '$bill_num'";
    mysqli_query($conn, $query);

    $query = "UPDATE patients_notifications SET notif_type = 'onlinereqanswered' WHERE appointment_num = '$bill_num'";
    mysqli_query($conn, $query);
}
else{
    $query = "UPDATE bills SET paid = '1', date_of_payment = '$date_paid' WHERE bill_num = '$bill_num'";
    mysqli_query($conn, $query);
}

$query = "SELECT * FROM bills ORDER BY UNIX_TIMESTAMP(bill_num) DESC LIMIT 0, 10";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $bill_num = $row['bill_num'];
        $issued_to = $row['issued_to'];
        $issued_by = $row['issued_by'];
        $date_of_payment = $row['date_of_payment'] == null ? "N/A" : date('Y, M d h:i A', strtotime($row['date_of_payment']));
        $date_issued = $row['date_issued'];
        $paid = $row['paid'] == "0" ? "Unsettled" : "Paid";
        //GET DETAILS OF PATIENT AND ISSUER
        $result1 = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$issued_to'");
        $row1 = mysqli_fetch_array($result1);
        $fullname_patient = $row1['first_name'] . " " . substr($row1['middle_name'], 0, 1) . ". " . $row1['last_name'];

        $result2 = mysqli_query($conn, "SELECT * FROM employee_table WHERE employee_id = '$issued_by'");
        $row2 = mysqli_fetch_array($result2);
        $fullname_employee = $row2['first_name'] . " " . substr($row2['middle_name'], 0, 1) . ". " . $row2['last_name'];

        $class = $paid == 'Unsettled' ? "red-text" : "green-text";

        $set_as_paid = $paid == 'Unsettled' ? "<button class = 'set-paid-doctor' value = '$bill_num'><i class='fas fa-check fa-lg'></i></button>" : "";

        echo "
            <div class = 'bill-content-div six-fr'>
                <span>$fullname_patient</span>
                <span>$fullname_employee</span>
                <span>$date_of_payment</span>
                <span>$date_issued</span>
                <span class = '$class'>$paid</span>
                <span>
                    <button class = 'view-bill-doctor' value = '$bill_num'><i class='fas fa-info-circle fa-lg'></i></button>
                    $set_as_paid
                </span>
            </div>
        ";
    }
} else {
    echo "<span class = 'no-appointments'>Bills Empty</span>";
}