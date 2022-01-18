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
    <link rel='stylesheet' type='text/css' href='../css/employee-contents.css'>
    <?php
    if ($_GET['docType'] == 'prescription') {
        echo "<link rel='stylesheet' type='text/css' href='../css/prescription.css'>";
    } else if ($_GET['docType'] == 'labresult') {
        echo "<link rel='stylesheet' type='text/css' href='../css/labresult.css'>";
    }
    ?>
    <title></title>
</head>

<body>
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

    <?php
    session_start();
    include '../php_processes/db_conn.php';

    $userid = $_GET['pid'];
    $patient_name = '';
    $patient_age = '';
    $date_today = '';
    $sex = '';
    $address = '';
    $test_type = isset($_GET['testType']) ? $_GET['testType'] : "TEST RESULTS";

    $query = "SELECT * FROM user_table WHERE patient_id = $userid";
    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $lastname = $row['last_name'];
        $middlename = substr($row['middle_name'], 0, 1);
        $firstname = $row['first_name'];
        $fullname = "$firstname $middlename. $lastname";
        $contact = $row['contact_num'];
        $sex = ucfirst($row['sex']);
        $address = $row['address'];

        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;

        $patient_name = $fullname;
        $patient_age = $age;
        $date_today = $to->format('m/d/Y');
    }

    if ($_GET['pid'] == '0000') {
        $patient_name = ucwords($_GET['pname']);
        $patient_age = 'Unspecified';
        $to = new DateTime('today');
        $date_today = $to->format('m/d/Y');
        $sex = 'Unspecified';
        $address = 'Unspecified';
    }

    ?>

    <?php
    // IF DOCUMENT TYPE IS PRESCRIPTION
    if ($_GET['docType'] == 'prescription') {
        $fullname_doctor = $_SESSION['fullname'];
        echo "
            <iframe name='dummyframe' id='dummyframe' style='display: none;'></iframe>
            <div class='buttons'>
                <div class='buttons-edit'>
                    <button id='add-div'><i class='far fa-plus-square'></i> Add Drug</button>
                    <button id='revert'><i class='fas fa-trash-alt'></i> Revert</button>
                </div>

                <div class='buttons-download'>
                    <button id='download' class='btns'>Upload</button>
                    <button id='copy' class='btns'>Download a Copy</button>
                    <button id='print' class='btns'>Print</button>
                </div>
            </div>
            <div class='container-for-prescription'>
                <div class='prescription-container' id='invoice'>
                <div class = 'pres-header'>
                    <div class = 'pres-img-logo'>
                        <img src = '../img/logo.png' />
                    </div>
                    <div>
                        <h2>TWIN CARE MEDICAL CLINIC and DIAGNOSTIC LABORATORY</h2>
                        <h4>Tapatan Road, Marungko, Angat, Bulacan</h4>
                        <span>\"Because we give the best healthcare you deserve\"</span>
                        <div class = 'black'>
                            <span>Clinic Schedule: Monday -- Saturday 9:00am - 1:00pm</span>
                            <span>Mobile No. 0925-734-7552</span>
                        </div>
                    </div>
                </div>

                <div class = 'pres-doctor-details'>
                    <div>
                        <b contenteditable = 'true'>PILAR D. TORRES-SALVADOR, MD, FPCP, FPSEDM</b><br />
                        <div>
                            <i contenteditable = 'true'>Internal Medicine-</i>
                            <b contenteditable = 'true'>ENDICRONOLOGIST</b>
                        </div>
                    </div>
                    <div>
                        <b contenteditable = 'true'>ALBERT SALVADOR, MD</b><br/>
                        <i contenteditable = 'true'>Internal Medicine</i>
                    </div>
                </div>

                <div class = 'hospital-aff'>
                    <u>Hospital Affliations:</u>
                    <i>Castro Maternity Hospital & Medical Center; ACE Medical Center; Sto. Nino Hospital; Marcelo Hospital; Rugay General Hospital</i>
                </div>

                <div class='patient-info'>
                    <hr />
                    <div class='name-age'>
                        <div class='name-container'>
                            <span>Patient Name: </span>
                            <span contenteditable='true' class='underlined' id='name'>$patient_name</span>
                        </div>
                        <div class='age-container'>
                            <span>Age: </span>
                            <span contenteditable='true' class='underlined' id='age'>$patient_age</span>
                        </div>
                    </div>
                    <div class='address-date'>
                        <div class='address-container'>
                            <span>Address: </span>
                            <span contenteditable='true' class='underlined' id='address'>$address</span>
                        </div>
                        <div class='date-container'>
                            <span>Date: </span>
                            <span contenteditable='true' class='underlined' id='date'>$date_today</span>
                        </div>
                    </div>
                </div>

                    <div class='medicine'>
                        <img src='../img/rx.png'>
                        <div class='medicine-table' id='medicine-table'>
                            <div class='medicine-table-header'>
                                <span class='left'>Drug</span>
                                <span class='center'>Amount</span>
                                <span class='right'>Frequency</span>
                            </div>

                            <div class='medicine-table-content' id='prescription-item'>
                                <span contenteditable='true' class='left'>Drug Name</span>
                                <span contenteditable='true' class='center'>0mg</span>
                                <span contenteditable='true' class='right'>Sample Frequency</span>
                                <button class = 'delete-div'>Delete</button>
                            </div>
                        </div>
                    </div>

                    <div class='footer'>
                        <div>
                            <span>Next Follow up: </span>
                            <span contenteditable = 'true' class = 'underlined' id = 'follow-up'></span>
                        </div>
                        <div>
                            <div>
                                <div class = 'e-sig-presc-div'>
                                    <div class = 'e-sig e-sig-1 e-sig-presc'>
                                    
                                    </div>
                                </div>
                                <span contenteditable = 'true' class = 'underlined' id = 'doctor'>$fullname_doctor</span>
                                <span>, M.D.</span>
                            </div>
                            <div>
                                <span>Lic. No. </span>
                                <span contenteditable = 'true' class = 'underlined' id = 'licNo'>101888</span>
                            </div>
                            <div>
                                <span>PTR No. </span>
                                <span contenteditable = 'true' class = 'underlined' id = 'ptrNo'>114283</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            ";
        // ELSE IF DOCUMENT IS A LABRESULT
    } else if ($_GET['docType'] == 'labresult') {
        $fullname_doctor = $_SESSION['fullname'];
        echo "
            <iframe name='dummyframe' id='dummyframe' style='display: none;'></iframe>
            <div class='buttons'>
                <div class='buttons-edit'>
                    <button id='add-head-lab' class = 'add-head-lab'><i class='far fa-plus-square'></i> Add Head</button>
                    <button id='add-div-lab' class = 'add-div-lab'><i class='far fa-plus-square'></i> Add Test</button>
                    <button id='revert-lab'><i class='fas fa-trash-alt'></i> Revert</button>
                </div>

                <div class='buttons-download'>
                    <button class='btns upload-with-bill'>Upload</button>
                    <button id='copy' class='btns'>Download a Copy</button>
                    <button id='print' class='btns'>Print</button>
                </div>
            </div>


            <div class='container-for-labresult'>
                <div class='labresult-container' id='invoice'>  
                    <div class = 'lab-header'>
                        <div>
                            <div class = 'lab-logo'>
                                <img src= '../img/logo.png' />
                            </div>
                            <div class = 'lab-clinic-name'>
                                <h2>TWIN CARE MEDICAL CLINIC and DIAGNOSTIC LABORATORY</h2>
                            </div>
                        </div>
                    </div>
                    <h4>02 Tapatan Road, Sulucan 3012, Angat, Bulacan</h4>
                    <br />
                    <div class='patient-info'>
                        <div class='date-container-2'>
                            <span>Date: </span>
                            <span contenteditable='true' class='underlined' id='date'>$date_today</span>
                        </div>
                        <div class='name-container'>
                            <span>Patient Name: </span>
                            <span contenteditable='true' class='underlined' id='name'>$patient_name</span>
                        </div>
                        <div class='address-container'>
                            <span>Address: </span>
                            <span contenteditable='true' class='underlined' id='address'>$address</span>
                        </div>
                        <div class='gender-container'>
                            <span>Sex: </span>
                            <span contenteditable='true' class='underlined' id='sex'>$sex</span>
                        </div>
                        <div class='age-container'>
                            <span>Age: </span>
                            <span contenteditable='true' class='underlined' id='age'>$patient_age</span>
                        </div>
                        <div class='doctor-container'>
                            <span>Referring Physician: </span>
                            <span contenteditable='true' class='underlined' id='physician'></span>
                        </div>
                    </div>

                    <div class='medicine lab-result'>
                        <div class = 'lab-title-type'>
                            <span contenteditable = 'true' id = 'lab-title'>$test_type</span>
                        </div>
                        <table class='medicine-table' id='medicine-table' border='1' cellspacing='0'>
                            <colgroup>
                                <col span='1' style='width: 20%;'>
                                <col span='1' style='width: 40%;'>
                                <col span='1' style='width: 40%;'>
                            </colgroup>

                            <tr>
                                <th colspan = '1'>Examinations</th>
                                <th colspan = '1'>Results</th>
                                <th colspan = '1'>Reference Value</th>
                            </tr>

                            <!--TESTING-->
                            <tr class='lab-result-content' id='prescription-item'>
                                <td contenteditable='true'>Test Name</td>
                                <td>
                                    <button class = 'add-row'>Add Row</button>
                                    <span contenteditable = 'true'>0</span>
                                </td>
                                <td>
                                    <button class = 'add-row'>Add Row</button>
                                    <span contenteditable = 'true'>0</span>
                                    <button class = 'delete-lab-div'>Delete</button>
                                </td>
                            </tr>
                        </table>
                        <div class = 'remarks'>
                            <span>REMARKS: </span>
                            <span contenteditable = 'true' id = 'remarks'></span>
                        </div>
                    </div>

                    <div class='footer-lab'>
                        <div>
                            <div class = 'e-sig e-sig-1'>
                            
                            </div>
                            <span contenteditable = 'true' id = 'first-med-name'>CLARISSE R. SALVADOR, RMT</span>
                            <span contenteditable = 'true' id = 'first-med-pos'>Medical Technologist</span>
                            <span contenteditable = 'true' id = 'first-med-no'>Lic. No. 63417</span>
                        </div>
                        <div>
                            <div class = 'e-sig e-sig-2'>
                                
                            </div>
                            <span contenteditable = 'true' id = 'second-med-name'>RHODERICK M. CRUZ, MD, DPSP</span>
                            <span contenteditable = 'true' id = 'second-med-pos'>Pathologist</span>
                            <span contenteditable = 'true' id = 'second-med-no'>Lic. No. 92087</span>
                        </div>
                    </div>
                </div>
            </div>
            ";
    }
    ?>
</body>
<script src='../js/pdf-generator.js'></script>
<script src='../js/base64-to-pdf.js'></script>
<script src='../js/billing.js'></script>

</html>