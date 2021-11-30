<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/profile.css'>
    <link rel='stylesheet' href='css/transaction.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>

<body>
    <?php

    include 'extras/employee-navbar.php';
    include 'extras/profile.php';

    $docnum = $_GET['docnum'];
    ?>

    <div class='background-container'>
    </div>


    <div class='contents'>
        <div class='transaction-container'>
            <span class='transaction-header'>Document No. <?php echo $docnum ?></span>
            <div class='transaction-details'>
                <!--PDF VIEWER HERE-->
                <div class='pdf-viewer'>
                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
                    $result = mysqli_query($conn, $query);
                    $base64 = '';

                    while ($row = mysqli_fetch_array($result)) {
                        $base64 = $row['pdf_file'];
                    }

                    if (!str_contains($base64, "data:application/pdf;base64,")) {
                        $base64 = "data:application/pdf;base64," . $base64;
                    }

                    echo "
                    <iframe src='$base64' type='application/pdf'></iframe>
                    ";
                    ?>
                </div>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>

</html>