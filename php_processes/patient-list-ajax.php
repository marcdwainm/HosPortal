<?php
include 'db_conn.php';

$keyword = $_POST['keyword'];

$query = "SELECT * FROM user_table WHERE
     first_name LIKE '%" . $keyword . "%' OR
     middle_name LIKE '%" . $keyword . "%' OR
     last_name LIKE '%" . $keyword . "%' OR
     contact_num LIKE '%" . $keyword . "%'
     ";

$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $lastname = $row['last_name'];
    $middlename = substr($row['middle_name'], 0, 1);
    $firstname = $row['first_name'];
    $fullname = "$firstname $middlename. $lastname";
    $sex = strtoupper(substr($row['sex'], 0, 1));
    $contact = $row['contact_num'];

    $birthdate = $row['birthdate'];
    $from = new DateTime($birthdate);
    $to = new DateTime('today');
    $age = $from->diff($to)->y;

    echo "
        <div class='e-contents-patients'>
            <span>$fullname</span>
            <span>$sex</span>
            <span>$age</span>
            <span>$contact</span>
            <div>
                <a><button>Record</button></a>
                <a><button>Progress</button></a>
            </div>
        </div>
    ";
}

mysqli_close($conn);
