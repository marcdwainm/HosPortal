<?php

include 'db_conn.php';

$clicked = $_POST['clicked'];

$result = mysqli_query($conn, "SELECT * FROM bills WHERE bill_num = '$clicked'");

while ($row = mysqli_fetch_array($result)) {
    echo $row['total'];
}
