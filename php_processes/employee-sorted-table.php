<?php
include 'db_conn.php';

$selected = $_GET['selected'];

if ($selected == 'today') {
    $query = "SELECT * FROM appointments WHERE date(date_and_time) = CURDATE()";
} else if ($selected == 'upcoming') {
    $query = "SELECT * FROM appointments WHERE date_and_time >= ( CURDATE() + INTERVAL 3 DAY)";
} else if ($selected == 'recent') {
    $query = "SELECT * FROM appointments WHERE date_and_time >= ( CURDATE() - INTERVAL 3 DAY)";
} else if ($selected == 'all') {
    $query = "SELECT * FROM appointments";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $appointmentnum = $row['appointment_num'];
        $fullname = $row['patient_fullname'];
        $datetime = $row['date_and_time'];
        $dt = new DateTime($datetime);

        $date = $dt->format('F j, Y l');
        $time = $dt->format('h:i A');

        echo "
            <div class='e-contents'>
                <span>$appointmentnum</span>
                <span>$fullname</span>
                <span>$datetime</span>
                <span class = 'e-num'>
                    0998390813
                    <button><i class='fas fa-ellipsis-v'></i></button>
                </span>
                <form class = 'dropdown' target = 'dummyframe'>
                    <button type = 'button' class = 'cancel-appointment' value = '$appointmentnum'>Cancel Appointment</button>
                </form>
            </div>
        ";
    }
} else {
    echo '
        <span class = "no-appointments">No appointments found</span>
    ';
}
