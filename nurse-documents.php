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
    <link rel='stylesheet' type='text/css' href='css/employee-contents.css'>

    <title>Twin Care Portal | Documents</title>
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

    <div class='background-container'>

        <div class='add-document-overlay'></div>
        <div class="document-upload-container">
            <div class='document-upload-exit'>
                <span>Upload a Document</span>
                <span class='exit'>X</span>
            </div>
            <form class='upload-document-content' method='GET'>
                <div class="p-name-documents">
                    <div class='portal-registered-checkbox'>
                        <div class='checkbox'>
                            <label for="portal-registered">Portal Registered Patient</label>
                            <input type="checkbox" name="portal-registered" id='portal-registered' value="p-registered">
                        </div>
                    </div>
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
                <input type="File" name="file" id='file' accept='application/pdf, image/png, image/jpeg, image/jpg'>
                <span class='patient-error2'>File is too big! Maximum size: 5MB</span>
                <div class='document-upload-btns'>
                    <button id='upload-from-device' type='button' value='0000' disabled>Upload from Device</button>
                    <button id='file-to-database' type='button' value='0000'>Upload File</button>
                    <span>OR</span>
                    <button id='generate-document' name='submit' type='submit' value='0000' disabled>Generate Document</button>
                </div>
            </form>
        </div>

        <div class='employee-contents margin-top' id='con'>
            <!--WORK DOCUMENTS-->
            <div class='e-contents-header-app'>
                <h1>Documents</h1>
                <div>
                    <h2>Latest Prescriptions</h2>
                    <div class='header-btn-btns'>
                        <button id='see-all-documents-nurse'>See All Documents</button>
                    </div>
                </div>

            </div>

            <div class='e-contents-table'>
                <div class='e-contents-header-table-docs'>
                    <span>Patient</span>
                    <span>Date Uploaded</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class="presc-tbl">

                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM documents WHERE doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'empty'>You don't have any Documents</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $doc_type = ucwords($row['doc_type']);
                            $pname = $row['patient_name'];
                            $date_uploaded = strtotime($row['date_uploaded']);
                            $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
                            $doc_num = $row['doc_num'];

                            echo "
                                <div class='e-contents three-fr'>
                                    <span>$pname</span>
                                    <span>$date_up_formatted</span>
                                    <div class = 'test'>
                                        <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                        <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                                        <button class = 'archive-prescription' value = '$doc_num'><i class='fas fa-archive'></i></button>
                                    </div>
                                </div>
                            ";
                        }
                    }

                    ?>

                </div>
            </div>

            <div class="reload-all">
                <button type='button' class='reload-tbl-doc' value='pres'>Reload Table</button>
            </div>

            <!--APPOINTMENT HISTORY-->
            <div class='e-contents-header'>
                <h2>Latest Lab Tests</h2>
            </div>

            <div class='e-contents-table'>
                <div class='e-contents-header-table-docs'>
                    <span>Patient</span>
                    <span>Date Uploaded</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->

                <div class="lab-tbl">

                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'empty'>You don't have any Documents</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $doc_type = ucwords($row['doc_type']);
                            $pname = $row['patient_name'];
                            $date_uploaded = strtotime($row['date_uploaded']);
                            $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
                            $doc_num = $row['doc_num'];

                            echo "
                    <div class='e-contents three-fr'>
                        <span>$pname</span>
                        <span>$date_up_formatted</span>
                        <div class = 'test'>
                            <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                            <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                            <button class = 'archive-labresult' value = '$doc_num'><i class='fas fa-archive'></i></button>
                        </div>
                    </div>
                ";
                        }
                    }

                    ?>

                </div>
            </div>

            <div class="reload-all">
                <button type='button' class='reload-tbl-doc' value='lab'>Reload Table</button>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/doctor-documents.js'></script>
<script src="js/notification.js"></script>t

</html>