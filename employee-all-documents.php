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
    <title>Twin Care Portal | All Documents</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['emp_id'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position !== 'doctor') {
        header("Location: $position-homepage.php");
    }

    include 'extras/profile.php'
    ?>

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

    <!--DUMMY DIV-->
    <div id='file'></div>

    <div class='background-container'>

        <div class='add-document-overlay'></div>
        <div class='employee-contents' id='con'>
            <!--WORK DOCUMENTS-->
            <div class='e-contents-header-app'>
                <div class='document-header-btn margin-top'>
                    <h1>Documents</h1>
                </div>
                <div class='all-docs-header'>
                    <h2>All (Latest - Oldest)</h2>
                    <div class='sortation-docs'>
                        <span id='patient-error' class='margin-right'>Enter a name</span>
                        <input type='text' placeholder='Enter Patient Name' id='patient-name'>
                        <select id='sortation-docs' value='all-desc'>
                            <option value='all-desc'>All (Latest - Oldest)</option>
                            <option value='all-asc'>All (Oldest - Latest)</option>
                            <option value='prescriptions'>Prescriptions</option>
                            <option value='labresults'>Lab Results</option>
                            <option value='today'>Today</option>
                            <option value='thisweek'>This Week</option>
                            <option value='thismonth'>This Month</option>
                            <option value='patientname'>Patient Name</option>
                        </select>
                        <button id='sort-table-docs' value='all-desc'>Sort</button>
                    </div>
                </div>
            </div>

            <div class='e-contents-table'>
                <div class='e-contents-header-table-docs four-fr'>
                    <span>Document Type</span>
                    <span>Patient</span>
                    <span>Date Uploaded</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class="dynamic-tbl">

                    <?php
                    include 'php_processes/db_conn.php';

                    $offset = 0;

                    if (isset($_POST['offset'])) {
                        $offset = $_POST['offset'];
                    }


                    $query = "SELECT * FROM documents ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'empty'>No Documents Found</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $doc_type = ucwords($row['doc_type']);
                            $pname = $row['patient_name'];
                            $date_uploaded = strtotime($row['date_uploaded']);
                            $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
                            $doc_num = $row['doc_num'];
                            $class = "";

                            if ($doc_type == 'Labresult') {
                                $doc_type = 'Lab Result';
                                $class = 'labresult';
                            } else if ($doc_type == 'Prescription') {
                                $class = 'prescription';
                            }

                            echo "
                            <div class='e-contents four-fr'>
                                <span>$doc_type</span>
                                <span>$pname</span>
                                <span>$date_up_formatted</span>
                                <div class = 'test'>
                                    <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                                    <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                                    <button class = 'archive-$class from-all-docs' value = '$doc_num'><i class='fas fa-archive'></i></button>
                                </div>
                            </div>
                        ";
                        }
                    }

                    ?>

                </div>
            </div>

            <div class="reload-all reload-all-docs">
                <div>
                    <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                    <button id='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                    <!-- <button id='hard-next'>&gt;&gt;</button> -->
                </div>
                <!-- <button type='button' class='reload-tbl-doc-2' value='pres'>Reload Table</button> -->
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/doctor-documents.js'></script>
<script src="js/notification-doc.js"></script>

</html>