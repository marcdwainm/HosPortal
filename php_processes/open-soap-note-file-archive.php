<?php

include 'db_conn.php';

$soap_id = $_POST['soap_id'];

$query = "SELECT * FROM archive_soap WHERE soap_id = '$soap_id'";
$result = mysqli_query($conn, $query);
$soap_note_content = "";

while ($row = mysqli_fetch_array($result)) {
    $soap_note_content = $row['soap_note'];
}

echo $soap_note_content;
