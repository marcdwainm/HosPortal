<?php
include '../db_conn.php';
if (isset($_POST["title"])) {
    $title = $_POST['title'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    $sql = "INSERT INTO appointments(title, start_event, end_event) VALUES ('$title','$start','$end')";
    $conn->query($sql);
}
