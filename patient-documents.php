<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/profile.css'>
    <link rel='stylesheet' href='css/contents.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <?php

    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    include 'extras/navbar.php';
    include 'extras/profile.php';

    ?>

    <div class='background-container'></div>

    <div class='contents'>
        <h1>DOCUMENTS</h1>
        <div class='document-container'>
            <h3 class='header-table'>
                <span>Lab Results</span>
            </h3>

            <div class='table'>
                <!--LAB RESULT TABLE HEADER-->
                <div class='labresult-header'>
                    <span>Collection Date</span>
                    <span>Estimated Date of Result</span>
                    <span>Test</span>
                    <span>Status</span>
                    <span></span>
                </div>

                <!--LAB RESULT TABLE CONTENTS-->
                <div class='table-content-labresult'>
                    <span>Dec 12, 2020</span>
                    <span>12/02/2021</span>
                    <span>Urinalysis</span>
                    <span>Pending</span>
                    <div class='table-btns'>
                        <a href='patient-labresult.php'>
                            <button class='details-btn'>Details</button>
                        </a>
                        <button class='download'><i class="fas fa-download"></i></button>
                    </div>
                </div>



            </div>
        </div>





        <div class='appointment-container'>
            <h3 class='header-table'>
                <span>Prescriptions</span>
            </h3>

            <div class='table'>
                <!--PRESCRIPTIONS TABLE HEADER-->
                <div class='table-header'>
                    <span>Collection Date</span>
                    <span>Estimated Date of Result</span>
                    <span></span>
                </div>

                <!--PRESCRIPTIONS TABLE CONTENTS-->


                <div class='table-content'>
                    <span>021382372.pdf</span>
                    <span>12/02/2021</span>
                    <div class='table-btns2'>
                        <a href='patient-prescription.php'>
                            <button class='details-btn'>Details</button>
                        </a>
                        <button class='download'><i class="fas fa-download"></i></button>
                    </div>
                </div>


            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/book-appointment.js'></script>

</html>