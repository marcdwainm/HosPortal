<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php include 'extras/employee-navbar.php' ?>
    <?php include 'extras/profile.php' ?>

    <div class='background-container'></div>

    <div class='add-document-overlay'></div>
    <div class="document-upload-container">
        <div class='document-upload-exit'>
            <span>Upload a Document</span>
            <span class='exit'>X</span>
        </div>
        <form class='upload-document-content' method='GET'>
            <div class="p-name-documents">
                <input type='text' id='patient-search' placeholder="Enter Patient's Name" autocomplete="off">
                <div class="p-name-documents-autocomplete">
                    <!--Dynamic Results Here-->
                </div>
            </div>
            <span id='patient-error'>Note: This patient is not portal-registered</span>
            <select id='document-type' name='document-type'>
                <option value='default' disabled selected>Choose Document Type</option>
                <option value='prescription'>Prescription</option>
                <option value='labresult'>Lab Result</option>
            </select>
            <input type="File" name="file" id='file' accept='application/msword, text/plain, application/pdf'>
            <div class='document-upload-btns'>
                <button id='upload-from-device' type='button' disabled>Upload from Device</button>
                <button id='file-to-database' type='button'>Upload File</button>
                <span>OR</span>
                <button id='generate-document' name='submit' type='submit' disabled>Generate Document</button>
            </div>
        </form>
    </div>

    <div class='employee-contents' id='con'>
        <!--WORK DOCUMENTS-->
        <div class='e-contents-header-app'>
            <div class='document-header-btn'>
                <h1>DOCUMENTS</h1>
                <button id='upload-document'>Upload a Document <i class="fas fa-upload"></i></button>
            </div>
            <h2>Prescriptions</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Filename</span>
                <span>Patient</span>
                <span>Date Uploaded</span>
            </div>

            <!-- TABLE CONTENTS OF PRESCRIPTION DOCUMENTS -->
            <?php
                include 'php_processes/db_conn.php';

                //USED INNER JOIN TO QUERY MULTIPLE TABLES WITH SAME KEYS, TO ACCESS PATIENT NAME VIA CODE
                $query = "SELECT documents.sent_to, documents.docs_date_time, documents.doc_type, user_table.first_name, user_table.last_name
                FROM documents 
                INNER JOIN user_table ON documents.sent_to = user_table.patient_id
                ORDER BY docs_date_time DESC
                ";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $pdf_file = 'fakeprescription.pdf';
                        $sent_to = $row['sent_to'];
                        $patient_fName = $row['first_name'];
                        $patient_lName = $row['last_name'];
                        $doc_datetime = $row['docs_date_time'];
                        $doc_type = $row['doc_type'];

                        if ($doc_type === 'prescription'){
                            echo "
                                <div class='e-contents'>
                                    <span>$pdf_file</span>
                                    <span>$patient_fName $patient_lName</span>
                                    <span>$doc_datetime</span>
                                    <div>
                                        <a class='view'><button>View</button></a>
                                        <a><button><i class='fas fa-download'></i></button></a>
                                    </div>
                                </div>
                            ";
                        }
                    }
                } else {
                        echo '
                            <span class = "no-appointments">No Prescriptions Found</span>
                        ';
                }
            ?>
        </div>

        <!--APPOINTMENT HISTORY-->
        <div class='e-contents-header'>
            <h2>Laboratory Tests</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Filename</span>
                <span>Patient</span>
                <span>Date Uploaded</span>
                <span></span>
            </div>

            <!-- TABLE CONTENTS OF LABORATORY TESTS-->

            <?php
                include 'php_processes/db_conn.php'; 

                $query = "SELECT documents.sent_to, documents.docs_date_time, documents.doc_type, user_table.first_name, user_table.last_name
                FROM documents 
                INNER JOIN user_table ON documents.sent_to = user_table.patient_id
                ORDER BY docs_date_time DESC
                ";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $pdf_file = 'fakeprescription.pdf';
                        $sent_to = $row['sent_to'];
                        $patient_fName = $row['first_name'];
                        $patient_lName = $row['last_name'];
                        $doc_datetime = $row['docs_date_time'];
                        $doc_type = $row['doc_type'];

                        if ($doc_type === 'labresult'){
                            echo "
                                <div class='e-contents'>
                                    <span>$pdf_file</span>
                                    <span>$patient_fName $patient_lName</span>
                                    <span>$doc_datetime</span>
                                    <div>
                                        <a class='view'><button>View</button></a>
                                        <a><button><i class='fas fa-download'></i></button></a>
                                    </div>
                                </div>
                            ";
                        }
                    }
                } else {
                        echo '
                            <span class = "no-appointments">No Laboratory Results Found</span>
                        ';
                }
            ?>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/doctor-documents.js'></script>

</html>