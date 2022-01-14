<?php
include 'db_conn.php';
session_start();
$pid = $_SESSION['patientid'];
$type = $_POST['type'];

$offset = 0;

if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
}

if ($type == 'all') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($type == 'oldest') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' ORDER BY UNIX_TIMESTAMP(date_uploaded) ASC LIMIT $offset, 5";
} else if ($type == 'prescriptions') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($type == 'labresults') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($type == 'today') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND DATE(date_uploaded) = CURDATE() ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($type == 'thisweek') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND YEARWEEK(date_uploaded, 1) = YEARWEEK(CURDATE(), 1) ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
} else if ($type == 'thismonth') {
    $query = "SELECT * FROM documents WHERE sent_to = '$pid' AND MONTH(date_uploaded) = MONTH(CURRENT_DATE()) ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT $offset, 5";
}

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class = 'no-appointments'>Documents Empty</div>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $date = strtotime($row['date_uploaded']);
        $date_formatted = date('M d, Y h:i A', $date);
        $docnum = $row['doc_num'];
        $doctype = ucwords($row['doc_type']);

        if ($doctype == 'Labresult') {
            $doctype = "Lab Result";
        }

        echo "
            <div class='table-content three-fr'>
                <span>$doctype</span>
                <span>$date_formatted</span>
                <div class='table-btns2'>
                    <button class='details-btn' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                    <button class='download' value = '$docnum'><i class='fas fa-download'></i></button>
                </div>
            </div>
        ";
    }
}
