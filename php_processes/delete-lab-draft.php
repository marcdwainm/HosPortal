<?php

include 'db_conn.php';

$docnum = $_POST['docnum'];

$query = "DELETE FROM lab_drafts WHERE doc_num = '$docnum'";
mysqli_query($conn, $query);

$query = "SELECT * FROM lab_drafts ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) == 0) {
    echo "<div class = 'empty'>You don't have any drafts</div>";
} else {
    while ($row = mysqli_fetch_array($result)) {
        $pname = $row['patient_fullname'];
        $test_type = $row['test_type'];
        $status = ucwords($row['status']);
        $doc_num = $row['doc_num'];
        $collection_date = $row['collection_date'];
        $estimated_result = $row['estimated_result'];
        $textcolor = '';

        switch ($status) {
            case 'Pending':
                $textcolor = 'orange-text';
                break;
        }

        echo "
            <div class='e-contents six-fr'>
                <span>$pname</span>
                <span>$test_type</span>
                <span class = '$textcolor'>$status</span>
                <span>$collection_date</span>
                <span>$estimated_result</span>
                <div class = 'medtech-btns'>
                    <button class = 'edit-draft' value = '$doc_num'><i class='fas fa-edit'></i></button>
                    <button class = 'delete-draft' value = '$doc_num'><i class='fas fa-trash'></i></button>
                </div>
            </div>
        ";
    }
}
