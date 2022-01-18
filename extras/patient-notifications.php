<?php
session_start();
$patient_id = $_SESSION['patientid'];
include '../php_processes/db_conn.php';
$notifs_to_del = array();
$iteration = 0;
$unseen_cnt = 0;

$query = "SELECT * FROM patients_notifications WHERE patient_id = '$patient_id' ORDER BY p_key DESC";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<span class = 'no-new'>No New Notifications!</span>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $appointment_date_time =  Date("l, M d / H:i A", strtotime($row['appointment_date']));
        $seen = "
                <div class='seen'>
                    <div class='seen-circle'></div>
                </div> 
            ";
        if ($row['seen'] == '1') {
            $seen = "<div class='seen'></div> ";
        }

        // APPOINTMENT CANCELLATIONS
        if ($row['notif_type'] == 'cancellation') {
            $appnum = $row['appointment_num'];
            echo "
                <button class='notif-content cancel-notif-type' value = '$appnum'>
                    <div class='notif-img'>
                        <i class='fas fa-ban fa-lg'></i>
                    </div>
                    <span>
                        The doctor has cancelled your appointment, dated: $appointment_date_time
                    </span>
                    $seen 
                </button>
            ";
        }


        // ONLINE APPOINTMENT REQUESTS FROM DOCTOR
        else if ($row['notif_type'] == 'onlinereq') {
            $appnum = $row['appointment_num'];
            echo "
                <span class='online-req-notif-type' value = '$appnum'>
                    <div class = 'online-req-content'>
                        <div class='notif-img'>
                            <i class='far fa-question-circle fa-lg'></i>
                        </div>
                        <span>
                            The doctor requested an appointment to be conducted online, dated: $appointment_date_time. Do you accept?
                        </span>
                        $seen 
                    </div>
                    <div class = 'online-req-buttons'>  
                        <button type = 'button' class = 'accept-online' value = '$appnum'>Accept</button>
                        <button type = 'button' class = 'decline-online' value = '$appnum'>Decline</button>
                    </div>
                </span>
            ";
        }


        // ANSWERED ONLINE APPOINTMENT REQUESTS FROM DOCTOR
        if ($row['notif_type'] == 'onlinereqanswered') {
            $appnum = $row['appointment_num'];
            echo "
                <button class='notif-content cancel-notif-type' value = '$appnum'>
                    <div class='notif-img'>
                        <i class='fas fa-check fa-lg'></i>
                    </div>
                    <span>
                        You responded to the doctor's request for online appointment, dated: $appointment_date_time.
                    </span>
                    $seen 
                </button>
            ";
        }


        // PRESCRIPTION UPLOADS FROM DOCTOR
        else if ($row['notif_type'] == 'prescription') {
            $docnum = $row['document_num'];
            echo "
                <button class='notif-content doc-notif-type' value = '$docnum'>
                    <div class='notif-img'>
                        <i class='far fa-file-powerpoint fa-lg'></i>
                    </div>
                    <span>
                        The doctor has sent you a prescription
                    </span>
                    $seen
                </button>
            ";
        }

        // LAB RESULTS UPLOADS FROM DOCTOR WITHOUT BILL
        else if ($row['notif_type'] == 'labresult' && $row['with_bill'] == '0') {
            $docnum = $row['document_num'];
            echo "
                <button class='notif-content doc-notif-type' value = '$docnum'>
                    <div class='notif-img'>
                        <i class='fas fa-flask fa-lg'></i>
                    </div>
                    <span>
                        Your laboratory test results are out
                    </span>
                    $seen
                </button>
            ";
        }

        // LAB RESULTS UPLOADS FROM DOCTOR WITH BILL
        else if ($row['notif_type'] == 'labresult' && $row['with_bill'] == '1') {
            $docnum = $row['document_num'];
            echo "
                <button class='notif-content doc-notif-type' value = '$docnum'>
                    <div class='notif-img'>
                        <i class='fas fa-flask fa-lg'></i>
                    </div>
                    <span>
                        Your laboratory test results are out including a bill
                    </span>
                    $seen
                </button>
            ";
        }

        // LAB DRAFTS FROM MEDTECH WITH BILL
        else if ($row['notif_type'] == 'draftbill') {
            echo "
                <button class='notif-content doc-notif-type'>
                    <div class='notif-img'>
                        <i class='fas fa-flask fa-lg'></i>
                    </div>
                    <span>
                        A bill has been issued for your laboratory test
                    </span>
                    $seen
                </button>
            ";
        }

        // APPOINTMENTS FROM DOCTOR
        else if ($row['notif_type'] == 'appointment') {
            $docnum = $row['document_num'];
            echo "
                <button class='notif-content app-notif-type' value = '$docnum'>
                    <div class='notif-img'>
                        <i class='fas fa-stethoscope fa-lg'></i>
                    </div>
                    <span>
                        The doctor has assigned you for appointment at $appointment_date_time
                    </span>
                    $seen
                </button>
            ";
        }

        // CHATROOM SETUP
        else if ($row['notif_type'] == 'chatroom') {
            $docnum = $row['document_num'];
            echo "
                <button class='notif-content chat-notif-type' value = '$docnum'>
                    <div class='notif-img'>
                        <i class='fas fa-comment-medical fa-lg'></i>
                    </div>
                    <span>
                        The doctor has set-up a chatroom for an e-appointment dated $appointment_date_time
                    </span>
                    $seen
                </button>
            ";
        }


        // RESCHEDULED APPOINTMENT
        else if ($row['notif_type'] == 'resched') {
            $appnum = $row['appointment_num'];
            echo "
                <button class='notif-content app-notif-type' value = '$appnum'>
                    <div class='notif-img'>
                        <i class='far fa-clock fa-lg'></i>
                    </div>
                    <span>
                        The doctor rescheduled your appointment to: $appointment_date_time
                    </span>
                    $seen
                </button>
            ";
        }


        $iteration += 1;

        if ($iteration >= 31) {
            array_push($notifs_to_del, $row['p_key']);
        }
    }

    if (!empty($notifs_to_del)) {
        foreach ($notifs_to_del as $id) {
            $del = "DELETE FROM patients_notifications WHERE p_key = '$id'";
            mysqli_query($conn, $del);
        }
    }
}
