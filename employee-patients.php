<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/profile.css'>
    <link rel='stylesheet' tyype='text/css' href='css/employee-contents.css'>
    <title>Document</title>
</head>

<body>
    <?php include 'extras/employee-navbar.php' ?>
    <?php include 'extras/profile.php' ?>

    <div class='background-container'></div>

    <div class='employee-contents'>

        <!--PATIENTS' INFORMATION-->
        <div class='e-contents-header'>
            <h2>Your Patients</h2>
            <input type='text' id='search-patient' placeholder='Enter Patient Name'>
        </div>

        <div class='e-contents-table-patients'>
            <div class='e-contents-header-table-patients'>
                <span>Patient Name</span>
                <span>Sex</span>
                <span>Age</span>
                <span>Contact No.</span>
                <span></span>
            </div>

            <!-- TABLE CONTENTS -->
            <div class='patient-tbl'>
                <?php
                include 'php_processes/db_conn.php';

                $query = "SELECT * FROM user_table";

                $result = mysqli_query($conn, $query);

                while ($row = mysqli_fetch_array($result)) {
                    $lastname = $row['last_name'];
                    $middlename = substr($row['middle_name'], 0, 1);
                    $firstname = $row['first_name'];
                    $fullname = "$firstname $middlename. $lastname";
                    $sex = strtoupper(substr($row['sex'], 0, 1));
                    $contact = $row['contact_num'];

                    $birthdate = $row['birthdate'];
                    $from = new DateTime($birthdate);
                    $to = new DateTime('today');
                    $age = $from->diff($to)->y;


                    echo "
                        <div class='e-contents-patients'>
                            <span>$fullname</span>
                            <span>$sex</span>
                            <span>$age</span>
                            <span>$contact</span>
                            <div>
                                <a><button>Record</button></a>
                                <a><button>Progress</button></a>
                            </div>
                        </div>
                    ";
                }

                ?>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/patient-list.js'></script>

</html>