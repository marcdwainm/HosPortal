<?php

include 'db_conn.php';

$arr = array();

if (isset($_POST['userid'])) {
    $userid = $_POST['userid'];
    $query = "SELECT * FROM user_table WHERE patient_id = $userid";
    $result = mysqli_query($conn, $query);
    while ($row = mysqli_fetch_array($result)) {
        $pid = $row['patient_id'];
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
        $contact = $row['contact_num'];
        $email = $row['email'];
        $sex = $row['sex'];

        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;

        $arr = array(
            "pid" => $pid,
            "fullname" => $fullname,
            "contact" => $contact,
            "email" => $email,
            "sex" => $sex,
            "age" => $age
        );
    }

    echo json_encode($arr);
}
