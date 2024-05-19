<?php
// Include PHPSpreadsheet autoload
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

require_once("db_connect.php");

// Query to select required columns from 'wallet_transaction' table and order by 'create_date' column in descending order
$sql = "SELECT id, 
transaction_type, 
user_id,
to_user_id,
transaction_date,
transaction_amount,
transaction_id,
status,
create_date,
update_date 
FROM fund_transaction 
WHERE update_date BETWEEN '2024-04-01' AND '2024-04-30' 
AND status = 3
ORDER BY create_date DESC";






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
    array('ID', 'Transaction Type', 'user_id', 'to_user_id','transaction_date', 'transaction_amount', 'transaction_id', 'status','Create Date','Deactivate Date'),
);

// Fetch data
$result = mysqli_query($conn, $sql);

if ($result) {
    $transactions = mysqli_fetch_all($result, MYSQLI_ASSOC);
   
    foreach ($transactions as $transaction) {
        $data[] = array(
            $transaction['id'],
            $transaction['transaction_type'],
            $transaction['user_id'],
            $transaction['to_user_id'],
          
            $transaction['transaction_date'],
            $transaction['transaction_amount'],
            $transaction['transaction_id'],
            'Deactivated',
            $transaction['create_date'],
            $transaction['update_date']
        );

     

    }
    mysqli_free_result($result);
}

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
