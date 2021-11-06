<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php
    include 'extras/employee-navbar.php';
    include 'extras/profile.php'
    ?>

    <div class='background-container'></div>

    <div class="notification-area">
        <div class="notification-box">
            <div class='notif-header'>
                <span>Notifications</span>
            </div>
            <div class="notif-contents">
                <!--10 People Have booked for appointments-->
                <div class="notif-content book-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        10 patients has booked for appointment
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div>

                <!--A patient has settled a bill-->
                <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div>

                <!--A patient has requested an appointment reschedule. Accept?-->
                <div class="notif-content resched-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain Magracia requested an appointment reschedule. Accept?
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div>

                <!--A patient has settled a bill-->
                <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div>

                <!--A patient has settled a bill-->
                <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div>

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

    <div class='employee-contents'>
        <!--APPOINTMENT UPCOMING-->
        <div class='e-contents-header-app'>
            <h1>Appointments</h1>
            <h2>Upcoming</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Appointment No.</span>
                <span>Patient</span>
                <span>Date/Time</span>
                <span>Contact No.</span>
            </div>

            <!-- TABLE CONTENTS -->

            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>0998390813</span>
            </div>
        </div>



        <!--APPOINTMENT HISTORY-->
        <div class='e-contents-header'>
            <h2>History</h2>
        </div>

        <div class='e-contents-table'>
            <div class='e-contents-header-table'>
                <span>Appointment No.</span>
                <span>Patient</span>
                <span>Date/Time</span>
                <span>Status</span>
            </div>

            <!-- TABLE CONTENTS -->
            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>

            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>

            <div class='e-contents'>
                <span>1902983721367</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021 5:00PM</span>
                <span>Appointed</span>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>

</html>