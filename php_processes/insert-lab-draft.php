<?php
session_start();
include 'db_conn.php';

$fullname_medtech = $_SESSION['fullname'];
$emp_id = $_SESSION['emp_id'];
$pname = ucwords($_POST['pname']);
$test_type = ucwords($_POST['testType']);
$collection_date = $_POST['collectionDate'];
$result_date = $_POST['resultDate'];
$pid = $_POST['pid'];
$html = "";
$base64 = "";
$corresponding_bill = isset($_POST['correspondingBill']) ? $_POST['correspondingBill'] : '';

$date = date('Ymdhis', time());
$doc_num = $date . $pid;

$date_uploaded = date('Y-m-d H:i:s', time());


if (isset($_POST['issuedByMedtech'])) {
    //IF ISSUED BY MEDTECH NOTIFY PATIENT ABOUT BILL ISSUE
    mysqli_query($conn, "INSERT INTO patients_notifications (emp_id, patient_id, notif_type, date_notified, seen) 
    VALUES ('$emp_id', '$pid', 'draftbill', '$date_uploaded', '0')");
}

//IF PATIENT IS PORTAL REGISTERED
if ($pid != '0000') {

    $query = "SELECT * FROM user_table WHERE patient_id = '$pid'";

    $result = mysqli_query($conn, $query);

    while ($row = mysqli_fetch_array($result)) {
        $sex = ucfirst($row['sex']);
        $birthdate = $row['birthdate'];
        $from = new DateTime($birthdate);
        $to = new DateTime('today');
        $age = $from->diff($to)->y;
        $date = date("m/d/Y", time());
        $address = $row['address'];

        $html = "   
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
                    <span contenteditable='true' class='underlined' id='date'>$date</span>
                </div>
                <div class='name-container'>
                    <span>Patient Name: </span>
                    <span contenteditable='true' class='underlined' id='name'>$pname</span>
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
                    <span contenteditable='true' class='underlined' id='age'>$age</span>
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
        $base64 = base64_encode($html);
    }
}

//IF PATIENT NOT PORTAL REGISTERED
else {
    $date = date("m/d/Y", time());
    $html = "
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
            <div class='date-container'>
                <span>Date: </span>
                <span contenteditable='true' class='underlined' id='date'>$date</span>
            </div>
            <div class='name-container'>
                <span>Patient Name: </span>
                <span contenteditable='true' class='underlined' id='name'>$pname</span>
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
                <span contenteditable='true' class='underlined' id='age'>$age</span>
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
        </div>x
    </div>
</div>
        ";
    $base64 = base64_encode($html);
}

$query = "INSERT INTO lab_drafts (`doc_num`, `emp_id`, `patient_id`, patient_fullname, `test_type`, `status`, `html_draft`, date_uploaded, collection_date, estimated_result, corresponding_bill)
    VALUES ('$doc_num', '$emp_id', '$pid', '$pname', '$test_type', 'pending', '$base64', '$date_uploaded', '$collection_date', '$result_date', '$corresponding_bill')";

mysqli_query($conn, $query);

include 'medtech-dynamic-table.php';
