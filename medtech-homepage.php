<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.0.js" integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/f45be26f8c.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel='stylesheet' type='text/css' href='css/navbar.css'>
    <link rel='stylesheet' type='text/css' href='css/employee-contents.css'>
    <title>Twin Care Portal | Homepage</title>
    <link rel="icon" href="img/logo.png">
</head>

<body>
    <?php
    session_start();

    if (!isset($_SESSION['email'])) {
        header("Location: index.php");
    }

    $position = $_SESSION['position'];
    if ($position != 'medtech') {
        if ($postition == 'doctor') {
            header("Location: employee-homepage.php");
        } else {
            header("Location: $position-homepage.php");
        }
    }

    include 'extras/medtech-profile.php';
    ?>

    <div class='background-container'>

        <div class='add-document-overlay'></div>

        <div class="document-upload-container medtech-draft-container">
            <div class='document-upload-exit'>
                <span>Create a Draft</span>
                <span class='exit exit-create-draft'>X</span>
            </div>
            <form class='upload-document-content' method='GET'>
                <div class="p-name-documents">
                    <div class='portal-registered-checkbox'>
                        <div class='checkbox'>
                            <label for="portal-registered">Portal Registered Patient</label>
                            <input type="checkbox" name="portal-registered" id='portal-registered' value="p-registered">
                        </div>
                    </div>
                    <input type='text' id='patient-search' placeholder="Enter Patient's Name" autocomplete="off">
                    <div class="p-name-documents-autocomplete">
                        <!--Dynamic Results Here-->
                    </div>
                </div>
                <span id='patient-error'>Note: This patient is not portal-registered</span>
                <input type='text' placeholder='Enter Test Type' id='test-type'>
                <input type='text' placeholder='Date of Collection' id='collection-date' onfocus="(this.type='date')" onblur="(this.type='text')">
                <input type='text' placeholder='Estimated Date of Results' id='result-date' onfocus="(this.type='date')" onblur="(this.type='text')">
                <span class='patient-error2'>File is too big! Maximum size: 5MB</span>
                <div class='document-upload-btns one-btn'>
                    <button id='generate-document' name='submit' type='button' value='0000' disabled>Create Draft</button>
                </div>
            </form>
        </div>

        <div class="dim-4">
            <div class='issue-bill-container'>
                <div class='book-header-exit'>
                    <span>Bill Issuing</span>
                    <span class='exit' id='exit-issue'>X</span>
                </div>
                <div class="issue-details">

                </div>
                <div class="issue-fillup">
                    <button id='add-bill-item'>Add Item</button>
                    <div class="fill-up-fields">
                        <div class='fill-up-field'>
                            <input type='text' placeholder='Enter a description' class='fill-up-item'>
                            <input type='number' placeholder='Enter price' class='fill-up-price'>
                            <button class='delete-item'><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                </div>
                <div class="total-and-button">
                    <div>
                        <b>Total Due:</b>
                        <span>PHP</span>
                        <span class='total-price'>0.00</span>
                    </div>
                    <button type='button' id='issue-bill-medtech'>Issue Bill</button>
                </div>
            </div>
        </div>

        <div class="document-upload-container direct-upload-medtech">
            <div class='document-upload-exit'>
                <span>Direct Upload</span>
                <span class='exit exit-direct-upload'>X</span>
            </div>
            <form class='upload-document-content' method='GET'>
                <div class="p-name-documents">
                    <div class='portal-registered-checkbox'>
                        <div class='checkbox'>
                            <label for="portal-registered">Portal Registered Patient</label>
                            <input type="checkbox" name="portal-registered" id='portal-registered-2' value="p-registered">
                        </div>
                    </div>
                    <input type='text' id='patient-search-2' placeholder="Enter Patient's Name" autocomplete="off">
                    <div class="p-name-documents-autocomplete autocomplete-2">
                        <!--Dynamic Results Here-->
                    </div>
                </div>
                <span id='patient-error-2'>Note: This patient is not portal-registered</span>
                <input type='text' placeholder='Enter Test Type' id='test-type-2'>
                <input type="File" name="file" id='file' accept='application/pdf, image/jpeg, image/jpg, image/png'>
                <span class='patient-error2'>File is too big! Maximum size: 5MB</span>
                <div class='document-upload-btns'>
                    <button id='upload-from-device' type='button' value='0000' disabled>Upload from Device</button>
                    <button id='file-to-database' type='button' value='0000'>Upload File</button>
                    <span>OR</span>
                    <button id='generate-document-2' name='submit' type='submit' value='0000' disabled>Generate Document</button>
                </div>
            </form>
        </div>

        <div class='employee-contents' id='con'>
            <!--WORK DOCUMENTS-->
            <div class='e-contents-header-app header-margin-top'>
                <div class='document-header-btn'>
                    <h1>Tests</h1>
                    <div class='header-btn-btns'>
                        <button id='create-draft'>Create a Draft</button>
                        <button id='upload-document'>Direct Upload<i class="fas fa-upload"></i></button>
                    </div>
                </div>
                <h2>Pending</h2>
            </div>

            <div class='e-contents-table'>
                <div class='e-contents-header-table-docs six-fr'>
                    <span>Patient</span>
                    <span>Test Type</span>
                    <span>Status</span>
                    <span>Collection Date</span>
                    <span>Estimated Date of Results</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class="lab-tbl">

                    <?php
                    include 'php_processes/db_conn.php';
                    $emp_id = $_SESSION['emp_id'];
                    $query = "SELECT * FROM lab_drafts WHERE emp_id = '$emp_id' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'empty'>You don't have any drafts</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $pname = $row['patient_fullname'];
                            $test_type = $row['test_type'];
                            $status = ucwords($row['status']);
                            $doc_num = $row['doc_num'];
                            $collection_date = $row['collection_date'];
                            $estimated_result = $row['estimated_result'];
                            $textcolor = '';

                            switch ($status) {
                                case 'Pending':
                                    $textcolor = 'orange-text';
                                    break;
                            }

                            echo "
                            <div class='e-contents six-fr'>
                                <span>$pname</span>
                                <span>$test_type</span>
                                <span class = '$textcolor'>$status</span>
                                <span>$collection_date</span>
                                <span>$estimated_result</span>
                                <div class = 'medtech-btns'>
                                    <button class = 'edit-draft' value = '$doc_num'><i class='fas fa-edit'></i></button>
                                    <button class = 'delete-draft' value = '$doc_num'><i class='fas fa-trash'></i></button>
                                </div>
                            </div>
                        ";
                        }
                    }

                    ?>

                </div>
            </div>

            <div class='e-contents-header-app'>
                <h2>Uploads</h2>
            </div>

            <div class='e-contents-table'>
                <div class='e-contents-header-table-docs three-fr'>
                    <span>Patient</span>
                    <span>Date Uploaded</span>
                    <span></span>
                </div>

                <!-- TABLE CONTENTS -->
                <div class="lab-tbl-2">

                    <?php
                    include 'php_processes/db_conn.php';

                    $query = "SELECT * FROM documents WHERE doc_type = 'labresult' ORDER BY UNIX_TIMESTAMP(date_uploaded) DESC LIMIT 0, 5";

                    $result = mysqli_query($conn, $query);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class = 'empty'>You don't have any uploads</div>";
                    } else {
                        while ($row = mysqli_fetch_array($result)) {
                            $pname = $row['patient_name'];
                            $date_uploaded = $row['date_uploaded'];
                            $docnum = $row['doc_num'];

                            echo "
                            <div class='e-contents three-fr'>
                                <span>$pname</span>
                                <span>$date_uploaded</span>
                                <div>
                                    <button class = 'view-document' value = '$docnum'><i class='fas fa-eye'></i></button>
                                    <button class = 'download-pdf' value = '$docnum'><i class='fas fa-download'></i></button>
                                </div>
                            </div>
                        ";
                        }
                    }
                    ?>

                </div>
            </div>
            <div class='reload-all'>
                <div>
                    <button id='prev'><i class="fas fa-arrow-left fa-lg"></i></button>
                    <span id='page-num'>1</span>
                    <span id='offset'>0</span>
                    <button id='next'><i class="fas fa-arrow-right fa-lg"></i></button>
                </div>
                <button id='reload-uploads'>Reload Table</button>
            </div>
        </div>
    </div>
</body>
<script src='js/navbar.js'></script>
<script src='js/medtech.js'></script>
<script src='js/billing.js'></script>

</html>