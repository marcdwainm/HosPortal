<?php
include 'db_conn.php';

$offset = 0;

if (isset($_POST['offset'])) {
    $offset = $_POST['offset'];
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$patientid = $_SESSION['patientid'];
$query = "SELECT * FROM bills WHERE issued_to = '$patientid' ORDER BY UNIX_TIMESTAMP(date_of_payment) DESC LIMIT $offset, 5";
$result = mysqli_query($conn, $query);

$trans_num = "";
$trans_date = "";
$trans_price = "";
$trans_status = "";

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_array($result)) {
        $trans_num = $row['bill_num'];
        $trans_date = $row['date_of_payment'] == null ? "N/A" : Date("M d, Y / h:i A", strtotime($row['date_of_payment']));
        $trans_price = $row['total'];
        $trans_status = $row['paid'] == "1" ?  "<span class = 'green-text'>Paid</span>" : "<span class = 'red-text'>Unsettled</span>";
        $paynow_btn = "<button class = 'pay-now' value = '$trans_num'>Pay Now</button>";

        if ($row['paid'] == "1") {
            $paynow_btn = "<button value = '$trans_num' class = 'view-trans-details'>View Details</button>";
        }

        echo "
            <div class='sample-transaction'>
                <span class='transaction-num'>$trans_num</span>
                <span>$trans_date</span>
                <span>P$trans_price</span>
                $trans_status
                <span>
                    $paynow_btn
                </span>
            </div>
        ";
    }
} else {
    echo "<span class = 'no-appointments'>You have no bills</span>";
}
