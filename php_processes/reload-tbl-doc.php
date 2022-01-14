<?php
include 'db_conn.php';

$type = $_POST['type'];
$class = "";

if ($type == 'pres') {
    $query = "SELECT * FROM documents WHERE doc_type = 'prescription' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";
    $class = "prescription";
} else if ($type == 'lab') {
    $query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5";
    $class = "labresult";
}


$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class = 'empty'>You don't have any Documents</div>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $doc_type = ucwords($row['doc_type']);
        $pname = $row['patient_name'];
        $date_uploaded = strtotime($row['date_uploaded']);
        $date_up_formatted = date('M d, Y h:i A', $date_uploaded);
        $doc_num = $row['doc_num'];

        echo "
            <div class='e-contents three-fr'>
                <span>$pname</span>
                <span>$date_up_formatted</span>
                <div>
                    <button class = 'view' value = '$doc_num'><i class='far fa-eye fa-lg'></i></button>
                    <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
                    <button class = 'archive-$class' value = '$doc_num'><i class='fas fa-archive'></i></button>
                </div>
            </div>
            ";
    }
}
