<?php
include '../php_processes/db_conn.php';
session_start();
$emp_id = $_SESSION['emp_id'];
$notifs_to_del = array();
$iteration = 0;


$query = "SELECT * FROM notifications WHERE emp_id = '$emp_id' OR emp_id = 'all' ORDER BY notif_id DESC";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<span class = 'no-new'>No New Notifications!</span>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $appointment_num = $row['appointment_num'];
        $seen = $row['seen'];
        $seen_text = '';
        $date_time = Date("l, M d / H:i A", strtotime($row['date_time']));

        if ($seen == 0) {
            $seen_text = "<div class='seen'>
            <div class='seen-circle'></div>
            </div>";
        } else {
            $seen_text = "<div class='seen'></div>";
        }

        // BOOKED APPOINTMENTS NOTIFICATIONS
        if ($row['notif_type'] == 'appointment') {
            $fullname = $row['patient_fullname'];
            echo "
                <button class='notif-content book-notif-type notif-doc' value = '$appointment_num'>
                    <div class='notif-img'>
                        <i class='far fa-calendar-check fa-lg'></i>
                    </div>
                    <span>
                        $fullname has booked for appointment, dated: $date_time
                    </span>
                    $seen_text
                </button>
            ";
        }


        //IF ONLINE REQUEST IS ACCEPTED
        else if ($row['notif_type'] == 'onlineaccept') {
            $fullname = $row['patient_fullname'];
            echo "
                <button class='notif-content online-req-notif-type notif-doc' value = '$appointment_num'>
                    <div class='notif-img'>
                        <i class='fas fa-user-check fa-lg'></i>
                    </div>
                    <span>
                        $fullname has accepted your request for online appointment, dated: $date_time
                    </span>
                    $seen_text
                </button>
            ";
        }

        //IF ONLINE REQUEST IS DECLINED
        else if ($row['notif_type'] == 'onlinedecline') {
            $fullname = $row['patient_fullname'];
            echo "
                <button class='notif-content online-req-notif-type notif-doc' value = '$appointment_num'>
                    <div class='notif-img'>
                        <i class='fas fa-user-times fa-lg'></i>
                    </div>
                    <span>
                        $fullname has declined your request for online appointment, dated: $date_time
                    </span>
                    $seen_text
                </button>
            ";
        }

        //APPOINTMENT CANCELLATIONS NOTIFICATIONS
        else if ($row['notif_type'] == 'cancellation') {
            $fullname = $row['patient_fullname'];
            echo "
                <button class='notif-content cancel-notif-type notif-doc' value = '$appointment_num'>
                    <div class='notif-img'>
                        <i class='fas fa-ban fa-lg'></i>
                    </div>
                    <span>
                        $fullname has cancelled an appointment dated $date_time
                    </span>
                    $seen_text
                </button>
            ";
        }

        //IF PAID BILL
        else if ($row['notif_type'] == 'payment') {
            $fullname = $row['patient_fullname'];
            echo "
                <button class='notif-content book-notif-type notif-doc'>
                    <div class='notif-img'>
                    <i class='fab fa-paypal fa-lg'></i>
                    </div>
                    <span>
                        $fullname has settled a bill. Kindly check your bills table.
                    </span>
                    $seen_text
                </button>
            ";
        }

        $iteration += 1;

        if ($iteration >= 31) {
            array_push($notifs_to_del, $row['notif_id']);
        }
    }

    if (!empty($notifs_to_del)) {
        foreach ($notifs_to_del as $id) {
            $del = "DELETE FROM notifications WHERE notif_id = '$id'";
            mysqli_query($conn, $del);
        }
    }
}
