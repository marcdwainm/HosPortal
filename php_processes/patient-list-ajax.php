<?php
include 'db_conn.php';

$keyword = $_POST['keyword'];

$like = "    WHERE first_name LIKE '%$keyword%' OR
middle_name LIKE '%$keyword%' OR
last_name LIKE '%$keyword%' OR
contact_num LIKE '%$keyword%' OR
sex LIKE '%$keyword%' OR
address LIKE '%$keyword%'
";
$query = $keyword == "" ? "SELECT * FROM user_table LIMIT 0, 10" : "SELECT * FROM user_table $like LIMIT 0, 10";

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
                <div class = 'progress-and-del'>
                    <button class = 'patient-progress-btn' value = '$pid'>Records</button>";
                echo "</div>
            </div>
            <div class = 'hidden-patient-progress-div'>
                <i class='fas fa-angle-double-down center-i'></i>";



                // GENERATE REPORT START //

                echo"
                    <div class = 'generate-report'>
                        <h2>Appointment Reports</h2>
                        <div>
                            <select>";
                                
                            $query_report = "SELECT * FROM soap_notes WHERE patient_id = '$pid' ORDER BY appointment_date_time DESC";
                            $result_report = mysqli_query($conn, $query_report);
                            $disabled = "";

                            if(mysqli_num_rows($result_report) > 0){
                                while($row = mysqli_fetch_array($result_report)){
                                    $date_display = date("M d, Y / D", strtotime($row['appointment_date_time']));
                                    $soap_id = $row['soap_id'];
                                    echo "
                                        <option value = '$soap_id'>$date_display</option>
                                    ";
                                }
                            }
                            else{
                                $disabled = 'disabled';
                            }

                            echo "</select>
                            <button class = 'generate-report-btn' $disabled>Generate</button>
                        </div>
                    </div>
                    <hr />
                    ";

                // GENERATE REPORT END //



                    echo"
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
                                                    <button class = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                                                    <button class = 'edit-soap' value = '$soap_id' ><i class='far fa-edit fa-lg'></i></button>
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
                            </div>
                            ";

         //--------------------Prescriptions--------------------
                    

         echo "
         <div class = 'patient-progress-header'>
             <h2>Prescriptions</h2>
         </div>

         <div class = 'soap-table'>
             <div class = 'other-docs-table-header'>
                 <span>Date Uploaded</span>
                 <span></span>
             </div>
             <div class = 'other-docs-table-contents' id = 'patient-presc-$pid'>";

             $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'prescription' ORDER BY date_uploaded DESC";
             $result2 = mysqli_query($conn, $query2);

             if(mysqli_num_rows($result2) > 0){
                 while($row = mysqli_fetch_array($result2)){
                     $docnum = $row['doc_num'];
                     $date_uploaded = strtotime($row['date_uploaded']);
                     $date_uploaded = date("M d, Y / h:i A", $date_uploaded);

                     echo "  
                         <div class = 'other-docs-table-content'>
                             <span>$date_uploaded</span>
                             <div>
                                 <div class = 'soap-btns'>
                                     <button class = 'view-doc' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                                     <button class = 'download-pdf' value = '$docnum'><i class='fas fa-download'></i></button>
                                     <button class = 'archive-presc' value = '$docnum'><i class='fas fa-archive fa-lg'></i></button>
                                 </div>
                             </div>
                         </div>
                     ";
                 }
             }
             else{
                 echo '<span class = "no-appointments font-size-bigger">No records yet</span>';
             }

             echo" 
             </div>
         </div>
     ";
     //------------------End of Prescriptions------------------


     //--------------------Lab results--------------------
         echo "
         <div class = 'patient-progress-header'>
             <h2>Lab Results</h2>
         </div>

         <div class = 'soap-table'>
             <div class = 'other-docs-table-header'>
                 <span>Date Uploaded</span>
                 <span></span>
             </div>
             <div class = 'other-docs-table-contents' id = 'patient-lab-$pid'>";

             $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'labresult' ORDER BY date_uploaded DESC";
             $result2 = mysqli_query($conn, $query2);

             if(mysqli_num_rows($result2) > 0){
                 while($row = mysqli_fetch_array($result2)){
                     $docnum = $row['doc_num'];
                     $date_uploaded = strtotime($row['date_uploaded']);
                     $date_uploaded = date("M d, Y / h:i A", $date_uploaded);

                     echo "  
                         <div class = 'other-docs-table-content'>
                             <span>$date_uploaded</span>
                             <div>
                                 <div class = 'soap-btns'>
                                     <button class = 'view-doc' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                                     <button class = 'download-pdf' value = '$docnum'><i class='fas fa-download'></i></button>
                                     <button class = 'archive-lab' value = '$docnum'><i class='fas fa-archive fa-lg'></i></button>
                                 </div>
                             </div>
                         </div>
                     ";
                 }
             }
             else{
                 echo '<span class = "no-appointments font-size-bigger">No records yet</span>';
             }

             echo" 
             </div>
         </div>
     ";
     //------------------End of Lab results------------------



        //---------------------------NEW TABLEEEE------------------------------------------------------
        echo "<div class = 'patient-progress-header'>
        <h2>Other Documents</h2>
    </div>
    <div class = 'soap-table'>
        <div class = 'other-docs-table-header'>
            <span>Date Uploaded</span>
            <span></span>
        </div>
        <div class = 'other-docs-table-contents' id = 'patient-other-$pid'>";

        $query_nest = "SELECT * FROM other_documents WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";
        $result2 = mysqli_query($conn, $query_nest);

        if (mysqli_num_rows($result2) > 0) {
            while ($row = mysqli_fetch_array($result2)) {
                $date_uploaded = strtotime($row['date_uploaded']);
                $date_uploaded = date("M d, Y / h:i A", $date_uploaded);
                $doc_num = $row['docnum'];

                echo "
                    <div class = 'other-docs-table-content'>
                        <span>$date_uploaded</span>
                        <div>
                            <div class = 'soap-btns'>
                                <button class = 'view-other' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                <button class = 'download-other' value = '$doc_num'><i class='fas fa-download'></i></button>
                                <button class = 'archive-other' value = '$doc_num'><i class='fas fa-archive fa-lg'></i></button>
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
        //END OF SECOND TABLE


        echo "
            </div>
            </div>";
    }
} else {
    echo "<span class = 'no-appointments'>No Patients Found</span>";
}

mysqli_close($conn);
