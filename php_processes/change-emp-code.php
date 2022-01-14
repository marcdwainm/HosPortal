<?php

include 'db_conn.php';
session_start();

$new_emp_code = $_POST['newEmpCode'];

mysqli_query($conn, "UPDATE employee_code SET emp_code = '$new_emp_code'");
mysqli_close($conn);
