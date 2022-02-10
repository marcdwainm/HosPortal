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
    <title>Twin Care Portal | Appointment Report</title>
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

    <div class='background-container'>

        <div class='employee-contents margin-top'>
            
            <?php
                $soap_id = $_GET['soap'];
                
                $query = "SELECT * FROM soap_notes WHERE soap_id = '$soap_id'";
                $result = mysqli_query($conn, $query);
                $row1 = mysqli_fetch_array($result);
                $pid = $row1['patient_id'];
                $soap_date = $row1['date_created'];

                echo "
                    <h2>Appointment Report: ". date("M d, Y / D (h:i A)", strtotime($row1['appointment_date_time'])) . "</h2>
                ";

                include "php_processes/db_conn.php";

                $query = "SELECT * FROM soap_notes WHERE soap_id = '$soap_id'";
                $result = mysqli_query($conn, $query);
                $row = mysqli_fetch_array($result);

                $soap_contents =  explode(' ### ', $row['soap_note']);
                $soap_subjective = $soap_contents[0];
                $soap_objective = $soap_contents[1];
                $soap_assessment = $soap_contents[2];
                $soap_plan = $soap_contents[3];
            ?>
            
            <div class = 'report-row-1'>
                <div class = 'soap-report'>
                    <div class="subjective-report">
                        <span>Subjective</span>
                        <textarea readonly><?php echo $soap_subjective ?></textarea>
                    </div>
                    <div class="objective-report">
                        <span>Objective</span>
                        <textarea readonly><?php echo $soap_objective ?></textarea>
                    </div>
                    <div class="assessment-report">
                        <span>Assessment</span>
                        <textarea readonly><?php echo $soap_assessment ?></textarea>
                    </div>
                    <div class="plan-report">
                        <span>Plan</span>
                        <textarea readonly><?php echo $soap_plan ?></textarea>
                    </div>
                </div>

                <div class = 'prescription-report'>
                    <?php
                        $query = "SELECT * FROM documents WHERE (sent_to = '$pid' AND doc_type = 'prescription') AND (DATE(date_uploaded) = DATE('$soap_date') AND TIME(date_uploaded) >= TIME('$soap_date')) ORDER BY date_uploaded LIMIT 1";

                        $result = mysqli_query($conn, $query);

                        if(mysqli_num_rows($result) > 0){
                            $base64 = '';
                            $file_ext = "";
    
                            while ($row = mysqli_fetch_array($result)) {
                                $base64 = $row['pdf_file'];
                                $file_ext = $row['file_ext'];
                            }
    
                            if ($file_ext == 'application/pdf') {
                                $base64 = !str_contains($base64, "data:application/pdf;base64,") ? "data:application/pdf;base64," . $base64 : $base64;
                                echo "<iframe src='$base64' type='$file_ext'></iframe>";
                            } else {
                                $base64 = !str_contains($base64, ";base64,") ? "data:$file_ext;base64," . $base64 : $base64;
                                echo "<div class = 'image-container'><img src='$base64'></div>";
                            }
                        }
                        else{
                            echo "<h2>No Prescriptions Found</h2>";
                        }

                        ?>
                </div>
            </div>

            <div class = 'labresult-report'>
                    <?php
                        $query = "SELECT * FROM documents WHERE (sent_to = '$pid' AND doc_type = 'labresult') AND (DATE(date_uploaded) = DATE('$soap_date') AND TIME(date_uploaded) >= TIME('$soap_date')) ORDER BY date_uploaded LIMIT 1";
                        
                        $result = mysqli_query($conn, $query);

                        if(mysqli_num_rows($result) > 0){
                            $base64 = '';
                            $file_ext = "";
    
                            while ($row = mysqli_fetch_array($result)) {
                                $base64 = $row['pdf_file'];
                                $file_ext = $row['file_ext'];
                            }
    
                            if ($file_ext == 'application/pdf') {
                                $base64 = !str_contains($base64, "data:application/pdf;base64,") ? "data:application/pdf;base64," . $base64 : $base64;
                                echo "<iframe src='$base64' type='$file_ext'></iframe>";
                            } else {
                                $base64 = !str_contains($base64, ";base64,") ? "data:$file_ext;base64," . $base64 : $base64;
                                echo "<div class = 'image-container'><img src='$base64'></div>";
                            }
                        }
                        else{
                            echo "<h2>No Lab Results Found</h2>";
                        }

                        ?>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/patient-list.js'></script>
<script src="js/notification-doc.js"></script>

</html>