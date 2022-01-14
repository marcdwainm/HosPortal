<?php
include 'db_conn.php';

$query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 0, 5";

$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class = 'empty'>You don't have any uploads</div>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $pname = $row['patient_name'];
        $date_uploaded = $row['date_uploaded'];
        $docnum = $row['doc_num'];

        echo "
                <div class='e-contents three-fr'>
                    <span>$pname</span>
                    <span>$date_uploaded</span>
                    <div>
                        <button class = 'view-document' value = '$docnum'><i class='fas fa-eye'></i></button>
                        <button class = 'download-pdf' value = '$docnum'><i class='fas fa-download'></i></button>
                    </div>
                </div>
            ";
    }
}
