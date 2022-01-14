<?php

include 'db_conn.php';

session_start();

$curr_emp_code = $_POST['currEmpCode'];

$result = mysqli_query($conn, "SELECT emp_code FROM employee_code");
$row = mysqli_fetch_array($result);

echo $row['emp_code'] == $curr_emp_code ? "emp code correct" : "emp code incorrect";
