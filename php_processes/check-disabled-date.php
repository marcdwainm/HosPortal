<?php

include 'db_conn.php';

$date_dragged = $_POST['dateDragged'];

$query = "SELECT * FROM disabled_dates WHERE date = '$date_dragged'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    echo "disabled";
} else {
    echo "enabled";
}
