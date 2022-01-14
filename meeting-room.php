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

    <!--THIS PHP WILL CHECK IF THE APPOINTMENT ALREADY HAS SOAP NOTE
    IF YES, THEN SET THE VALUES TO TEXTAREAS -->
    <?php

    $query = "SELECT * FROM soap_notes WHERE appointment_num = '$app_num'";
    $result = mysqli_query($conn, $query);
    $soap = "";
    $s = "";
    $o = "";
    $a = "";
    $p = "";

    if (mysqli_num_rows($result) > 0) {
        // IF A SOAP NOTE HAS BEEN CREATED FOR THE APPOINTMENT
        while ($row = mysqli_fetch_array($result)) {
            $soap = $row['soap_note'];
        }

        $soap = explode(' ### ', $soap);
        $s = $soap[0];
        $o = $soap[1];
        $a = $soap[2];
        $p = $soap[3];
    } else {
        // CREATE NEW SOAP NOTE

        $query = "SELECT * FROM appointments WHERE appointment_num = '$app_num'";
        $result = mysqli_query($conn, $query);
        $appointment_start = "";
        $patient_id = "";

        while ($row = mysqli_fetch_array($result)) {
            $appointment_start = $row['date_and_time'];
            $patient_id = $row['patient_id'];
        }

        date_default_timezone_set('Asia/Manila');
        $date_created = date("Y-m-d H:i:s", time());

        $query = "INSERT INTO soap_notes (appointment_num, appointment_date_time, date_created, patient_id, soap_note) VALUES ('$app_num', '$appointment_start', '$date_created', '$patient_id', ' ###  ###  ### ')";
        mysqli_query($conn, $query);
    }
    ?>

    <div class='soap-area'>
        <div class='soap-div'>
            <div class='soap-button'>
                <i class="far fa-edit fa-lg"></i>
            </div>

            <div class='soap-note-container'>
                <div class='soap-note-wrapper'>
                    <div class='soap-header'>
                        <span>SOAP Note</span>
                        <span id='save'></span>
                        <div>
                            <button id='open-soap-note-btn' value=<?php echo "'$app_num'" ?>>Open</button>
                            <div class='open-soap-note'>
                                <div class='open-soap-note-header'>
                                    <span>Open SOAP Note</span>
                                </div>
                                <div class='open-soap-note-files'>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='soap-notes'>
                        <div class='soap-subjective'>
                            <div>
                                <b>Subjective Complaint</b>
                                <div>
                                    <button class='add-bullet' id='add-bullet-subjective'>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea id='subjective-textarea' placeholder='Type notes here...'><?php echo !empty($s) ? $s : ""; ?></textarea>
                        </div>
                        <div class='soap-objective'>
                            <div>
                                <b>Physical Examination</b>
                                <div>
                                    <button class='add-bullet' id='add-bullet-objective'>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea id='objective-textarea' placeholder='Type notes here...'><?php echo !empty($o) ? $o : ""; ?></textarea>
                        </div>
                        <div class='soap-assessment'>
                            <div>
                                <b>Assessment/Diagnosis</b>
                                <div>
                                    <div class='icd-10-container'>
                                        <button id='insert-icd-10'>ICD-10</button>
                                        <div class='icd-10-codes'>
                                            <div class="icd-10-codes-header">
                                                <input type='text' id='icd-10-code-doctor-keyword' placeholder='Enter Code or Diagnosis'>
                                                <button id='icd-10-search'>Search</button>
                                            </div>
                                            <div class='icd-10-code-buttons'>
                                                <span class='no-results'>No Results</span>
                                                <!--  JSON RESULTS HERE -->
                                                <!---
                                                    STOPPED HERE,
                                                    TODO
                                                    ADD FUNCTIONALITY FOR 'OPEN SOAP'
                                                        IF OPEN SOAP, ASK IF DOCTOR WILL DISCARD CHANGES TO THE CURRENT SOAP
                                                        IF DOCTOR WANTS TO SAVE SOAP, SAVE SOAP TO DATABASE AND OPEN SOAP NOTE
                                                    SAVE SOAP DATA TO DATABASE
                                                    ADD FUNCTIONALITY FOR SOAP TABLE IN PATIENTS TABLE
                                                    ADD 'ADD PATIENT' BUTTON
                                                        THIS WILL MANUALLY ADD THE PATIENT 
                                                -->
                                            </div>
                                        </div>
                                    </div>
                                    <button class='add-bullet' id='add-bullet-assessment'>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea id='diagnosis-textarea' placeholder='Type notes here...'><?php echo !empty($a) ? $a : ""; ?></textarea>
                        </div>
                        <div class='soap-plan'>
                            <div>
                                <b>Treatment/Plan</b>
                                <div>
                                    <button class='add-bullet' id='add-bullet-plan'>
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                            <textarea id='plan-textarea' placeholder='Type notes here...'><?php echo !empty($p) ? $p : ""; ?></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>

<script src='js/meet.js'></script>

</html>