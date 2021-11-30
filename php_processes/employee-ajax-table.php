<?php

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
    echo "<span class = 'no-appointments'>No Appointments Found</span>";
}
