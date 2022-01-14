<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!--JQUERY UI-->
    <link rel='stylesheet' href='jqueryui/jquery-ui.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.structure.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.theme.css'>
    <script src='jqueryui/jquery-ui.js' type='text/javascript'></script>

    <title>Twin Care Portal | Homepage</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <!--THIS INVISIBLE IFRAME IS FOR PREVENTING FORM REDIRECTIONS-->
    <iframe name="dummyframe" id="dummyframe" style="display: none;"></iframe>

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

    <div class='background-container'>

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

        <div class='dim'>
            <div class='book-container'>
                <div class='book-header-exit'>
                    <span>Book an Appointment</span>
                    <span class='exit'>X</span>
                </div>
                <form class='book-content' target='dummyframe'>
                    <div class='row-0'>
                        <input type='text' class='date-time-input appointment-date-time' id='appointment-date-time' name='appointment-date-time' placeholder="Select Appointment Date (Required)" autocomplete='off'>
                        <div>
                            <input type='text' id='appointment-time' placeholder='Enter Appointment Time (Required)' autocomplete='off' readonly>
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
                        <input type='text' maxLength="100" id='chief-complaint' placeholder="Chief Complaint (e.g. Cough, Headache) (Required)" autocomplete="off">
                    </div>
                    <div class='row-1'>
                        <input type='text' maxLength="4" id='height' placeholder="Height (e.g. 5'4)">
                        <input type='text' maxLength="4" id='weight' placeholder="Weight (e.g. 170)">
                        <select id='weight-metric'>
                            <option value='kgs'>kg</option>
                            <option value='lbs'>lbs</option>
                        </select>
                        <input type='text' maxLength="7" id='blood-pressure' placeholder="Blood Pressure (e.g. 120/80)">
                    </div>
                    <div class="row-2">
                        <input type='text' maxLength="2" id='temperature' placeholder="Temperature (in degrees) (e.g. 36)">
                        <input type='text' maxLength="100" id='past-surgery' placeholder="Past Surgery/Hospitalization">
                    </div>
                    <fieldset class='row-3'>
                        <legend>Family History (Required)</legend>
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
                        <fieldset class='row-4-1'>
                            <legend>Allergies (Required)</legend>
                            <div>
                                <div class='inline-block'><input type="checkbox" name="none-allergies" id='none-allergies' value="None/Unknown"> None/Unknown</div>
                                <div class='inline-block'><input type="checkbox" name="allergies" value="Food"> Food</div>
                                <div class='inline-block'><input type="checkbox" name="allergies" value="Drug"> Drug</div>
                            </div>
                            <input type='text' maxLength="100" id='others-allergies' placeholder='(Others) Type Here'>
                        </fieldset>

                        <fieldset class='row-4-2'>
                            <legend>Social History (Required)</legend>
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
                        <input type='text' maxlength='100' id='curr-med' placeholder='Current Medications'>
                        <input type='text' maxlength='100' id='travel-history' placeholder='Travel History (last 3 months)'>
                    </div>

                    <div class="availability">
                        <span class='available-head'>Work Days:</span>
                        <span class='time-date'>Monday - Saturday</span>
                        <span class='time-date'>9:00AM - 4:00PM</span>
                    </div>
                    <div class='book-buttons'>
                        <button type="button" id='book'>Book Appointment</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="dim-2">
            <div class="bill-container">

            </div>
        </div>

        <div class="dim-3">
            <div class="pay-container">
                <div class='book-header-exit'>
                    <span>Online Request Confirmation</span>
                </div>
                <div class='pay-content'>
                    <span class='red-text'>Are you sure? By accepting, you are required to pay online via PayPal. When paid, only then the appointment will proceed with the online setup.</span>
                    <br />
                    <br />
                    <span class='red-text'><b>NOTE:</b></span>
                    <br />
                    <span class='red-text'>Twin Care Portal uses Paypal as a method for online transactions. This ensures that your transaction information is kept private. To know how Paypal keeps your transactions secure, please click here: <a href='https://www.paypal.com/ph/webapps/mpp/paypal-safety-and-security'>https://www.paypal.com/ph/webapps/mpp/paypal-safety-and-security</a></span>
                    <div class='pay-btns'>
                        <button type='button' class='cancel-accept'>Cancel</button>
                        <div class='paypal'></div>
                    </div>
                </div>
            </div>
        </div>


        <div class="dim-4">
            <div class="pay-container">
                <div class='book-header-exit'>
                    <span>Online Request Confirmation</span>
                </div>
                <div class='pay-content'>
                    <span class='red-text'>Are you sure? By declining, the appointment will continue with the Face-to-Face setup.</span>
                    <div class='pay-btns'>
                        <button type='button' class='cancel-decline'>Cancel</button>
                        <button type='button' class='accept-accept'>Decline</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="dim-5">
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

        <div class='contents'>
            <h1>Appointments</h1>
            <div class='appointment-container'>
                <h3 class='header-table'>
                    <span>Upcoming Appointments</span>
                </h3>

                <div class='table'>
                    <!--APPOINTMENT TABLE HEADER-->
                    <div class='table-header four-fr'>
                        <span>Appointment No.</span>
                        <span>Status</span>
                        <span>Date/Time</span>
                        <span>Type</span>
                    </div>

                    <div id='appt-table'>
                        <!--APPOINTMENT TABLE CONTENTS-->
                        <?php
                        include 'php_processes/db_conn.php';

                        $patientid = $_SESSION['patientid'];
                        $query = "SELECT * FROM appointments WHERE patient_id = '$patientid' AND (status = 'pending' OR status = 'ongoing' OR status = 'onlinereq')";

                        $result = mysqli_query($conn, $query);

                        if (mysqli_num_rows($result) > 0) {
                            while ($row = mysqli_fetch_array($result)) {
                                $appointmentnum = $row['appointment_num'];
                                $datetime = $row['date_and_time'];
                                $status = ucwords($row['status']);
                                $dt = new DateTime($datetime);
                                $type = ucwords($row['app_type']);

                                $cancel_btn = "<button type = 'button' class = 'cancel-appointment-patient' value = '$appointmentnum'>Cancel Appointment</button>";
                                $join_btn = "<button type = 'button' id = 'join-chatroom' value = '$appointmentnum'>Join Chatroom</button>";
                                $online_req = "<div class = 'online-req'>
                                                    <span>The doctor requested for the appointment to be conducted online. Do you accept? By accepting, you also agree that you have to pay online via PayPal. Decline to keep the Face-to-Face setup. 
                                                    </span>
                                                    <div>
                                                        <button type = 'button' class = 'accept-online' value = '$appointmentnum'>Accept</button>
                                                        <button type = 'button' class = 'decline-online' value = '$appointmentnum'>Decline</button>
                                                    </div>
                                                </div>";

                                $date = $dt->format('F j, Y, l');
                                $time = $dt->format('h:i A');

                                switch ($status) {
                                    case 'Ongoing':
                                        $cancel_btn = "";
                                        $online_req = "";
                                        break;
                                    case 'Pending':
                                        $join_btn = "";
                                        $online_req = "";
                                        break;
                                    case 'Onlinereq':
                                        $join_btn = "";
                                        $status = "Pending (Online Request)";
                                        break;
                                }

                                echo "
                                    <div class='table-content four-fr'>
                                        <span class='appointment-num'>$appointmentnum</span>
                                        <span class = 'orange-text'>$status</span>
                                        <span>$date / $time</span>
                                        <span class = 'e-num'>
                                            $type
                                            <button><i class='fas fa-ellipsis-v'></i></button>
                                        </span>
                                        <form class = 'dropdown'>
                                            <button type = 'button' class = 'view-triage-patient' value = '$appointmentnum'>View Triage</button>
                                            $cancel_btn
                                            $join_btn
                                            $online_req
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
            </div>


            <!--APPOINTMENT HISTORY-->
            <h3 class='header-table'>
                <span>History</span>
            </h3>

            <div class='table'>
                <!--APPOINTMENT TABLE HEADER-->
                <div class='table-header four-fr'>
                    <span>Appointment No.</span>
                    <span>Status</span>
                    <span>Date/Time</span>
                    <span>Type</span>
                </div>

                <div id='appt-table-all'>
                    <!--APPOINTMENT TABLE CONTENTS-->
                    <?php include 'php_processes/appt-table-patient.php'; ?>
                </div>
            </div>

            <div class='reload-book-btns'>
                <!-- <button id='hard-prev'>&#60;&#60;</button> -->
                <div class='reload-all'>
                    <button id='prev5'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num-app'>1</span>
                    <span id='offset-app'>0</span>
                    <button id='next5'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
                <!-- <button id='hard-next'>&gt;&gt;</button> -->

                <div>
                    <?php
                    $query = "SELECT has_appointment FROM user_table WHERE patient_id = '$patientid'";
                    $result = mysqli_query($conn, $query);
                    $row = mysqli_fetch_array($result);

                    $result2 = mysqli_query($conn, 'SELECT toggle FROM appointment_booking_toggle');
                    $row2 = mysqli_fetch_array($result2);

                    if ($row2['toggle'] == '0') {
                        echo "<button id = 'reload'>Reload Tables</button>";
                    } else if ($row[0] == 1) {
                        echo "
                            <button id = 'reload'>Reload Tables</button>
                            <button id='book-appointment' disabled>Book Appointments</button>
                        ";
                    } else {
                        echo "
                            <button id = 'reload'>Reload Tables</button>
                            <button id='book-appointment'>Book Appointments</button>
                        ";
                    }
                    ?>
                </div>
            </div>

            <!--END OF SECOND TABLE-->

            <div class='transaction-container'>
                <h1>Transactions</h1>
                <h3 class='header-table'>History</h3>

                <div class='table'>
                    <!--TRANSACTION TABLE HEADER-->
                    <div class='transaction-header'>
                        <span>Transaction ID</span>
                        <span>Date of Payment</span>
                        <span>Amount</span>
                        <span>Status</span>
                        <span></span>
                    </div>

                    <div class="transaction-table">
                        <!--TRANSACTION TABLE CONTENT-->
                        <?php include 'php_processes/patient-transaction-table.php'; ?>
                    </div>
                </div>

                <div class='reload-all'>
                    <button id='prev6'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num-trans'>1</span>
                    <span id='offset-trans'>0</span>
                    <button id='next6'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
            </div>
        </div>
    </div>
    <!--SANDBOX-->
    <script src="https://www.paypal.com/sdk/js?client-id=AaHbmFJW2LslQgEHPVAc8ASwU6pgLw8kyDy-H8OZ8uGXBmrpQiWa-9N3MKtychySNzBcqg0fJsKo0L-x&disable-funding=credit,card&currency=PHP"></script>
    <!--LIVE-->
    <!--<script src="https://www.paypal.com/sdk/js?client-id=AcOQatDCaLrp7YyymYLrFyukmnadZ5qRg5z2VIWv_qaG4ADENWl1vRgkP5MrNyNH-IRlR5JcvdNQgpbU&disable-funding=credit,card&currency=PHP"></script>-->
</body>
<script src='js/navbar.js'></script>
<script src='js/book-appointment.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src='js/notification.js'></script>
<script src='js/appointment-manager.js'></script>
<script src='js/billing.js'></script>

</html>