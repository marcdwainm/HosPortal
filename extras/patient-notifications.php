<div class="notification-area">
    <div class="notification-box">
        <div class='notif-header'>
            <span>Notifications</span>
        </div>
        <div class="notif-contents">
            <!--10 People Have booked for appointments-->
            <?php
            $unseen_count = 0;
            $patient_id = $_SESSION['patientid'];
            include 'php_processes/db_conn.php';

            $notifs_to_del = array();
            $iteration = 0;

            $query = "SELECT * FROM patients_notifications WHERE patient_id = '$patient_id' ORDER BY p_key DESC";

            $result = mysqli_query($conn, $query);

            if (mysqli_num_rows($result) == 0) {
                echo "<span class = 'no-new'>No New Notifications!</span>";
            } else {
                while ($row = mysqli_fetch_array($result)) {
                    $seen = "
                        <div class='seen'>
                            <div class='seen-circle'></div>
                        </div> 
                    ";
                    if ($row['seen'] == '1') {
                        $seen = "<div class='seen'></div> ";
                    }

                    if ($row['seen'] == '0') {
                        $unseen_count += 1;
                    }

                    // APPOINTMENT CANCELLATIONS
                    if ($row['notif_type'] == 'cancellation') {
                        $appnum = $row['appointment_num'];
                        echo "
                            <button class='notif-content cancel-notif-type' value = '$appnum'>
                                <div class='notif-img'></div>
                                <span>
                                    The doctor has cancelled your appointment
                                </span>
                                $seen 
                            </button>
                        ";
                    }

                    // DOCUMENT UPLOADS FROM DOCTOR
                    else if ($row['notif_type'] == 'document') {
                        $docnum = $row['document_num'];
                        echo "
                            <button class='notif-content doc-notif-type' value = '$docnum'>
                                <div class='notif-img'></div>
                                <span>
                                    The doctor has sent you a prescription
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
                                <div class='notif-img'></div>
                                <span>
                                    The doctor has assigned you for appointment at *Date Here*
                                </span>
                                $seen
                            </button>
                        ";
                    }

                    $iteration += 1;

                    if ($iteration >= 16) {
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



            ?>


            <!-- Doctor has cancelled your appointment, put appointment in appointment history -->
            <!-- The doctor has sent you a prescription -->
            <!-- Your lab result is out -->
            <!-- Recent Appointment not paid. Settle now?-->


        </div>
    </div>
    <div class="notification-num"><span><?php echo $unseen_count; ?></span></div>
    <div class="notification-btn">
        <i class="far fa-bell"></i>
    </div>
</div>