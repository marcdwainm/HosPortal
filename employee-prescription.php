<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel='stylesheet' href='css/navbar.css'>
    <link rel='stylesheet' href='css/view-doc.css'>
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <title>Twin Care Portal | View Document</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    $position = $_SESSION['position'];
    if ($position == 'nurse') {
        include 'extras/nurse-profile.php';
    } else {
        include 'extras/profile.php';
    }

    $position = $_SESSION['position'];
    if ($position != 'doctor' && $position != 'nurse') {
        header("Location: $position-homepage.php");
    }


    $docnum = $_GET['docnum'];
    ?>

    <div class='background-container'>

        <div class='contents'>
            <div class='container container-2'>
                <span class='header'>Document No. <?php echo $docnum ?></span>
                <div class='details'>
                    <!--PDF VIEWER HERE-->
                    <div class='pdf-viewer'>
                        <?php
                        include 'php_processes/db_conn.php';

                        $fromArchive = isset($_GET['fromArchive']) ? $_GET['fromArchive'] : "";
                        $fromOthers = isset($_GET['fromOthers']) ? $_GET['fromOthers'] : "";
                        $other = isset($_GET['other']) ? $_GET['other'] : "";

                        $query = "SELECT * FROM documents WHERE doc_num = '$docnum'";
                        if ($fromArchive == 'true') {
                            $query = "SELECT * FROM archive_documents WHERE doc_num = '$docnum'";
                        } else if ($fromOthers == 'true') {
                            $query = "SELECT * FROM other_documents WHERE docnum = '$docnum'";
                        } else if ($other == 'true') {
                            $query = "SELECT * FROM archive_other WHERE doc_num = '$docnum'";
                        }

                        $result = mysqli_query($conn, $query);

                        $base64 = '';
                        $file_ext = "";

                        while ($row = mysqli_fetch_array($result)) {
                            $base64 = $row['pdf_file'];
                            $file_ext = $row['file_ext'];
                        }

                        if ($file_ext == 'application/pdf') {
                            $base64 = !str_contains($base64, "data:application/pdf;base64,") ? "data:application/pdf;base64," . $base64 : $base64;
                            echo "<iframe src='$base64' type='$file_ext'></iframe>";
                        } else {
                            $base64 = !str_contains($base64, ";base64,") ? "data:$file_ext;base64," . $base64 : $base64;
                            echo "<div class = 'image-container'><img src='$base64'></div>";
                        }

                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>

</html>