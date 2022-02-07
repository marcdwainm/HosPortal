<?php

include 'db_conn.php';

$pid = $_POST['pid'];

$query_nest = "SELECT * FROM soap_notes WHERE patient_id = '$pid' ORDER BY UNIX_TIMESTAMP(date_created) DESC";
$result2 = mysqli_query($conn, $query_nest);

if (mysqli_num_rows($result2) > 0) {
    while ($row = mysqli_fetch_array($result2)) {
        $date_time = $row['appointment_date_time'];
        $date_time = strtotime($date_time);
        $date_time = date("M d, Y / h:i A", $date_time);
        $date_created = $row['date_created'];
        $date_created = strtotime($date_created);
        $date_created = date("M d, Y / h:i A", $date_created);
        $app_num = $row['appointment_num'];
        $soap_id = $row['soap_id'];

        echo "
                <div class = 'soap-table-content'>
                    <span>$date_created</span>
                    <span>$date_time</span>
                    <div>
                        <div class = 'soap-btns'>
                            <button class = 'view-soap' value = '$soap_id'><i class='far fa-eye fa-lg'></i></button>
                            <button class = 'edit-soap' value = '$soap_id' ><i class='far fa-edit fa-lg'></i></button>
                            <button class = 'archive-soap' value = '$soap_id'><i class='fas fa-archive fa-lg'></i></button>
                        </div>
                    </div>
                </div>
                "; 
    }
} else {
    echo '
        <span class = "no-appointments font-size-bigger">No records yet</span>
    ';
}
