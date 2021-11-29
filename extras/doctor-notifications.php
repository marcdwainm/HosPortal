<div class="notification-area">
    <div class="notification-box">
        <div class='notif-header'>
            <span>Notifications</span>
        </div>
        <div class="notif-contents">
            <!--10 People Have booked for appointments-->
            <?php

            include 'php_processes/db_conn.php';

            $query = "SELECT * FROM notifications WHERE date_booked > DATE_SUB(NOW(), INTERVAL '1' DAY)";

            $result = mysqli_query($conn, $query);
            $number = mysqli_num_rows($result) - 1;
            $arrayNames = array();

            while ($row = mysqli_fetch_array($result)) {
                $fullname = $row['patient_fullname'];

                if (!in_array($fullname, $arrayNames)) {
                    array_push($arrayNames, $fullname);
                }
            }

            if (sizeof($arrayNames) == 1) {
                echo "
                        <div class='notif-content book-notif-type'>
                            <div class='notif-img'></div>
                            <span>
                                $arrayNames[0] has booked for appointment
                            </span>
                            <div class='seen'>
                                <div class='seen-circle'></div>
                            </div> 
                        </div>
                    ";
            } else if (sizeof($arrayNames) == 0) {
                echo "<span class = 'no-new'>No New Notifications!</span>";
            } else {
                $size = sizeof($arrayNames) - 1;
                echo "
                        <div class='notif-content book-notif-type'>
                            <div class='notif-img'></div>
                            <span>
                                $arrayNames[0] and $size other/s has booked for appointment
                            </span>
                            <div class='seen'>
                                <div class='seen-circle'></div>
                            </div> 
                        </div>
                    ";
            }

            ?>


            <!-- A patient has settled a bill -->
            <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

            <!--A patient has requested an appointment reschedule. Accept?-->
            <!-- <div class="notif-content resched-notifgit type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain Magracia requested an appointment reschedule. Accept?
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

            <!--A patient has settled a bill-->
            <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->

            <!--A patient has settled a bill-->
            <!-- <div class="notif-content bill-notif-type">
                    <div class='notif-img'></div>
                    <span>
                        Marc Dwain B. Magracia has settled a bill
                    </span>
                    <div class="seen">
                        <div class="seen-circle"></div>
                    </div>
                </div> -->
        </div>
    </div>
    <div class="notification-num"><span>0</span></div>
    <div class="notification-btn">
        <i class="far fa-bell"></i>
    </div>
</div>