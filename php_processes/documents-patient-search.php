<?php
include 'db_conn.php';

if (isset(($_POST['query']))) {
    $output = '';
    $query = "SELECT * FROM user_table WHERE
     first_name LIKE '%" . $_POST['query'] . "%' OR
     middle_name LIKE '%" . $_POST['query'] . "%' OR
     last_name LIKE '%" . $_POST['query'] . "%' OR
    CONCAT(first_name, ' ', LEFT(middle_name, 1), '. ', last_name) LIKE '%" . $_POST['query'] . "%'";

    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            $lastname = $row['last_name'];
            $middlename = substr($row['middle_name'], 0, 1);
            $firstname = $row['first_name'];
            $sex = ucfirst($row['sex']);
            $birthdate = $row['birthdate'];
            $userid = $row['patient_id'];

            $from = new DateTime($birthdate);
            $to = new DateTime('today');
            $age = $from->diff($to)->y;

            $output .= "<button type = 'button' class='result-autocomplete' value = '$userid'>
                            <span>$firstname $middlename. $lastname</span>
                            <span>$sex, $age yrs</span>
                        </button>";
        }
    } else {
        $output = '';
    }

    echo $output;
}

mysqli_close($conn);
