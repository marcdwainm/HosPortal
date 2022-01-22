<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    if ($position != 'patient') {
        if ($postition == 'doctor') {
            header("Location: employee-homepage.php");
        } else {
            header("Location: $position-homepage.php");
        }
    }

    include 'extras/patient-profile.php';

    ?>

    <div class='notif-live'>
        <div class="notification-area">
            <div class="notification-box">
                <div class='notif-header'>
                    <span>Notifications</span>
                </div>
                <div class="notif-contents">
                    <!--DYNAMIC NOTIFS-->
                </div>
            </div>
            <div class="notification-num"><span></span></div>
            <div class="notification-btn">
                <i class="far fa-bell"></i>
            </div>
        </div>
    </div>

    <div class='background-container'>

        <div class='contents'>
            <h1>Documents</h1>
            <div class='document-container'>
                <h3 class='header-table'>
                    <span>Ongoing Lab Tests</span>
                    <div class="reload-book-btns">
                        <button id='see-all-documents'>See all Documents</button>
                    </div>
                </h3>

                <div class='table'>
                    <!--LAB RESULT TABLE HEADER-->
                    <div class='labresult-header four-fr'>
                        <span>Collection Date</span>
                        <span>Estimated Date of Result</span>
                        <span>Test</span>
                        <span>Status</span>
                    </div>

                    <?php
                    $pid = $_SESSION['patientid'];
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM lab_drafts WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'no-appointments'>No Lab Tests Ongoing</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $collection_date = Date("M d, Y", strtotime($row['collection_date']));
                            $estimated_result = Date("M d, Y - l", strtotime($row['estimated_result']));
                            $test_type = ucwords($row['test_type']);
                            $status = ucwords($row['status']);

                            if ($status == 'Pending') {
                                $status = "<span class = 'orange-text'>Pending</span>";
                            }

                            echo "
                            <div class='table-content-labresult four-fr'>
                                <span>$collection_date</span>
                                <span>$estimated_result</span>
                                <span>$test_type</span>
                                $status
                            </div>
                        ";
                        }
                    }
                    ?>

                </div>
            </div>





            <div class='appointment-container'>
                <h3 class='header-table'>
                    <span>Prescriptions</span>
                </h3>

                <div class='table'>
                    <!--PRESCRIPTIONS TABLE HEADER-->
                    <div class='table-header three-fr'>
                        <span>Document Type</span>
                        <span>Date Uploaded</span>
                        <span></span>
                    </div>

                    <!--PRESCRIPTIONS TABLE CONTENTS-->
                    <div class="dynamic-tbl-docs">
                        <?php
                        include 'php_processes/db_conn.php';
                        $pid = $_SESSION['patientid'];

                        $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 0, 5";

                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) == 0) {
                            echo "<div class = 'no-appointments'>Documents Empty</div>";
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                $date = strtotime($row['date_uploaded']);
                                $date_formatted = date('M d, Y h:i A', $date);
                                $docnum = $row['doc_num'];
                                $doctype = ucwords($row['doc_type']);

                                echo "
                                <div class='table-content three-fr'>
                                    <span>$doctype</span>
                                    <span>$date_formatted</span>
                                    <div class='table-btns2'>
                                        <button class='details-btn' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                                        <button class='download' value = '$docnum'><i class='fas fa-download'></i></button>
                                    </div>
                                </div>
                            ";
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="reload-all reload-all-docs">
                    <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                    <button id='prev2'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next2'><i class="fas fa-arrow-right fa-lg"></i></button>
                    <!-- <button id='hard-next'>&gt;&gt;</button> -->
                </div>
            </div>


            <div class='appointment-container'>
                <h3 class='header-table'>
                    <span>Lab Results</span>
                </h3>

                <div class='table'>
                    <!--PRESCRIPTIONS TABLE HEADER-->
                    <div class='table-header three-fr'>
                        <span>Document Type</span>
                        <span>Date Uploaded</span>
                        <span></span>
                    </div>

                    <!--PRESCRIPTIONS TABLE CONTENTS-->
                    <div class="dynamic-tbl-docs-lab">
                        <?php
                        include 'php_processes/db_conn.php';
                        $pid = $_SESSION['patientid'];

                        $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND (doc_type = 'labresult' AND paid = '1') ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 0, 5";

                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) == 0) {
                            echo "<div class = 'no-appointments'>Documents Empty</div>";
                        } else {
                            while ($row = mysqli_fetch_array($result)) {
                                $date = strtotime($row['date_uploaded']);
                                $date_formatted = date('M d, Y h:i A', $date);
                                $docnum = $row['doc_num'];

                                echo "
                                <div class='table-content three-fr'>
                                    <span>Lab Result</span>
                                    <span>$date_formatted</span>
                                    <div class='table-btns2'>
                                        <button class='details-btn' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                                        <button class='download' value = '$docnum'><i class='fas fa-download'></i></button>
                                    </div>
                                </div>
                            ";
                            }
                        }
                        ?>
                    </div>
                </div>

                <div class="reload-all reload-all-docs">
                    <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                    <button id='prev3'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num-lab'>1</span>
                    <span id='offset-lab'>0</span>
                    <button id='next3'><i class="fas fa-arrow-right fa-lg"></i></button>
                    <!-- <button id='hard-next'>&gt;&gt;</button> -->
                </div>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/book-appointment.js'></script>
<script src='js/patient-documents.js'></script>
<script src="js/notification.js"></script>

</html>