<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='fullcalendar/main.css' rel='stylesheet' />
    <script src='fullcalendar/main.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>

    <!--JQUERY UI-->
    <link rel='stylesheet' href='jqueryui/jquery-ui.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.structure.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.theme.css'>
    <script src='jqueryui/jquery-ui.js' type='text/javascript'></script>

    <title>Twin Care Portal | Archive</title>
    <link rel="icon" href="img/logo.png">
    <?php

    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position !== 'doctor') {
        header("Location: $position-homepage.php");
    }

    include 'extras/profile.php';
    include 'php_processes/db_conn.php';

    ?>
</head>

<body>
    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <div class='background-container'>

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

        <div class="doc-notifs">
            <div class="notification-area">
                <div class="notification-box">
                    <div class='notif-header'>
                        <span>Notifications</span>
                    </div>
                    <div class="notif-contents-doc">
                        <!--AJAX NOTIFS HERE-->
                    </div>
                </div>
                <div class="notification-num"><span></span></div>
                <div class="notification-btn">
                    <i class="far fa-bell"></i>
                </div>
            </div>
        </div>


        <!--APPOINTMENT UPCOMING-->
        <div class='e-contents-header-archive'>
            <div class="archive-header">
                <h1>Archive</h1>
            </div>
            <div class='archive-tables'>
                <!--PRESCRIPTIONS-->
                <div class='archive-category'>
                    <div class='archive-category-header' id='archive-search-prescription'>
                        <span>Presciptions</span>
                        <input type='text' class='archive-search' placeholder='Search Archived Prescriptions'>
                    </div>

                    <div class='archive-table-container'>
                        <div class='archive-table'>
                            <div class="archive-table-header four-fr">
                                <span>Patient</span>
                                <span>Date Uploaded</span>
                                <span>Date Archived</span>
                                <span></span>
                            </div>
                            <div class="archive-table-contents" id='archive-prescription-table'>
                                <?php
                                include 'php_processes/db_conn.php';

                                $query = "SELECT * FROM archive_documents WHERE doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) <= 0) {
                                    echo "<span class = 'no-appointments'>Archive Empty</span>";
                                } else {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $patient_name = $row['patient_name'];
                                        $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_uploaded']));
                                        $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
                                        $doc_num = $row['doc_num'];

                                        echo "
                                        <div class='archive-table-content four-fr'>
                                            <span>$patient_name</span>
                                            <span>$date_uploaded</span>
                                            <span>$date_archived</span>
                                            <div>
                                                <button class = 'view-prescription' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                                <button class = 'restore-prescription' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
                                            </div>
                                        </div>
                                        ";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="archive-pagination">
                        <div id='pagination-prescription'>
                            <button class='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                            <span class='page-num page-num-presc'>1</span>
                            <span class='offset offset-presc'>0</span>
                            <button class='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                        </div>
                    </div>
                </div>





                <!--LABRESULTS-->
                <div class='archive-category'>
                    <div class='archive-category-header' id='archive-search-labresult'>
                        <span>Lab Results</span>
                        <input type='text' class='archive-search' placeholder='Search Archived Prescriptions'>
                    </div>

                    <div class='archive-table-container'>
                        <div class='archive-table'>
                            <div class="archive-table-header four-fr">
                                <span>Patient</span>
                                <span>Date Uploaded</span>
                                <span>Date Archived</span>
                                <span></span>
                            </div>
                            <div class="archive-table-contents" id='archive-labresult-table'>
                                <?php
                                include 'php_processes/db_conn.php';

                                $query = "SELECT * FROM archive_documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) <= 0) {
                                    echo "<span class = 'no-appointments'>Archive Empty</span>";
                                } else {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $patient_name = $row['patient_name'];
                                        $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_uploaded']));
                                        $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
                                        $doc_num = $row['doc_num'];

                                        echo "
                                        <div class='archive-table-content four-fr'>
                                            <span>$patient_name</span>
                                            <span>$date_uploaded</span>
                                            <span>$date_archived</span>
                                            <div>
                                                <button class = 'view-labresult' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                                <button class = 'restore-labresult' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
                                            </div>
                                        </div>
                                        ";
                                    }
                                }
                                ?>
                                <!--STOPPED HERE
                                AUTOCOMPLETE SEARCH IN ARCHIVE
                                PAGINATION IN ARCHIVE
                                VIEW DOCUMENT IN ARCHIVE
                            -->
                            </div>
                        </div>
                    </div>

                    <div class="archive-pagination">
                        <div id='pagination-labresult'>
                            <button class='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                            <span class='page-num page-num-labresult'>1</span>
                            <span class='offset offset-labresult'>0</span>
                            <button class='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                        </div>
                    </div>
                </div>



                <!--SOAP-->
                <div class='archive-category'>
                    <div class='archive-category-header' id='archive-search-soap'>
                        <span>SOAP Notes</span>
                        <input type='text' class='archive-search' placeholder='Search Archived Prescriptions'>
                    </div>

                    <div class='archive-table-container'>
                        <div class='archive-table'>
                            <div class="archive-table-header four-fr">
                                <span>Patient</span>
                                <span>Date Created</span>
                                <span>Date Archived</span>
                                <span></span>
                            </div>
                            <div class="archive-table-contents" id='archive-soap-table'>
                                <?php
                                include 'php_processes/db_conn.php';

                                $query = "SELECT * FROM archive_soap ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) <= 0) {
                                    echo "<span class = 'no-appointments'>Archive Empty</span>";
                                } else {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $patient_name = $row['patient_fullname'];
                                        $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_created']));
                                        $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
                                        $soap_id = $row['soap_id'];

                                        echo "
                                        <div class='archive-table-content four-fr'>
                                            <span>$patient_name</span>
                                            <span>$date_uploaded</span>
                                            <span>$date_archived</span>
                                            <div>
                                                <button class = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                                                <button class = 'restore-soap' value = '$soap_id'><i class='fas fa-trash-restore fa-lg'></i></button>
                                            </div>
                                        </div>
                                        ";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="archive-pagination">
                        <div id='pagination-soap'>
                            <button class='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                            <span class='page-num page-num-soap'>1</span>
                            <span class='offset offset-soap'>0</span>
                            <button class='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                        </div>
                    </div>
                </div>




                <!--OTHER DOCUMENTS-->
                <div class='archive-category'>
                    <div class='archive-category-header' id='archive-search-other'>
                        <span>Other Documents</span>
                        <input type='text' class='archive-search' placeholder='Search Archived Documents'>
                    </div>

                    <div class='archive-table-container'>
                        <div class='archive-table'>
                            <div class="archive-table-header four-fr">
                                <span>Patient</span>
                                <span>Date Uploaded</span>
                                <span>Date Archived</span>
                                <span></span>
                            </div>
                            <div class="archive-table-contents" id='archive-other-table'>
                                <?php
                                include 'php_processes/db_conn.php';

                                $query = "SELECT * FROM archive_other ORDER BY UNIX_TIMESTAMP(date_archived) DESC LIMIT 0, 5";
                                $result = mysqli_query($conn, $query);

                                if (mysqli_num_rows($result) <= 0) {
                                    echo "<span class = 'no-appointments'>Archive Empty</span>";
                                } else {
                                    while ($row = mysqli_fetch_array($result)) {
                                        $patient_name = $row['patient_name'];
                                        $date_uploaded = date("M d, Y / h:i A", strtotime($row['date_uploaded']));
                                        $date_archived = date("M d, Y / h:i A", strtotime($row['date_archived']));
                                        $doc_num = $row['doc_num'];

                                        echo "
                                        <div class='archive-table-content four-fr'>
                                            <span>$patient_name</span>
                                            <span>$date_uploaded</span>
                                            <span>$date_archived</span>
                                            <div>
                                                <button class = 'view-other' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                                <button class = 'restore-other' value = '$doc_num'><i class='fas fa-trash-restore fa-lg'></i></button>
                                            </div>
                                        </div>
                                        ";
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>

                    <div class="archive-pagination">
                        <div id='pagination-other'>
                            <button class='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                            <span class='page-num page-num-other'>1</span>
                            <span class='offset offset-other'>0</span>
                            <button class='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="js/archive.js"></script>
<script src='js/navbar.js'></script>
<script src='js/notification-doc.js'></script>
<script src='js/appointment-manager.js'></script>
<script src='js/book-appointment.js'></script>
<script src='js/billing.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</html>