<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <link rel = 'stylesheet' type = 'text/css' href = 'css/navbar.css'>
    <link rel = 'stylesheet' type = 'text/css' href = 'css/profile.css'>
    <link rel = 'stylesheet' tyype = 'text/css' href= 'css/employee-contents.css'>
    <title>Document</title>
</head>
<body>
    <?php include 'extras/employee-navbar.php'?>
    <?php include 'extras/profile.php' ?>

    <div class = 'background-container'></div>

    <div class = 'employee-contents' id = 'con'>
        <!--WORK DOCUMENTS-->
        <div class = 'e-contents-header-app'>
            <div class = 'document-header-btn'>
                <h1>DOCUMENTS</h1>
                <a href = 'extras/prescription.html' target = "_blank">
                    <button><i class="fas fa-upload"></i></button>
                </a> 
            </div>
            <h2>Prescriptions</h2>
        </div>

        <div class = 'e-contents-table'>
            <div class = 'e-contents-header-table'>
                <span>Filename</span>
                <span>Patient</span>
                <span>Date Uploaded</span>
                <span></span>
            </div>

            <!-- TABLE CONTENTS -->
            
            <div class = 'e-contents'>
                <span>190298_3721367.pdf</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021</span>
                <div>
                    <a class = 'view'><button>View</button></a>
                    <a><button><i class="fas fa-download"></i></button></a>
                </div>
            </div>
        </div>



        <!--APPOINTMENT HISTORY-->
        <div class = 'e-contents-header'>
            <h2>Laboratory Tests</h2>
        </div>

        <div class = 'e-contents-table'>
            <div class = 'e-contents-header-table'>
                <span>Filename</span>
                <span>Patient</span>
                <span>Date Uploaded</span>
                <span></span>
            </div>

            <!-- TABLE CONTENTS -->

            <div class = 'e-contents'>
                <span>190298_3721367.pdf</span>
                <span>Marc Dwain Magracia</span>
                <span>19/03/2021</span>
                <div>
                    <a class = 'view'><button>View</button></a>
                    <a><button><i class="fas fa-download"></i></button></a>
                </div>
            </div>
        </div>
    </div>
</body>
<script src = 'js/navbar.js'></script>
</html>