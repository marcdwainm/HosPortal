<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='fullcalendar/main.css' rel='stylesheet' />
    <script src='fullcalendar/main.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>

    <!--JQUERY UI-->
    <link rel='stylesheet' href='jqueryui/jquery-ui.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.structure.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.theme.css'>
    <script src='jqueryui/jquery-ui.js' type='text/javascript'></script>

    <title>Twin Care Portal | Homepage</title>
    <link rel="icon" href="img/logo.png">
    <?php

    session_start();

    if (!isset($_SESSION['emp_id'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position !== 'doctor') {
        header("Location: $position-homepage.php");
    }

    include 'extras/profile.php';
    include 'php_processes/db_conn.php';

    ?>
    <?php include 'php_processes/calendar-script.php'; ?>
</head>

<body>
    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>
    <div class='background-container'>

        <div class='event-tooltip'>

        </div>

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


        <div class='dim'>
            <div class='book-container'>
                <div class='book-header-exit'>
                    <span>Book an Appointment</span>
                    <span class='exit'>X</span>
                </div>
                <form class='book-content-doctor' target='dummyframe'>
                    <div class='portal-registered-checkbox'>
                        <div class = 'triage-fill-up-warning'>
                            <label>*Input boxes in "Blue" are required. Otherwise, boxes in "Gray" can be left blank.</label>
                        </div>
                        <div class='checkbox2'>
                            <label for="portal-registered">Portal Registered Patient</label>
                            <input type="checkbox" name="portal-registered" id='portal-registered' value="p-registered">
                        </div>
                    </div>
                    <div class='row-0'>
                        <input type='text' class='date-time-input' id='appointment-date-time' name='appointment-date-time' placeholder="Select Date" autocomplete='off' style = 'border: 1px solid var(--blue);'>
                        <div class='app-time'>
                            <input type='text' id='appointment-time' placeholder='Enter Time' autocomplete='off' style = 'border: 1px solid var(--blue);' readonly>
                            <div id='autocomplete-contain'>
                                <div class='time-autocomplete'>
                                    <button type='button' id='i09-00-00' class='chosen-time'>9:00 AM</button>
                                    <button type='button' id='i09-15-00' class='chosen-time'>9:15 AM</button>
                                    <button type='button' id='i09-30-00' class='chosen-time'>9:30 AM</button>
                                    <button type='button' id='i09-45-00' class='chosen-time'>9:45 AM</button>

                                    <button type='button' id='i10-00-00' class='chosen-time'>10:00 AM</button>
                                    <button type='button' id='i10-15-00' class='chosen-time'>10:15 AM</button>
                                    <button type='button' id='i10-30-00' class='chosen-time'>10:30 AM</button>
                                    <button type='button' id='i10-45-00' class='chosen-time'>10:45 AM</button>

                                    <button type='button' id='i11-00-00' class='chosen-time'>11:00 AM</button>
                                    <button type='button' id='i11-15-00' class='chosen-time'>11:15 AM</button>
                                    <button type='button' id='i11-30-00' class='chosen-time'>11:30 AM</button>
                                    <button type='button' id='i11-45-00' class='chosen-time'>11:45 AM</button>

                                    <button type='button' id='i12-00-00' class='chosen-time'>12:00 PM</button>
                                    <button type='button' id='i12-15-00' class='chosen-time'>12:15 PM</button>
                                    <button type='button' id='i12-30-00' class='chosen-time'>12:30 PM</button>
                                    <button type='button' id='i12-45-00' class='chosen-time'>12:45 PM</button>

                                    <button type='button' id='i13-00-00' class='chosen-time'>1:00 PM</button>
                                    <button type='button' id='i13-15-00' class='chosen-time'>1:15 PM</button>
                                    <button type='button' id='i13-30-00' class='chosen-time'>1:30 PM</button>
                                    <button type='button' id='i13-45-00' class='chosen-time'>1:45 PM</button>

                                    <button type='button' id='i14-00-00' class='chosen-time'>2:00 PM</button>
                                    <button type='button' id='i14-15-00' class='chosen-time'>2:15 PM</button>
                                    <button type='button' id='i14-30-00' class='chosen-time'>2:30 PM</button>
                                    <button type='button' id='i14-45-00' class='chosen-time'>2:45 PM</button>

                                    <button type='button' id='i15-00-00' class='chosen-time'>3:00 PM</button>
                                    <button type='button' id='i15-15-00' class='chosen-time'>3:15 PM</button>
                                    <button type='button' id='i15-30-00' class='chosen-time'>3:30 PM</button>
                                    <button type='button' id='i15-45-00' class='chosen-time'>3:45 PM</button>

                                    <button type='button' id='i16:00:00' class='chosen-time'>4:00 PM</button>
                                </div>
                            </div>
                        </div>
                        <div class='search-patient'>
                            <input type='text' id='pname-search' placeholder="Patient's Name" autocomplete="off" style = 'border: 1px solid var(--blue);'>
                            <div id='plist-search'>
                                <!--Autocomplete search results here-->
                            </div>
                        </div>
                        <input type='text' id='pcontact' placeholder="Contact Number" maxlength="11">
                        <input type='text' id='patient-age' placeholder='Age' maxlength="3">
                        <input type='text' id='chief-complaint' placeholder='Chief Complaint' autocomplete="off" style = 'border: 1px solid var(--blue);'>
                    </div>
                    <div class='row-1'>
                        <input type='text' maxLength="4" id='height' placeholder="Height (e.g. 5'4)" autocomplete='off'>
                        <input type='text' maxLength="4" id='weight' placeholder="Weight (e.g. 170)" autocomplete='off'>
                        <select id='weight-metric'>
                            <option value='kgs'>kg</option>
                            <option value='lbs'>lbs</option>
                        </select>
                        <input type='text' maxLength="7" id='blood-pressure' placeholder="Blood Pressure (e.g. 120/80)" autocomplete='off'>
                    </div>
                    <div class="row-2">
                        <input type='text' maxLength="2" id='temperature' placeholder="Temperature (in degrees) (e.g. 36)" autocomplete='off'>
                        <input type='text' maxLength="100" id='past-surgery' placeholder="Past Surgery/Hospitalization" autocomplete='off'>
                    </div>
                    <fieldset class='row-3' style = 'border: 1px solid var(--blue);'>
                        <legend>Family History</legend>
                        <div id='family-history-checkboxes'>
                            <div class='inline-block'><input type="checkbox" name="none-family-history" id='none-fam-history' value="None/Unknown"> None/Unknown</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Asthma"> Asthma</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Heart Disease"> Heart Disease</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Hypertension"> Hypertension</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Diabetes"> Diabetes</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Renal Disease"> Renal Disease</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Cerebrovascular Disease"> Cerebrovascular Disease</div>
                            <div class='inline-block'><input type="checkbox" name="family-history" value="Cancer"> Cancer</div>
                        </div>
                        <input type='text' maxlength='100' id='others-fam-history' placeholder='(Others) Type Here'>
                    </fieldset>
                    <div class='row-4'>
                        <fieldset class='row-4-1' style = 'border: 1px solid var(--blue);'>
                            <legend>Allergies</legend>
                            <div>
                                <div class='inline-block'><input type="checkbox" name="none-allergies" id='none-allergies' value="None/Unknown"> None/Unknown</div>
                                <div class='inline-block'><input type="checkbox" name="allergies" value="Food"> Food</div>
                                <div class='inline-block'><input type="checkbox" name="allergies" value="Drug"> Drug</div>
                            </div>
                            <input type='text' maxLength="100" id='others-allergies' placeholder='(Others) Type Here'>
                        </fieldset>

                        <fieldset class='row-4-2' style = 'border: 1px solid var(--blue);'>
                            <legend>Social History</legend>
                            <div>
                                <div class='inline-block'><input type="checkbox" name="none-social-history" id='none-social-history' value="None/Unknown"> None/Unknown</div>
                                <div class='inline-block'><input type="checkbox" name="socialhistory" value="Tobacco"> Tobacco</div>
                                <div class='inline-block'><input type="checkbox" name="socialhistory" value="E-Cigarette"> E-Cigarette</div>
                                <div class='inline-block'><input type="checkbox" name="socialhistory" value="Alcohol"> Alcohol</div>
                                <div class='inline-block'><input type="checkbox" name="socialhistory" value="Drugs"> Drugs</div>
                            </div>
                            <input type='text' maxlength='100' id='others-social-history' placeholder='(Others) Type Here'>
                        </fieldset>
                    </div>
                    <div class='row-5'>
                        <input type='text' maxlength='100' id='curr-med' placeholder='Current Medications' autocomplete='off'>
                        <input type='text' maxlength='100' id='travel-history' placeholder='Travel History (last 3 months)' autocomplete='off'>
                    </div>
                    <span id="patient-error" class='p-error'>Patient is not Portal-registered</span>
                    <button type='submit' id='book-doctor' value='0000'>Book Appointment</button>
                </form>
            </div>
        </div>

        <div class="dim-2">
            <div class='generate-meeting-container'>
                <div class='book-header-exit'>
                    <span>E-Consultation</span>
                    <span class='exit' id='exit-meet'>X</span>
                </div>
                <div class='meeting-message'></div>
                <div class='generate-meet-div'>
                    <button id='generate-meet-link'>
                        Set-up meeting room
                    </button>
                </div>
            </div>
        </div>

        <div class="dim-3">
            <div class='view-triage-container'>
                <div class='book-header-exit'>
                    <span>Triage</span>
                    <span class='exit' id='exit-triage'>X</span>
                </div>
                <div class="triage-details">
                    <!--DYNAMIC DETAILS HERE-->
                </div>
            </div>
        </div>

        <div class="dim-4">
            <div class='issue-bill-container'>
                <div class='book-header-exit'>
                    <span>Bill Issuing</span>
                    <span class='exit' id='exit-issue'>X</span>
                </div>
                <div class="issue-details">

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
                    <button type='button' id='issue-bill'>Issue Bill</button>
                </div>
            </div>
        </div>

        <div class='header-intro'>
            
            <?php
                $query = "SELECT * FROM appointments WHERE (status = 'pending' || status = 'ongoing' || status = 'onlinereq') AND date(date_and_time) = CURDATE()";
                $result = mysqli_query($conn, $query);

                if(mysqli_num_rows($result) > 0){
                    $apptnumber = mysqli_num_rows($result);
                    echo "
                        <div class = 'red-text'>Reminder: You have $apptnumber appointment/s today.</div>
                    ";
                }
            ?>  
        </div>

        <div class='employee-contents'>
            <div class='calendar-legend'>
            <button id='see-all-appt'>Add an Appointment</button>
                <div class='legends'>
                    <div class="legend-appointed">
                        <div></div>
                        <span>Appointed</span>
                    </div>
                    <div class="legend-pending">
                        <div></div>
                        <span>Pending/Ongoing</span>
                    </div>
                    <div class="legend-cancelled">
                        <div></div>
                        <span>Cancelled/Missed</span>
                    </div>
                </div>
            </div>
            <div id='calendar'></div>
            <div class='appointment-calendar-dropdown'>
                <button>View Triage</button>
                <button>Request Online</button>
                <button>Finish Appointment</button>
                <button>Marked as Missed</button>
                <button>Cancel Appointment</button>
            </div>
            <!--APPOINTMENT UPCOMING-->
            <div class='e-contents-header-app'>
                <h1>Appointments Table</h1>
                <h2 class='h2-sortation'>
                    <span class='title'>Today</span>
                    <form class='sortation' method='GET'>
                        <span id='patient-error3' class='margin-right'>Enter a name</span>
                        <input type='text' class='sortation-text-byname' placeholder='Enter Patient Name' maxlength="40">
                        <select name='sortation' class='sortation-select'>
                            <option value='' selected disabled>-- Select Option --</option>
                            <option value='today'>Today</option>
                            <option value='upcoming'>Upcoming</option>
                            <option value='recent'>Recent</option>
                            <option value='lastweek'>Last 7 Days</option>
                            <option value='pending'>Pending</option>
                            <option value='appointed'>Appointed</option>
                            <option value='cancelled'>Cancelled</option>
                            <option value='missed'>Missed</option>
                            <option value='byname'>By Name</option>
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
                    <span>Type</span>
                    <span>Contact No.</span>
                </div>

                <div id='doctor-appt-table'>
                    <!-- TABLE CONTENTS -->
                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE() ORDER BY date_and_time ASC LIMIT 0, 5";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) > 0) {
                        while ($row = mysqli_fetch_array($result)) {
                            $appointmentnum = $row['appointment_num'];
                            $fullname = ucwords($row['patient_fullname']);
                            $datetime = Date("D, M d, h:i A", strtotime($row['date_and_time']));
                            $status = ucfirst($row['status']);
                            $app_type = ucfirst($row['app_type']);
                            $status_div;
                            $finish_btn = "<button type = 'button' id = 'finish-appointment' value = '$appointmentnum'>Finish Appointment</button>";
                            $cancel_btn = "<button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>";
                            $missed_btn = "<button type = 'button' class = 'missed' value = '$appointmentnum'>Mark as missed</button>";
                            $call_btn = "<button type = 'button' class = 'consult-online' value = '$appointmentnum'>Set-up Chat Room</button>";
                            $details_btn = "<button type = 'button' class = 'view-triage' value = '$appointmentnum'>View Triage</button>";
                            $online_btn = "<button type = 'button' class = 'request-online' value = '$appointmentnum'>Request Online</button>";

                            if ($status == 'Pending' && $app_type == 'Online') {
                                $status_div = "<span class = 'orange-text'>$status</span>";
                                $online_btn = "";
                            } else if ($status == 'Pending' && $app_type == 'F2f') {
                                $status_div = "<span class = 'orange-text'>$status</span>";
                                $call_btn = "";
                            } else if ($status == 'Appointed') {
                                $status_div = "<span class = 'green-text'>$status</span>";
                                $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
                            } else if ($status == 'Missed' || $status == 'Cancelled') {
                                $status_div = "<span class = 'red-text'>$status</span>";
                                $finish_btn = $cancel_btn = $missed_btn = $call_btn = $online_btn = "";
                            } else if ($status == 'Ongoing') {
                                $status_div = "<span class = 'orange-text'>$status</span>";
                                $call_btn = "<button type = 'button' value = '$appointmentnum' class = 'consult-online'>Rejoin Meeting</button>";
                                $online_btn = "";
                            } else if ($status == 'Onlinereq') {
                                $status_div = "<span class = 'orange-text'>F2f (Online Requested)</span>";
                                $online_btn = "";
                            }

                            $dt = new DateTime($datetime);

                            $date = $dt->format('F j, Y l');
                            $time = $dt->format('h:i A');

                            echo "
                            <div class='e-contents'>
                                    <span>$appointmentnum</span>
                                    <span>$fullname</span>
                                    <span>$datetime</span>
                                    $status_div
                                    <span>$app_type</span>
                                    <span class = 'e-num'>
                                        0998390813
                                        <button><i class='fas fa-ellipsis-v'></i></button>
                                    </span>
                                    <form class = 'dropdown' target = 'dummyframe'>
                                        $details_btn
                                        $online_btn   
                                        $finish_btn
                                        $missed_btn
                                        $cancel_btn
                                        $call_btn
                                    </form>
                                </div>
                            ";
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
                <div>
                    <button id='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
                <div>
                    <button type='button' id='reload-tbl' value='today'>Reload Table</button>
                </div>
            </div>
        </div>
    </div>
</body>

<script src='js/navbar.js'></script>
<script src='js/notification-doc.js'></script>
<script src='js/appointment-manager.js'></script>
<script src='js/book-appointment.js'></script>
<script src='js/billing.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</html>