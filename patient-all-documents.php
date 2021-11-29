<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/profile.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <?php

    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    include 'extras/navbar.php';
    include 'extras/profile.php';

    ?>

    <div class='background-container'></div>

    <?php //include 'extras/patient-notifications.php'; 
    ?>

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

                        $query = "SELECT * FROM documents WHERE sent_to = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";

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
                                    <button class='details-btn' value = '$docnum'>View</button>
                                    <button class='download' value = '$docnum'><i class='fas fa-download'></i></button>
                                </div>
                            </div>
                        ";
                            }
                        }

                        ?>

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