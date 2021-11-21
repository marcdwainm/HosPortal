<?php

include 'db_conn.php';

$arr = array();

if (isset($_POST['userid'])) {
    $userid = $_POST['userid'];
    $query = "SELECT * FROM user_table WHERE patient_id = $userid";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
        $contact = $row['contact_num'];

        $arr = array(
            "fullname" => $fullname,
            "contact" => $contact
        );
    }

    echo json_encode($arr);
}
