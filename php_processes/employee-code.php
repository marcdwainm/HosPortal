<?php
    include 'db_conn.php';

    $code = $_GET['code'];

    $query = "SELECT * FROM employee_code WHERE emp_code = '$code'";

    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) > 0){
        echo "found";
    }
    else{
        echo "not found";
    }
    
?>