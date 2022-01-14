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
    <link rel='stylesheet' type='text/css' href='css/meeting.css'>
    <title>Document</title>
    <script src='https://meet.jit.si/external_api.js'></script>
</head>

<body>
    <?php
    session_start();
    include 'php_processes/db_conn.php';

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }


    ?>

    <div class='header-nav'>
        <div class='display-flex'>
            <div class='header-logo'>
                <img src='img/logo-2-meet.png'>
            </div>
            <span id='patient-name'>
                <?php
                $app_num = $_GET['appnum'];
                $pname = "";
                $schedule_start = "";
                $schedule_end = "";
                $query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_array($result)) {
                    $pname = $row['patient_fullname'];
                    $schedule_start = $row['date_and_time'];
                    $schedule_end = $row['date_and_time_finish'];
                }

                $date = substr($schedule_start, 0, 10);
                $date = strtotime($date);
                $date = date("M d, Y", $date);

                $schedule_start = substr($schedule_start, 11);
                $schedule_start = strtotime($schedule_start);
                $schedule_start = date("h:i A", $schedule_start);

                $schedule_end = substr($schedule_end, 11);
                $schedule_end = strtotime($schedule_end);
                $schedule_end = date("h:i A", $schedule_end);

                echo "<b>Patient Name: </b><span id = 'patient-fullname'>$pname</span>";
                ?>
            </span>
        </div>
        <span><b>Schedule:</b> <?php echo "$date ($schedule_start - $schedule_end)"; ?></span>
        <div>
            <button id='prescribe'>Prescribe</button>
            <button id='view-triage'>View Triage</button>
            <button id='conclude-appointment'>Conclude Appointment</button>
        </div>

        <div class='triage-dropdown'>
            <div class='triage-header'>
                <span>Triage</span>
            </div>
            <div class="triage-details">
                <!--DYNAMIC DETAILS HERE-->
            </div>
        </div>
    </div>


    <div id='meeting-window'></div>
</body>

<script src='js/meet.js'></script>

</html>