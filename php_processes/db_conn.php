<?php

$host = 'localhost';
$username = 'root';
$password = 'root';
$database = 'twincare_db';

// $host = 'localhost';
// $username = 'u991711642_twincareportal';
// $password = 'TwinCarePortal_0';
// $database = 'u991711642_twincareportal';

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    echo $conn->connect_error;
}
