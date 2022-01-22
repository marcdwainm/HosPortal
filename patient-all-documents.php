<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
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
            <div class='document-container'>

                <div class='appointment-container'>
                    <h1>Documents</h1>
                    <h3 class='header-table'>
                        <span>All Documents</span>
                        <div class='sortation'>
                            <select id='sortation'>
                                <option value='all'>All</option>
                                <option value='oldest'>Oldest - Latest</option>
                                <option value='prescriptions'>Prescriptions</option>
                                <option value='labresults'>Lab Results</option>
                                <option value='today'>Today</option>
                                <option value='thisweek'>This Week</option>
                                <option value='thismonth'>This Month</option>
                            </select>
                            <button id='sort-table'>Sort</button>
                        </div>
                    </h3>

                    <div class='table'>
                        <!--PRESCRIPTIONS TABLE HEADER-->
                        <div class='table-header three-fr'>
                            <span>Document Type</span>
                            <span>Date Uploaded</span>
                            <span></span>
                        </div>

                        <div id='sorting'>
                            <!--PRESCRIPTIONS TABLE CONTENTS-->

                            <?php
                            include 'php_processes/db_conn.php';
                            $pid = $_SESSION['patientid'];

                            $if_paid = "AND ((doc_type = 'prescription' AND paid = '0') OR (doc_type = 'labresult' AND paid = '1'))";

                            $query = "SELECT * FROM documents WHERE sent_to = '$pid' $if_paid ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 0, 5";

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
                    <div class="reload-all">
                        <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                        <button id='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                        <span id='page-num'>1</span>
                        <span id='offset'>0</span>
                        <button id='next'><i class="fas fa-arrow-right fa-lg"></i></button>
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