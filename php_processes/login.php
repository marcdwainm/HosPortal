<?php
session_start();

$email = $_POST['login-email'];
$pass = $_POST['login-pass'];

$required_fields = array('login-email', 'login-pass');
$error = false;

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $error = true;
    }
}

if ($error) {
    header("Location: ../index.php?fieldslogin=empty&loginemail=$email");
    exit();
}


//IF EMAIL INPUT IS NOT AN EMAIL
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    header("Location: ../index.php?emaillogin=invalid&loginemail=$email");
    exit();
}

//IF NOTHING IS WRONG WITH INPUTS
else {
    include 'db_conn.php';

    $query = "SELECT * FROM user_table WHERE email = '$email'";
    $query2 = "SELECT * FROM employee_table WHERE email = '$email'";

    $result = mysqli_query($conn, $query);
    $result2 = mysqli_query($conn, $query2);

    if (mysqli_num_rows($result) > 0 || mysqli_num_rows($result2) > 0) {
        while ($row = mysqli_fetch_array($result)) {
            if (password_verify($pass, $row['password'])) {

                $fname = $row['first_name'];
                $mname = substr($row['middle_name'], 0, 1) . '.';
                $lname = $row['last_name'];

                $_SESSION['email'] = $email;
                $_SESSION['fullname'] = "$fname $mname $lname";
                $_SESSION['patientid'] = $row['patient_id'];
                $_SESSION['contact'] = $row['contact_num'];
                $_SESSION['position'] = 'patient';

                //SET STATUS AS LOGIN
                $patient_id = $row['patient_id'];
                $q = "UPDATE user_table SET online = '1' WHERE patient_id = '$patient_id'";
                mysqli_query($conn, $q);

                header('location: ../patient-homepage.php');
            } else {
                header("Location: ../index.php?acc=notfound&loginemail=$email");
            }
        }

        while ($row = mysqli_fetch_array($result2)) {
            if (password_verify($pass, $row['password'])) {
                $fname = $row['first_name'];
                $mname = substr($row['middle_name'], 0, 1) . '.';
                $lname = $row['last_name'];

                $_SESSION['email'] = $email;
                $_SESSION['fullname'] = "$fname $mname $lname";
                $_SESSION['contact'] = $row['contact_num'];
                $_SESSION['position'] = $row['position'];
                $_SESSION['emp_id'] = $row['employee_id'];

                if ($_SESSION['position'] == 'doctor') {
                    header('location: ../employee-homepage.php');
                } else if ($_SESSION['position'] == 'medtech') {
                    header('location: ../medtech-homepage.php');
                } else if ($_SESSION['position'] == 'nurse') {
                    header('location: ../nurse-homepage.php');
                }
            } else {
                header("Location: ../index.php?acc=notfound&loginemail=$email");
            }
        }
    } else {
        header("Location: ../index.php?acc=notfound&loginemail=$email");
    }
}
