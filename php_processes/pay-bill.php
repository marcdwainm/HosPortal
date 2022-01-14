<?php

include 'db_conn.php';

$bill_num = $_POST['billnum'];

$query = "UPDATE bills SET paid = '1' WHERE bill_num = '$bill_num'";
mysqli_query($conn, $query);

$patientid = substr($bill_num, -4);



$query = "SELECT * FROM bills WHERE issued_to = '$patientid' ORDER BY date_issued DESC";
$result = mysqli_query($conn, $query);

$trans_num = "";
$trans_date = "";
$trans_price = "";
$trans_status = "";


while ($row = mysqli_fetch_array($result)) {
    $trans_num = $row['bill_num'];
    $trans_date = $row['date_issued'];
    $trans_price = $row['total'];
    $trans_status = $row['paid'] == "1" ?  "<span class = 'green-text'>Paid</span>" : "<span class = 'red-text'>Unsettled</span>";
    $paynow_btn = "<button class = 'pay-now' value = '$trans_num'>Pay Now</button>";

    if ($trans_status == "<span class = 'green-text'>Paid</span>") {
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
