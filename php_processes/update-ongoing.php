<?php

include 'db_conn.php';
session_start();
$emp_id = $_SESSION['emp_id'];

$docnum = $_POST['doc_num'];
$pid = substr($docnum, -4);
$base64 = $_POST['base64'];
$file_ext = $_POST['file_ext'];

date_default_timezone_set('Asia/Manila');
$date_uploaded = date("Y-m-d H:i:s", time());

if($pid != '0000'){
    //GET USER
    $query = "SELECT * FROM user_table WHERE patient_id = '$pid'";
    $result = mysqli_query($conn, $query);
    $row = mysqli_fetch_array($result);
    $fullname = $row['first_name'] . " " . substr($row['middle_name'], 0, 1) . ". " . $row['last_name'];
    
    
    //GET BILL
    $query = "SELECT * FROM bills WHERE corresponding_doc = '$docnum'";
    $result = mysqli_query($conn, $query);
    $paid = "";
    if(mysqli_num_rows($result) > 0){
        $row = mysqli_fetch_array($result);
        $paid = $row['paid'];
    }
    else{
        $paid = '1';
    }
    
    //NOTIFY PATIENT
    $withBill = $paid == '1' ? '0' : '1';
    $query = "INSERT INTO patients_notifications (emp_id, patient_id, notif_type, document_num, date_notified, with_bill) 
    VALUES ('$emp_id', '$pid', 'labresult', '$docnum', '$date_uploaded', '$withBill')";
    mysqli_query($conn, $query);

    $wb = $withBill == '1' ? ' including a bill' : "";

    $result = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$pid'");
    $row = mysqli_fetch_array($result);

    $to = $row['email'];
    $subject = 'Twin Care Portal | Laboratory Result';
    $headers = "Good day, our dear patient!";
    $message = "Your lab results are out$wb. Kindly visit www.twincareportal.online to view your document.";

    mail($to, $subject, $message, $headers);
    
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext, paid) 
    VALUES ('$docnum', 'labresult', '$base64', '$pid', '$fullname', '$date_uploaded', '$emp_id', '$file_ext', '$paid')";
    mysqli_query($conn, $query);

    $query = "INSERT INTO documents_patient_copy (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext, paid) 
    VALUES ('$docnum', 'labresult', '$base64', '$pid', '$fullname', '$date_uploaded', '$emp_id', '$file_ext', '$paid')";
    mysqli_query($conn, $query);
}
else{
    $fullname = $_POST['fullname'];
    $query = "INSERT INTO documents (doc_num, doc_type, pdf_file, sent_to, patient_name, date_uploaded, emp_id, file_ext, paid) 
    VALUES ('$docnum', 'labresult', '$base64', '0000', '$fullname', '$date_uploaded', '$emp_id', '$file_ext', '1')";
    mysqli_query($conn, $query);
}


$query = "DELETE FROM lab_drafts WHERE doc_num = '$docnum'";
mysqli_query($conn, $query);


//UPDATE TABLE BELOW

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
            <div class = 'test'>
                <button class = 'view-document' value = '$docnum'><i class='fas fa-eye'></i></button>
                <button class = 'download-pdf' value = '$docnum'><i class='fas fa-download'></i></button>
            </div>
        </div>
    ";
    }
}

