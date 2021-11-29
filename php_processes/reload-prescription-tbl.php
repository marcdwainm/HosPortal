<?php
include 'db_conn.php';

$query = "
    SELECT * FROM documents ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 5 
";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $doc_type = ucwords($row['doc_type']);
    $pname = $row['patient_name'];
    $date_uploaded = $row['date_uploaded'];
    $doc_num = $row['doc_num'];

    echo "
    <div class='e-contents three-fr'>
        <span>$doc_type</span>
        <span>$pname</span>
        <span>$date_uploaded</span>
        <div>
            <button class = 'view' value = '$doc_num'>View</button>
            <button class = 'download-pdf' value = '$doc_num'><i class='fas fa-download'></i></button>
        </div>
    </div>
";
}
