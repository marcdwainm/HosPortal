<?php

include 'db_conn.php';

$docnum = $_POST['docnum'];
$doctype = $_POST['doctype'];
$pid = $_POST['pid'];

$query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
$result = mysqli_query($conn, $query);

$pdf_file = "";
$sent_to = "";
$patient_name = "";
$date_uploaded = "";
$emp_id = "";
$file_ext = "";

while ($row = mysqli_fetch_array($result)) {
    $pdf_file = $row['pdf_file'];
    $sent_to = $row['sent_to'];
    $patient_name = $row['patient_name'];
    $date_uploaded = $row['date_uploaded'];
    $emp_id = $row['emp_id'];
    $file_ext = $row['file_ext'];
}

date_default_timezone_set('Asia/Manila');
$curr_date = date("Y-m-d H:i:s", time());

//PUT INTO DOCUMENT ARCHIVE
if ($doctype == 'prescription') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext)
    VALUES ('$docnum', 'prescription', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);
} else if ($doctype == 'labresult') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext) 
        VALUES ('$docnum', 'labresult', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);
}

//DELETE FROM DOCUMENT TABLE
$query = "DELETE FROM documents WHERE doc_num = '$docnum'";
mysqli_query($conn, $query);


if ($doctype == 'prescription') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext)
    VALUES ('$docnum', 'prescription', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);

    $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'prescription'";
    $result2 = mysqli_query($conn, $query2);

    if(mysqli_num_rows($result2) > 0){
        while($row = mysqli_fetch_array($result2)){
            $docnum = $row['doc_num'];
            $date_uploaded = strtotime($row['date_uploaded']);
            $date_uploaded = date("M d, Y / h:i A", $date_uploaded);

            echo "  
                <div class = 'other-docs-table-content'>
                    <span>$date_uploaded</span>
                    <div>
                        <div class = 'soap-btns'>
                            <button class = 'view-doc' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                            <button class = 'archive-presc' value = '$docnum'><i class='fas fa-archive fa-lg'></i></button>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    else{
        echo '<span class = "no-appointments font-size-bigger">No records yet</span>';
    }
} else if ($doctype == 'labresult') {
    $query = "INSERT INTO archive_documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, date_archived, emp_id, file_ext) 
        VALUES ('$docnum', 'labresult', '$pdf_file', '$sent_to', '$patient_name', '$date_uploaded', '$curr_date', '$emp_id', '$file_ext')";
    mysqli_query($conn, $query);

    $query2 = "SELECT * FROM documents WHERE sent_to = '$pid' AND doc_type = 'labresult'";
    $result2 = mysqli_query($conn, $query2);
        
    if(mysqli_num_rows($result2) > 0){
        while($row = mysqli_fetch_array($result2)){
            $docnum = $row['doc_num'];
            $date_uploaded = strtotime($row['date_uploaded']);
            $date_uploaded = date("M d, Y / h:i A", $date_uploaded);

            echo "  
                <div class = 'other-docs-table-content'>
                    <span>$date_uploaded</span>
                    <div>
                        <div class = 'soap-btns'>
                            <button class = 'view-doc' value = '$docnum'><i class='far fa-eye fa-lg'></i></button>
                            <button class = 'archive-lab' value = '$docnum'><i class='fas fa-archive fa-lg'></i></button>
                        </div>
                    </div>
                </div>
            ";
        }
    }
    else{
        echo '<span class = "no-appointments font-size-bigger">No records yet</span>';
    }
}