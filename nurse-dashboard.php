<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href='fullcalendar/main.css' rel='stylesheet' />
    <script src='fullcalendar/main.js'></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" type="text/css" href="https://npmcdn.com/flatpickr/dist/themes/airbnb.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    
    <!--CSS-->
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/employee-contents.css'>

    <!--JQUERY UI-->
    <link rel='stylesheet' href='jqueryui/jquery-ui.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.structure.css'>
    <link rel='stylesheet' href='jqueryui/jquery-ui.theme.css'>
    <script src='jqueryui/jquery-ui.js' type='text/javascript'></script>


    <title>Twin Care Portal | Dashboard</title>
    <link rel="icon" href="img/logo.png">
    <?php

    session_start();

    if (!isset($_SESSION['emp_id'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position !== 'nurse') {
        header("Location: $position-homepage.php");
    }

    include 'extras/nurse-profile.php';
    include 'php_processes/db_conn.php';

    ?>
    <?php include 'php_processes/calendar-script.php'; ?>
</head>

<body>
    <div class='background-container'>

        <div class='header-intro'>
            <h1>Welcome, Dr. <?php echo ucwords($_SESSION['fullname']) ?></h1>
        </div>

        <div class='employee-contents'>
            <h2 class = "mb-4">Appointments</h1>
            <!--Appointed Yesterday-->
            <div class = "container p-0 d-flex justify-content-between gap-3 mb-5">
                <div class="card shadow-sm w-100">
                    <div class="card-header bg-white fw-bold small">Appointments Yesterday</div>
                    <div class="card-body d-flex justify-content-around">
                        <?php
                            include 'php_processes/db_conn.php';

                            $query = "SELECT * FROM appointments WHERE DATE(date_and_time) = SUBDATE(CURDATE(), 1)";
                            $result = mysqli_query($conn, $query);

                            $appt_count = mysqli_num_rows($result);
                            
                            echo "
                            <h1 class = 'blue-text fw-bolder'>$appt_count</h1>
                            <div class = 'd-flex flex-column'>
                            ";
                            
                            while($row = mysqli_fetch_array($result)){
                                $status = $row['status'];
                                $pname = $row['patient_fullname'];
                                echo "
                                    <small class = 'd-flex align-items-center'><div class = 'status-circle me-2 status-circle-$status'></div>$pname</small>
                                ";
                            }

                            echo "</div>";
                        ?>
                    </div>
                </div>

                <div class="card shadow-sm w-100">
                    <div class="card-header bg-white fw-bold small">Appointments Today</div>
                    <div class="card-body d-flex justify-content-around">
                        <?php
                            include 'php_processes/db_conn.php';

                            $query = "SELECT * FROM appointments WHERE DATE(date_and_time) = CURDATE()";
                            $result = mysqli_query($conn, $query);

                            $appt_count = mysqli_num_rows($result);
                            
                            echo "
                            <h1 class = 'blue-text fw-bolder'>$appt_count</h1>
                            <div class = 'd-flex flex-column'>
                            ";
                            
                            while($row = mysqli_fetch_array($result)){
                                $status = $row['status'];
                                $pname = $row['patient_fullname'];
                                echo "
                                    <small class = 'd-flex align-items-center'><div class = 'status-circle me-2 status-circle-$status'></div>$pname</small>
                                ";
                            }

                            echo "</div>";
                        ?>
                    </div>
                </div>

                <div class="card shadow-sm w-100">
                    <div class="card-header bg-white fw-bold small">Appointments Tomorrow</div>
                    <div class="card-body d-flex justify-content-around">
                        <?php
                            include 'php_processes/db_conn.php';

                            $query = "SELECT * FROM appointments WHERE DATE(date_and_time) = ADDDATE(CURDATE(), 1)";
                            $result = mysqli_query($conn, $query);

                            $appt_count = mysqli_num_rows($result);

                            echo "
                            <h1 class = 'blue-text fw-bolder'>$appt_count</h1>
                            <div class = 'd-flex flex-column'>
                            ";

                            while($row = mysqli_fetch_array($result)){
                                $status = $row['status'];
                                $pname = $row['patient_fullname'];
                                echo "
                                    <small class = 'd-flex align-items-center'><div class = 'status-circle me-2 status-circle-$status'></div>$pname</small>
                                ";
                            }

                            echo "</div>";
                        ?>
                    </div>
                </div>
            </div>
                            
            <div class="container p-0 mb-3 d-flex gap-3 mb-5">
                <div class="card shadow-sm w-100">
                    <div class="card-header bg-white fw-bold small">Latest Payments</div>
                    <div class="card-body p-0 d-flex flex-column justify-content-center">
                        <?php
                            $query = "SELECT * FROM bills WHERE date_of_payment <= NOW() ORDER BY date_of_payment DESC LIMIT 5";
                            $result = mysqli_query($conn, $query);

                            if(mysqli_num_rows($result) > 0){
                                $kulang = 5 - mysqli_num_rows($result);
                                while($row = mysqli_fetch_array($result)){
                                    $issued_to = $row['issued_to'];

                                    $query2 = "SELECT * FROM user_table WHERE patient_id = '$issued_to'";
                                    $result2 = mysqli_query($conn, $query2);
                                    $row2 = mysqli_fetch_array($result2);

                                    $fullname = $row2['first_name'] . " " . substr($row2['middle_name'], 0, 1) . ". " . $row2['last_name'];

                                    echo "
                                        <div class = 'd-flex justify-content-between p-3 border-bottom'>
                                            <small>$fullname</small>
                                            <small class = 'fw-bold price-green'>+P". $row['total'] ."</small>
                                        </div>
                                    ";
                                }

                                for($i = 0; $i < $kulang; $i++){
                                    echo "
                                    <div class = 'd-flex justify-content-between p-3 border-bottom'>
                                        <small class = 'text-white'>.</small>
                                        <small class = 'fw-bold'></small>
                                    </div>
                                    ";
                                }
                            }
                            else{
                                echo "
                                    <div class = 'text-center p-2'>
                                        <span class = 'text-muted fw-bold'>No payments made</span>
                                    </div>
                                ";
                            }

                            
                            
                        ?>
                    </div>
                </div>

                <div class="card shadow-sm w-100">
                    <div class="card-header bg-white fw-bold small">Latest Documents</div>
                    <div class="card-body p-0 d-flex flex-column justify-content-center">
                        <?php
                            $query = "SELECT * FROM documents WHERE date_uploaded <= NOW() ORDER BY date_uploaded DESC LIMIT 5";
                            $result = mysqli_query($conn, $query);

                            if(mysqli_num_rows($result) > 0){
                                
                                while($row = mysqli_fetch_array($result)){
                                    $pname = $row['patient_name'];
                                    $doc_type = $row['doc_type'] == 'labresult' ? "Lab Result" : "Prescription";

                                    echo "
                                        <div class = 'd-flex justify-content-between p-3 border-bottom'>
                                            <small>$pname <span class = 'text-muted'>($doc_type)</span></small>
                                            <small class = 'fw-bold text-muted'>". time_elapsed_string($row['date_uploaded']) ."</small>
                                        </div>
                                    ";
                                }
                            }
                            else{
                                echo "
                                    <div class = 'text-center p-2'>
                                        <span class = 'text-muted fw-bold'>No Documents</span>
                                    </div>
                                ";
                            }
                            
                            function time_elapsed_string($datetime, $full = false) {

                                $now = new DateTime();
                                $now->add(new DateInterval('PT7H'));
                                $ago = new DateTime($datetime);
                                $diff = $now->diff($ago);
                            
                                $diff->w = floor($diff->d / 7);
                                $diff->d -= $diff->w * 7;
                            
                                $string = array(
                                    'y' => 'year',
                                    'm' => 'month',
                                    'w' => 'week',
                                    'd' => 'day',
                                    'h' => 'hour',
                                    'i' => 'minute',
                                    's' => 'second',
                                );
                                foreach ($string as $k => &$v) {
                                    if ($diff->$k) {
                                        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
                                    } else {
                                        unset($string[$k]);
                                    }
                                }
                            
                                if (!$full) $string = array_slice($string, 0, 1);
                                return $string ? implode(', ', $string) . ' ago' : 'just now';
                            }
                        ?>
                    </div>
                </div>
            </div>

            <!--Next Appointment-->
            <div class = 'card shadow-sm w-100 mb-5'>
                <div class="card-header bg-white fw-bold small">Next Appointment</div>
                <div class="d-flex">
                    <?php
                        $query = "SELECT * FROM appointments WHERE DATE(date_and_time) = CURDATE() AND CURTIME() <= TIME(date_and_time) LIMIT 1";
                        $result = mysqli_query($conn, $query);
                        $row = mysqli_fetch_array($result);
                        $appointment_num = "";
                        $date_and_time = "";
                        
                        if(mysqli_num_rows($result) > 0){
                            $appointment_num = $row['appointment_num'];
                            $pid = $row['patient_id'];
                            $pname = $row['patient_fullname'];
                            $date_and_time = date("h:i", strtotime($row['date_and_time']));
                            $date_and_time_finish = date("h:i A", strtotime($row['date_and_time_finish']));
    
                            $query2 = "SELECT * FROM user_table WHERE patient_id = '$pid'";
                            $result2 = mysqli_query($conn, $query2);
                            $row2 = mysqli_fetch_array($result2);

                            $email = $row2['email'];
                            $contact = $row2['contact_num'];
                            $sex = ucfirst($row2['sex']);

                            $birthdate = $row2['birthdate'];
                            $from = new DateTime($birthdate);
                            $to = new DateTime('today');
                            $age = $from->diff($to)->y;
                            
    
                            echo "
                                <div class = 'd-flex flex-column border border-start-0 border-top-0 border-bottom-0 px-4 py-3 w-25'>
                                    <h4>Details</h4>
                                    <small class = 'fw-bold'>Time: <span class = 'fw-normal'>$date_and_time - $date_and_time_finish</span></small>
                                    <small class = 'fw-bold'>Patient: <span class = 'fw-normal'>$pname</span></small>
                                    <small class = 'fw-bold'>Email: <span class = 'fw-normal'>$email</span></small>
                                    <small class = 'fw-bold'>Contact No.: <span class = 'fw-normal'>$contact</span></small>
                                    <small class = 'fw-bold'>Sex: <span class = 'fw-normal'>$sex</span></small>
                                    <small class = 'fw-bold'>Age: <span class = 'fw-normal'>$age</span></small>
                                </div>
                            ";
                        }
                        else{
                            echo "
                                <div class = 'p-4 text-center w-100'><span class = 'text-secondary fw-bold'>No more appointments for today</span></div>
                            ";
                        }
                        
                        if(mysqli_num_rows($result) > 0){
                            $query3 = "SELECT * FROM triage WHERE appointment_num = '$appointment_num'";
                            $result3 = mysqli_query($conn, $query3);
                            $row3 = mysqli_fetch_array($result3);
    
                            echo "
                                <div class = 'd-flex flex-column w-75 px-4 py-3'>
                                    <div>
                                        <h4>Triage</h4>
                                    </div>
                                    <div class = 'd-flex'>
                                        <div class = 'd-flex flex-column col-4'>
                                            <small class = 'fw-bold'>Appointment No.: <br /><span class = 'fw-normal'>$appointment_num</span></small>
                                            <small class = 'fw-bold'>Chief Complaint: <br /><span class = 'fw-normal'>". $row3['chief_complaint'] . "</span></small>
                                            <small class = 'fw-bold'>Height: <br /><span class = 'fw-normal'>". $row3['height'] . "</span></small>
                                            <small class = 'fw-bold'>Weight: <br /><span class = 'fw-normal'>". $row3['weight'] . "</span></small>
                                        </div>
                                        <div class = 'd-flex flex-column col-4'>
                                            <small class = 'fw-bold'>Blood Pressure: <br /><span class = 'fw-normal'>". $row3['blood_pressure'] . "</span></small>
                                            <small class = 'fw-bold'>Temperature: <br /><span class = 'fw-normal'>". $row3['temperature'] . "</span></small>
                                            <small class = 'fw-bold'>Past Surgery: <br /><span class = 'fw-normal'>". $row3['past_surgery'] . "</span></small>
                                            <small class = 'fw-bold'>Family History: <br /><span class = 'fw-normal'>". $row3['family_history'] . "</span></small>
                                        </div>
                                        <div class = 'd-flex flex-column col-4'>
                                            <small class = 'fw-bold'>Allergies: <br /><span class = 'fw-normal'>". $row3['allergies'] . "</span></small>
                                            <small class = 'fw-bold'>Social History: <br /><span class = 'fw-normal'>". $row3['social_history'] . "</span></small>
                                            <small class = 'fw-bold'>Current Medications: <br /><span class = 'fw-normal'>". $row3['current_medications'] . "</span></small>
                                            <small class = 'fw-bold'>Travel History: <br /><span class = 'fw-normal'>". $row3['travel_history'] . "</span></small>
                                        </div>
                                    </div>
                                </div>
                            ";
                        }
                    ?>
                </div>
            </div>

            <!--Next Appointment-->


            <!--Payments for the last 24 Hrs-->
        </div>
    </div>
</body>

<script src='js/navbar.js'></script>
<script src='js/notification-doc.js'></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

</html>