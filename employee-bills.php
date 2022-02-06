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
    <title>Twin Care Portal | Bills</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
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

    <div class="dim-bill">
        <div class="bill-container">

        </div>
    </div>

    <div class="dim-4">
        <div class='issue-bill-container'>
            <div class='book-header-exit'>
                <span>Bill Issuing</span>
                <span class='exit' id='exit-issue'>X</span>
            </div>
            <div class="issue-details" style = "width: 100%;">
                <div class = "issue-bill-autocomplete" style = 'width: 100%;'>
                    <input type='text' class = 'bill-manual-input' placeholder="Enter Patient's Name" autocomplete="off">
                    <div class = "bill-manual-autocomplete">
                        <!--Ajax results-->
                    </div>
                </div>
            </div>
            <div class="issue-fillup">
                <button id='add-bill-item'>Add Item</button>
                <div class="fill-up-fields">
                    <div class='fill-up-field'>
                        <input type='text' placeholder='Enter a description' class='fill-up-item'>
                        <input type='number' placeholder='Enter price' class='fill-up-price'>
                        <button class='delete-item'><i class="fas fa-trash"></i></button>
                    </div>
                </div>
            </div>
            <div class="total-and-button">
                <div>
                    <b>Total Due:</b>
                    <span>PHP</span>
                    <span class='total-price'>0.00</span>
                </div>
                <button type='button' id='issue-bill-manual' disabled>Issue Bill</button>
            </div>
        </div>
    </div>

    <div class='background-container'>

        <div class='employee-contents margin-top'>
            <!--PATIENTS' INFORMATION-->
            <h1>Bills</h1>
            <div class='e-contents-header'>
                <h2>History</h2>
                <button class = 'issue-a-bill'>Issue a Bill</button>
            </div>

            <div class='e-contents-table-patients'>
                <div class='e-contents-header-table-patients six-fr'>
                    <span>Issued To</span>
                    <span>Issued By</span>
                    <span>Date of Payment</span>
                    <span>Date Issued</span>
                    <span>Status</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class='bills-tbl'>
                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM bills ORDER BY UNIX_TIMESTAMP(bill_num) DESC LIMIT 0, 10";
                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $bill_num = $row['bill_num'];
                            $issued_to = $row['issued_to'];
                            $issued_by = $row['issued_by'];
                            $date_of_payment = $row['date_of_payment'] == null ? "N/A" : date('Y, M d h:i A', strtotime($row['date_of_payment']));
                            $date_issued = $row['date_issued'];
                            $paid = $row['paid'] == "0" ? "Unsettled" : "Paid";
                            //GET DETAILS OF PATIENT AND ISSUER
                            $result1 = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$issued_to'");
                            $row1 = mysqli_fetch_array($result1);
                            $fullname_patient = $row1['first_name'] . " " . substr($row1['middle_name'], 0, 1) . ". " . $row1['last_name'];

                            $result2 = mysqli_query($conn, "SELECT * FROM employee_table WHERE employee_id = '$issued_by'");
                            $row2 = mysqli_fetch_array($result2);
                            $fullname_employee = $row2['first_name'] . " " . substr($row2['middle_name'], 0, 1) . ". " . $row2['last_name'];

                            $class = $paid == 'Unsettled' ? "red-text" : "green-text";

                            $set_as_paid = $paid == 'Unsettled' ? "<button class = 'set-paid-doctor' value = '$bill_num'><i class='fas fa-check fa-lg'></i></button>" : "";

                            echo "
                                <div class = 'bill-content-div six-fr'>
                                    <span>$fullname_patient</span>
                                    <span>$fullname_employee</span>
                                    <span>$date_of_payment</span>
                                    <span>$date_issued</span>
                                    <span class = '$class'>$paid</span>
                                    <span>
                                        <button class = 'view-bill-doctor' value = '$bill_num'><i class='fas fa-info-circle fa-lg'></i></button>
                                        $set_as_paid
                                    </span>
                                </div>
                            ";
                        }
                    } else {
                        echo "<span class = 'no-appointments'>Bills Empty</span>";
                    }
                    ?>
                </div>
            </div>
            <div class='add-a-patient-div reload-all'>
                <div>
                    <button id='prev-bill'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next-bill'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/patient-list.js'></script>
<script src="js/notification-doc.js"></script>
<script src='js/billing.js'></script>
<script src="js/doctor-documents.js"></script>

</html>