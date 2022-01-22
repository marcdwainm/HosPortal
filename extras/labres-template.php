<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.js"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='../css/prescription.css'>
    <title>Twin Care Portal | Document Generator</title>
    <link rel="icon" href="../  img/logo.png">
</head>

<body>
    <div class="invisible-params" style="display: none"><?php echo $_POST['pid'] . ", " . $_POST['pname'] . ", " . $_POST['docnum']; ?></div>

    <iframe name='dummyframe' id='dummyframe' style='display: none;'></iframe>

    <div id='signature-adder-dim'>
        <div id='signature-adder-container'>
            <div id="signature-adder-header">
                <span>Add a signature</span>
                <span class='signature-adder-exit'>X</span>
            </div>

            <div id="signature-adder-body">
                <canvas id='signature-canvas'>
                </canvas>

                <div class='signature-adder-buttons'>
                    <button id='clear-signature'>Clear</button>
                    <button id='add-to-document-signature'>Add to Document</button>
                    <input id='upload-image-signature' type='file' accept="image/png, image/jpeg, image/jpg" />
                    <button id='upload-signature'>Upload Image</button>
                </div>
            </div>
        </div>
    </div>

    <div class='buttons'>
        <div class='buttons-edit'>
            <button id='add-head-lab-medtech' class='add-head-lab'><i class='far fa-plus-square'></i> Add Head</button>
            <button id='add-div-lab-medtech' class='add-div-lab'><i class='far fa-plus-square'></i> Add Test</button>
            <button id='save-draft'>Save Draft</button>
        </div>

        <div class='buttons-download'>
            <button id='download' class='btns' style="display:none">Upload</button>
            <button id='upload-medtech' class='btns'>Upload</button>
            <button id='copy' class='btns'>Download a Copy</button>
            <button id='print' class='btns'>Print</button>
        </div>
    </div>

    <div class="wrapper">
        <?php
        $html = base64_decode($_POST['content']);
        echo $html;
        ?>
    </div>
</body>
<script src='../js/upload-medtech.js'></script>
<script src='../js/pdf-generator.js'></script>

</html>