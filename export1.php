<?php
// Include PHPSpreadsheet autoload
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once("db_connect.php");

// Query to select required columns from 'wallet_transaction' table and order by 'create_date' column in descending order
$sql = "SELECT transaction_amount,create_date FROM wallet_transaction WHERE user_id = 695202 AND transaction_type = 'roi'";


// Create new Spreadsheet object
$spreadsheet = new Spreadsheet();

// Set document properties
$spreadsheet->getProperties()
    ->setCreator("Your Name")
    ->setLastModifiedBy("Your Name")
    ->setTitle("Data Export")
    ->setSubject("Data Export")
    ->setDescription("Data export in Excel format")
    ->setKeywords("excel data export")
    ->setCategory("Data Export");

// Prepare the data for the spreadsheet
$data = array(
    array('user_id','Transaction Type', 'transaction_amount', 'create_date',),
);

// Fetch data
$result = mysqli_query($conn, $sql);


if ($result) {
    $transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);


    // print_r($transactions);
    // die();

    $user_id= 695202;
    $transaction_type = 'roi';
   
    foreach ($transactions as $transaction) {
        $data[] = array(
          
            $user_id ,
            $transaction_type ,           
            $transaction['transaction_amount'],
            $transaction['create_date'],
        );
    }
    mysqli_free_result($result);
}

$sum = "SELECT SUM(transaction_amount) AS total_amount
        FROM wallet_transaction
        WHERE user_id = 695202 AND transaction_type = 'roi'";

$sumResult = mysqli_query($conn, $sum);
$sumTransactions = mysqli_fetch_all($sumResult, MYSQLI_ASSOC);

$total = $sumTransactions[0]['total_amount'];


$newRow = ['total ROI Amount', $total];

// Add the new row to the data array
$data[] = $newRow;


// Set data to the active sheet
$spreadsheet->getActiveSheet()->fromArray($data, null, 'A1');

// Create Excel Writer object
$writer = new Xlsx($spreadsheet);

// Set headers to force download the file
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="data_export.xlsx"');
header('Cache-Control: max-age=0');

// Save Excel file to output stream (browser)
$writer->save('php://output');

// Close database connection
mysqli_close($conn);
?>
