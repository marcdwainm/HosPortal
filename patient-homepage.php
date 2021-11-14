<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/profile.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <title>Document</title>
</head>

<body>
    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>

    <?php

    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    include 'extras/navbar.php';
    include 'extras/profile.php';

    ?>

    <div class='background-container'></div>

    <div class="notification-area">
        <div class="notification-box">
            <div class='notif-header'>
                <span>Notifications</span>
            </div>
            <div class="notif-contents">
                <!--10 People Have booked for appointments-->
                <?php
                $patient_id = $_SESSION['patientid'];
                include 'php_processes/db_conn.php';

                $query = "SELECT * FROM patients_notifications WHERE patient_id = '$patient_id' AND date_notified > DATE_SUB(NOW(), INTERVAL '1' DAY)";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) == 0) {
                    echo "<span class = 'no-new'>No New Notifications!</span>";
                } else {
                    while ($row = mysqli_fetch_array($result)) {
                        $appnum = $row['appointment_num'];
                        echo "
                            <div class='notif-content cancel-notif-type'>
                                <div class='notif-img'></div>
                                <span>
                                    The doctor has cancelled your appointment (Appointment #$appnum)
                                </span>
                                <div class='seen'>
                                    <div class='seen-circle'></div>
                                </div> 
                            </div>
                        ";
                    }
                }

                ?>


                <!-- Doctor has cancelled your appointment, put appointment in appointment history -->
                <!-- The doctor has sent you a prescription -->
                <!-- Your lab result is out -->
                <!-- Recent Appointment not paid. Settle now?-->


                <div class='notif-see-all'>
                    <span>See All</span>
                </div>
            </div>
        </div>
        <div class="notification-num"><span>0</span></div>
        <div class="notification-btn">
            <i class="far fa-bell"></i>
        </div>
    </div>

    <div class='dim'>
        <div class='book-container'>
            <div class='book-header-exit'>
                <span>Book an Appointment</span>
                <span class='exit'>X</span>
            </div>
            <form class='book-content' target='dummyframe'>
                <span class='content-head'>Set Date/Time</span>
                <input type='datetime-local' class='date-time-input' id='appointment-date-time' name='appointment-date-time' placeholder="Select Appointment Date and Time...">
                <input type='text' class='description' name='description' placeholder="Provide a brief description of your concern (Optional)">
                <div class="availability">
                    <span class='available-head'>Availablity:</span>
                    <span class='time-date'>Monday - Saturday</span>
                    <span class='time-date'>9:00AM - 4:00PM</span>
                </div>
                <button type='submit' id='book'>Book Appointment</button>
            </form>
        </div>
    </div>

    <div class='contents'>
        <div class='appointment-container'>
            <h3 class='header-table'>
                <span>Upcoming Appointments</span>
            </h3>

            <div class='table'>
                <!--APPOINTMENT TABLE HEADER-->
                <div class='table-header'>
                    <span>Appointment No.</span>
                    <span>Date</span>
                    <span>Time</span>
                </div>

                <div id='appt-table'>
                    <!--APPOINTMENT TABLE CONTENTS-->
                    <?php
                    include 'php_processes/db_conn.php';

                    $patientid = $_SESSION['patientid'];
                    $query = "SELECT * FROM appointments WHERE patient_id = '$patientid'";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $appointmentnum = $row['appointment_num'];
                            $datetime = $row['date_and_time'];
                            $dt = new DateTime($datetime);

                            $date = $dt->format('F j, Y l');
                            $time = $dt->format('h:i A');

                            echo "
                                <div class='table-content'>
                                    <span class='appointment-num'>$appointmentnum</span>
                                    <span>$date</span>
                                    <span class = 'e-num'>
                                        $time
                                        <button><i class='fas fa-ellipsis-v'></i></button>
                                    </span>
                                    <form class = 'dropdown'>
                                        <button type = 'button' class = 'cancel-appointment-patient' value = '$appointmentnum'>Cancel Appointment</button>
                                    </form>
                                </div>
                            ";
                        }
                    } else {
                        echo '
                            <span class = "no-appointments">You currently have no appointments</span>
                        ';
                    }
                    ?>
                </div>
            </div>

            <div class='reload-book-btns'>
                <?php
                $query = "SELECT num_of_appt FROM user_table WHERE patient_id = '$patientid'";

                $result = mysqli_query($conn, $query);

                $row = mysqli_fetch_array($result);

                if ($row[0] == 1) {
                    echo "
                    <button id = 'reload'>Reload Table</button>
                    <button id='book-appointment' disabled>Book Appointments</button>
                ";
                } else {
                    echo "
                    <button id = 'reload'>Reload Table</button>
                    <button id='book-appointment'>Book Appointments</button>
                ";
                }
                ?>
            </div>
        </div>

        <div class='transaction-container'>
            <h3 class='header-table'>Transaction History</h3>

            <div class='table'>
                <!--TRANSACTION TABLE HEADER-->
                <div class='transaction-header'>
                    <span>Transaction ID</span>
                    <span>Date</span>
                    <span>Amount</span>
                    <span>Status</span>
                    <span></span>
                </div>

                <!--TRANSACTION TABLE CONTENT-->
                <div class='sample-transaction'>
                    <span class='transaction-num'>02138123782173</span>
                    <span>12/02/2021</span>
                    <span>P3000</span>
                    <span>Paid</span>
                    <span>
                        <a href='patient-transaction.php' class='transaction-btn'>
                            <button>Details</button>
                        </a>
                        <span>
                </div>

            </div>
        </div>
    </div>

</body>
<script src='js/navbar.js'></script>
<script src='js/book-appointment.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src='js/notification.js'></script>
<script src='js/appointment-manager.js'></script>

</html>