<?php
session_start();
include 'db_conn.php';

$billnum = $_POST['billnum'];
$paypal_btn  = $_POST['paystatus'] == 'unpaid' ? "<div class='paypal-btn'></div>" : "";

$query = "SELECT * from bills WHERE bill_num = '$billnum'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_array($result)) {
    $issued_to = $row['issued_to'];

    $result1 = mysqli_query($conn, "SELECT * FROM user_table WHERE patient_id = '$issued_to'");
    $row1 = mysqli_fetch_array($result1);
    $fullname_patient = $row1['first_name'] . " " . substr($row1['middle_name'], 0, 1) . ". " . $row1['last_name'];


    $items = $row['names'];
    $prices = $row['prices'];
    $total = $row['total'];
    $issued_by = $row['issued_by'];
    $date_issued = $row['date_issued'];
    $date_of_payment = $row['paid'] == '0' ? 'N/A' : date("M d, Y h:i A", strtotime($row['date_of_payment']));
    $status = $row['paid'] == "0" ? 'Unsettled' : 'Paid';

    $items_array = explode(", ", $items);
    $prices_array = explode(", ", $prices);

    $query_nest = "SELECT * FROM employee_table WHERE employee_id = '$issued_by'";
    $result_nest = mysqli_query($conn, $query_nest);
    $employee_fullname = "";

    while ($row = mysqli_fetch_array($result_nest)) {
        $firstname = $row['first_name'];
        $middleinitial = $row['middle_name'][0] . ".";
        $lastname = $row['last_name'];

        $employee_fullname = "$firstname $middleinitial $lastname";
    }

    echo "
    <div class='book-header-exit'>
        <span>Bill No. <span class = 'bill-num'>$billnum</span></span>
        <span class='exit-2'>X</span>
    </div>
    <div class='bill-content'>
        <div class='bill-details'>
            <span><b>Issued to:</b>  $fullname_patient</span>
            <span><b>Issued by:</b>  $employee_fullname</span>
            <span><b>Date of Payment:</b>  $date_of_payment</span>
            <span><b>Status:</b>  $status</span>
        </div>

        <div class='bill-table'>
            <div class='bill-table-header'>
                <span>Description</span>
                <span>Price</span>
            </div>
            <div class='bill-table-body'>";



    for ($i = 0; $i < count($items_array); $i++) {
        $curr_price = number_format($prices_array[$i], 2);
        echo "
            <div class='bill-item'>
                <span>$items_array[$i]</span>
                <span>P$curr_price</span>
            </div>
            ";
    }


    echo "</div>
        </div>

        <div class='bill-total-button'>
            <div class='bill-total'>Total: <b>P<span id = 'total-price'>$total</span></b></div>
            $paypal_btn
        </div>
    </div>
    ";
}
