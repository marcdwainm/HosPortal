<?php

include 'db_conn.php';

$inputFname = ucwords($_POST['inputFname']);
$inputMname = ucwords($_POST['inputMname']);
$inputLname = ucwords($_POST['inputLname']);
$inputContact = $_POST['inputContact'];
$inputGender = $_POST['inputGender'];
$inputBirthdate = $_POST['inputBirthdate'];
$inputAddress = ucwords($_POST['inputAddress']);

$query = "INSERT INTO user_table (`first_name`, `middle_name`, `last_name`, `contact_num`, `sex`, `birthdate`, `address`) 
VALUES ('$inputFname', '$inputMname', '$inputLname', '$inputContact', '$inputGender', '$inputBirthdate', '$inputAddress')";

mysqli_query($conn, $query);

mysqli_close($conn);
