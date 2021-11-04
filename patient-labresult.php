<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = 'stylesheet' href = 'css/navbar.css'>
    <link rel = 'stylesheet' href = 'css/profile.css'>
    <link rel = 'stylesheet' href = 'css/transaction.css'>
    <script
    src="https://code.jquery.com/jquery-3.6.0.js"
    integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
    crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <title>Document</title>
</head>
<body>
    <?php include 'extras/navbar.php'?>

    <div class = 'background-container'>
        <?php include 'extras/profile.php' ?>
        
        <div class = 'contents'>
            <div class = 'transaction-container'>
                <span class = 'transaction-header'>Lab Test No. 02893217312</span>
                <div class = 'transaction-details'>
                    <!--PDF VIEWER HERE-->
                    <div class = 'pdf-viewer'>
                        PDF VIEWER HERE (LAB RESULT)
                    </div>
                    <button>Download Copy</button>
                </div>
            </div>
        </div>

    </div>
</body>
<script src = 'js/navbar.js'></script>
</html>