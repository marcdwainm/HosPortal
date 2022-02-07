<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Twin Care Portal | Patient List</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position != 'nurse') {
        if ($postition == 'doctor') {
            header("Location: employee-homepage.php");
        } else {
            header("Location: $position-homepage.php");
        }
    }

    include 'extras/nurse-profile.php'
    ?>

    <div class="dim-soap">
        <div class="soap-container">
            <div class="soap-container-header">
                <span>SOAP Note</span>
                <span id='exit-soap'>X</span>
            </div>
            <div class="soap-column-container">
                <div class='soap-subjective-column'>
                    <div>
                        <span>Subjective Complaint</span>
                    </div>
                    <textarea id='soap-column-subjective' style="resize: none;" readonly></textarea>
                </div>
                <div class='soap-objective-column'>
                    <div>
                        <span>Physical Examination</span>
                    </div>
                    <textarea id='soap-column-objective' style="resize: none;" readonly></textarea>
                </div>
                <div class='soap-assessment-column'>
                    <div>
                        <span>Diagnosis</span>
                    </div>
                    <textarea id='soap-column-assessment' style="resize: none;" readonly></textarea>
                </div>
                <div class='soap-plan-column'>
                    <div>
                        <span>Treatment</span>
                    </div>
                    <textarea id='soap-column-plan' style="resize: none;" readonly></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="dim-soap-edit">
        <div class="soap-container">
            <div class="soap-container-header">
                <span>SOAP Note</span>
                <span id='saving'></span>
                <span id='exit-soap-edit'>X</span>
            </div>
            <div class="soap-column-container">
                <div class='soap-subjective-column'>
                    <div>
                        <span>Subjective Complaint</span>
                        <div>
                            <button class='add-bullet-btn'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <textarea id='soap-column-subjective-edit' style="resize: none;" spellcheck='false' placeholder='Type notes here...'></textarea>
                </div>
                <div class='soap-objective-column'>
                    <div>
                        <span>Physical Examination</span>
                        <div>
                            <button class='add-bullet-btn'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <textarea id='soap-column-objective-edit' style="resize: none;" spellcheck='false' placeholder='Type notes here...'></textarea>
                </div>
                <div class='soap-assessment-column'>
                    <div>
                        <span>Diagnosis</span>
                        <div>
                            <div class='icd-10-container'>
                                <button id='insert-icd-10-edit'>ICD-10</button>
                                <div class='icd-10-codes'>
                                    <div class="icd-10-codes-header">
                                        <input type='text' id='icd-10-code-doctor-keyword' placeholder='Enter Code or Diagnosis' autocomplete='off'>
                                        <button id='icd-10-search'>Search</button>
                                    </div>
                                    <div class='icd-10-code-buttons'>
                                        <span class='no-results'>No Results</span>
                                    </div>
                                </div>
                            </div>
                            <button class='add-bullet-btn'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <textarea id='soap-column-assessment-edit' style="resize: none;" spellcheck='false' placeholder='Type notes here...'></textarea>
                </div>
                <div class='soap-plan-column'>
                    <div>
                        <span>Treatment</span>
                        <div>
                            <button class='add-bullet-btn'><i class="fas fa-plus"></i></button>
                        </div>
                    </div>
                    <textarea id='soap-column-plan-edit' style="resize: none;" spellcheck='false' placeholder='Type notes here...'></textarea>
                </div>
            </div>
        </div>
    </div>

    <div class="dim-add-a-patient">
        <div class="add-a-patient-window">
            <div class='add-a-patient-header'>
                <span>Add a Patient</span>
                <span class='add-a-patient-exit'>X</span>
            </div>
            <div class="add-a-patient-body">
                <input id='p-fname' type='text' placeholder='Enter First Name'>
                <input id='p-mname' type='text' placeholder='Enter Middle Name'>
                <input id='p-lname' type='text' placeholder='Enter Last Name'>
                <input id='p-contact' type='text' placeholder='Enter Contact Number'>
                <select id='p-gender'>
                    <option selected disabled>Select Gender</option>
                    <option value='Male'>Male</option>
                    <option value='Female'>Female</option>
                </select>
                <input id='p-birthdate' type='date'>
                <input id='p-address' type='text' placeholder='Enter Address'>
                <button id='add-patient'>Add Patient</button>
            </div>
        </div>
    </div>

    <!--UPLOAD OTHER DOCUMENTS-->
    <div class='dim-doc-upload'>
        <div class="document-upload-container-2">
            <div class='document-upload-exit'>
                <span>Upload a Document</span>
                <span class='exit-upload-other-doc'>X</span>
            </div>
            <form class='upload-document-content' method='GET'>
                <span id='patient-error'>Note: This patient is not portal-registered</span>
                <input type="File" name="file" id='file-other-doc' accept='application/pdf, image/png, image/jpeg, image/jpg'>
                <span class='patient-error2'>File is too big! Maximum size: 5MB</span>
                <div class='document-upload-btns-2'>
                    <button type='button' class='upload-other-doc' value="" disabled>Upload File</button>
                </div>
            </form>
        </div>
    </div>

    <div class='background-container'>

        <div class='employee-contents margin-top'>
            <!--PATIENTS' INFORMATION-->
            <h1>Patients</h1>
            <div class='e-contents-header'>
                <h2>Portal-Registered</h2>
                <input type='text' id='search-patient' placeholder='Enter Patient Name'>
            </div>

            <div class='e-contents-table-patients'>
                <div class='e-contents-header-table-patients'>
                    <span>Patient Name</span>
                    <span>Sex</span>
                    <span>Age</span>
                    <span>Contact No.</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class='patient-tbl'>
                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM user_table LIMIT 0, 10";

                    $result = mysqli_query($conn, $query);

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
                                                    <button class = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
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

                                $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'prescription'";
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

                                $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'labresult'";
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
                        <div>
                            
                        </div>
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
                    ?>
                </div>
            </div>
            <div class='add-a-patient-div reload-all'>
                <div>
                    <button id='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
                <button class='add-a-patient'>Add a Patient</button>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/patient-list.js'></script>
<script src="js/notification.js"></script>
<script src="js/doctor-documents.js"></script>
</html>