<?php
include 'db_conn.php';

$keyword = $_POST['keyword'];

$query = "SELECT * FROM user_table WHERE
     first_name LIKE '%" . $keyword . "%' OR
     middle_name LIKE '%" . $keyword . "%' OR
     last_name LIKE '%" . $keyword . "%' OR
     contact_num LIKE '%" . $keyword . "%'
     ";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $pid = $row['patient_id'];
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
        $sex = strtoupper(substr($row['sex'], 0, 1));
        $contact = $row['contact_num'];

        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;


        echo "
                        <div class='e-contents-patients'>
                            <div class = 'patient-details'>
                                <span class = 'patient-fullname'>$fullname</span>
                                <span>$sex</span>
                                <span>$age</span>
                                <span>$contact</span>
                                <div>
                                    <button class = 'patient-progress-btn' value = '$pid'>Records</button>
                                </div>
                            </div>
                            <div class = 'hidden-patient-progress-div'>
                                <i class='fas fa-angle-double-down center-i'></i>
                                <div class = 'patient-progress-header'>
                                    <h2>SOAP Notes</h2>
                                    <div>
                                        <button class = 'create-new-soap'>Create New SOAP <i class='far fa-plus-square'></i></button>
                                    </div>
                                </div>
                                <div class = 'soap-table'>
                                    <div class = 'soap-table-header'>
                                        <span>Date Created</span>
                                        <span>Appointment Date</span>
                                        <span></span>
                                    </div>
                                    <div class = 'soap-table-contents' id = 'patient$pid'>";

        //SOAP TABLE FOR THE SELECTED PATIENT
        $query_nest = "SELECT * FROM soap_notes WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_created) DESC";
        $result2 = mysqli_query($conn, $query_nest);

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_array($result2)) {
                $date_time = $row['appointment_date_time'];
                $date_time = strtotime($date_time);
                $date_time = date("M d, Y / h:i A", $date_time);
                $date_created = $row['date_created'];
                $date_created = strtotime($date_created);
                $date_created = date("M d, Y / h:i A", $date_created);
                $app_num = $row['appointment_num'];
                $soap_id = $row['soap_id'];

                echo "
                                        <div class = 'soap-table-content'>
                                            <span>$date_created</span>
                                            <span>$date_time</span>
                                            <div>
                                                <div class = 'soap-btns'>
                                                    <button id = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                                                    <button id = 'edit-soap' value = '$soap_id' ><i class='far fa-edit fa-lg'></i></button>
                                                    <button class = 'archive-soap' value = '$soap_id'><i class='fas fa-archive fa-lg'></i></button>
                                                </div>
                                            </div>
                                        </div>
                                        ";
            }
        } else {
            echo '
                                <span class = "no-appointments font-size-bigger">No records yet</span>
                            ';
        }

        echo "
                                </div>
                            </div>";


        //---------------------------NEW TABLEEEE------------------------------------------------------
        echo "<div class = 'patient-progress-header'>
                        <h2>Other Documents</h2>
                        <div>
                            <button class = 'upload-new-other-doc'>Upload New Document <i class='fas fa-upload'></i></button>
                        </div>
                    </div>
                    <div class = 'soap-table'>
                        <div class = 'other-docs-table-header'>
                            <span>Date Uploaded</span>
                            <span></span>
                        </div>
                        <div class = 'soap-table-contents' id = 'patient$pid'>";
        //FIX THISSSSSSSSSSSS 
        //MAKE NEW TABLE IN PHPMYADMIN FOR OTHER DOCUMENTS
        //SOAP TABLE FOR THE SELECTED PATIENT
        $query_nest = "SELECT * FROM soap_notes WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_created) DESC";
        $result2 = mysqli_query($conn, $query_nest);

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_array($result2)) {
                $date_time = $row['appointment_date_time'];
                $date_time = strtotime($date_time);
                $date_time = date("M d, Y / h:i A", $date_time);
                $date_created = $row['date_created'];
                $date_created = strtotime($date_created);
                $date_created = date("M d, Y / h:i A", $date_created);
                $app_num = $row['appointment_num'];
                $soap_id = $row['soap_id'];

                echo "
                            <div class = 'soap-table-content'>
                                <span>$date_created</span>
                                <span>$date_time</span>
                                <div>
                                    <div class = 'soap-btns'>
                                        <button id = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                                        <button class = 'archive-soap' value = '$soap_id'><i class='fas fa-archive fa-lg'></i></button>
                                    </div>
                                </div>
                            </div>
                            ";
            }
        } else {
            echo '
                    <span class = "no-appointments font-size-bigger">No records yet</span>
                ';
        }

        echo "
                    </div>
                </div>";
    }
} else {
    echo "<span class = 'no-appointments'>No Patients Found</span>";
}

mysqli_close($conn);
