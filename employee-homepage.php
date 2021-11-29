<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    include 'extras/employee-navbar.php';
    include 'extras/profile.php'
    ?>

    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <div class='background-container'></div>

    <?php include 'extras/doctor-notifications.php'; ?>

    <div class='dim'>
        <div class='book-container'>
            <div class='book-header-exit'>
                <span>Book an Appointment</span>
                <span class='exit'>X</span>
            </div>
            <form class='book-content-doctor' target='dummyframe'>
                <div class='portal-registered-checkbox'>
                    <div class='checkbox2'>
                        <label for="portal-registered">Portal Registered Patient</label>
                        <input type="checkbox" name="portal-registered" id='portal-registered' value="p-registered">
                    </div>
                </div>
                <span class='content-head'>Set Date/Time</span>
                <input type='datetime-local' class='date-time-input' id='appointment-date-time' name='appointment-date-time' placeholder="Select Appointment Date and Time...">
                <div class='search-patient'>
                    <input type='text' id='pname-search' placeholder="Patient's Name" autocomplete="off">
                    <div id='plist-search'>
                        <!--Autocomplete search results here-->
                    </div>
                </div>
                <span id="patient-error" class='p-error'>Patient is not Portal-registered</span>
                <input type='text' id='pcontact' placeholder="Contact Number">
                <input type='text' id='desc' placeholder="Provide a short description (Optional)">
                <select id='appointment-type'>
                    <option id='app-def' value='' disabled selected>Type of Appointment</option>
                    <option value='f2f'>F2F</option>
                    <option value='online'>Online</option>
                </select>
                <div class="availability">
                    <span class='available-head'>Availablity:</span>
                    <span class='time-date'>Monday - Saturday</span>
                    <span class='time-date'>9:00AM - 4:00PM</span>
                </div>
                <button type='submit' id='book-doctor' value='0000'>Book Appointment</button>
            </form>
        </div>
    </div>

    <div class='employee-contents'>
        <!--APPOINTMENT UPCOMING-->
        <div class='e-contents-header-app'>
            <h1>Appointments</h1>
            <h2 class='h2-sortation'>
                <span>Today</span>
                <form class='sortation' method='GET'>
                    <select name='sortation' class='sortation-select'>
                        <option value='' selected disabled>-- Select Option --</option>
                        <option value='today'>Today</option>
                        <option value='upcoming'>Upcoming</option>
                        <option value='recent'>Recent</option>
                        <option value='lastweek'>Last 7 Days</option>
                        <option value='pending'>Pending</option>
                        <option value='appointed'>Appointed</option>
                        <option value='all'>All</option>
                    </select>
                    <button type='button' id='sort-btn' disabled>
                        Sort
                    </button>
                </form>
            </h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Appointment No.</span>
                <span>Patient</span>
                <span>Date/Time</span>
                <span>Status</span>
                <span>Contact No.</span>
            </div>

            <div id='doctor-appt-table'>
                <!-- TABLE CONTENTS -->
                <?php
                include 'php_processes/db_conn.php';

                $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE() ORDER BY date_and_time ASC";

                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) > 0) {
                    while ($row = mysqli_fetch_array($result)) {
                        $appointmentnum = $row['appointment_num'];
                        $fullname = $row['patient_fullname'];
                        $datetime = $row['date_and_time'];
                        $status = ucfirst($row['status']);
                        $status_div;
                        $finish_btn;
                        $cancel_btn = "<button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>";
                        $details_btn = "<button type = 'button' value = '$appointmentnum'>Appointment Details</button>";

                        if ($status == 'Pending') {
                            $status_div = "<span class = 'orange-text'>$status</span>";
                            $finish_btn = "
                                <button type = 'button' id = 'finish-appointment' value = '$appointmentnum'>Finish Appointment</button>
                            ";
                            $details_btn = "";
                        } else if ($status == 'Appointed') {
                            $status_div = "<span class = 'green-text'>$status</span>";
                            $cancel_btn = "";
                            $finish_btn = "";
                        }

                        $dt = new DateTime($datetime);

                        $date = $dt->format('F j, Y l');
                        $time = $dt->format('h:i A');

                        if ($status == 'Cancelled') {
                            echo "
                                <div class='e-contents'>
                                    <span>$appointmentnum</span>
                                    <span>$fullname</span>
                                    <span>$datetime</span>
                                    <span class = 'red-text'>$status</span>
                                    <span class = 'e-num'>
                                        0998390813
                                        <button><i class='fas fa-ellipsis-v'></i></button>
                                    </span>
                                </div>
                                ";
                        } else {
                            echo "
                                <div class='e-contents'>
                                        <span>$appointmentnum</span>
                                        <span>$fullname</span>
                                        <span>$datetime</span>
                                        $status_div
                                        <span class = 'e-num'>
                                            0998390813
                                            <button><i class='fas fa-ellipsis-v'></i></button>
                                        </span>
                                        <form class = 'dropdown' target = 'dummyframe'>
                                            $details_btn
                                            $cancel_btn
                                            $finish_btn
                                        </form>
                                    </div>
                                ";
                        }
                    }
                } else {
                    echo '
                            <span class = "no-appointments">No Appointments Found</span>
                        ';
                }
                ?>

            </div>
        </div>

        <div class="reload-all">
            <button type='button' id='reload-tbl' value='today'>Reload Table</button>
            <button id='see-all-appt'>Add an Appointment</button>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/notification.js'></script>
<script src='js/appointment-manager.js'></script>
<script src='js/book-appointment.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</html>