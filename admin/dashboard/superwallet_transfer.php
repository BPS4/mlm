<?php
	require_once("../../db_connect.php");
// Step 1: Select user_id and superwallet from the wallets table
$query_select = "SELECT user_id, superwallet FROM wallets";
$res_select = mysqli_query($conn, $query_select);

if (!$res_select) {
    die('Error: ' . mysqli_error($conn));
}

$wallets = mysqli_fetch_all($res_select, MYSQLI_ASSOC);

// Step 2: Loop through each wallet record
foreach ($wallets as $wallet) {
    $user_id = $wallet['user_id'];
    $superwallet = $wallet['superwallet'];
    
    // Step 3: Update superwallet to NULL in the wallets table
    $query_update = "
    UPDATE wallets 
    SET 
        wallet_commission = wallet_commission + superwallet, 
        superwallet = 0
    WHERE superwallet > 0";

    $res_update = mysqli_query($conn, $query_update);
    
    // if (!$res_update) {
    //     die('Error: ' . mysqli_error($conn));
    // }

    // Step 4: Insert a new record into the wallet_transaction table
    $transaction_type = 'superwallet';
    $transaction_mode = 'credit';
    $from_user_id = 'superwallet';
    $transaction_amount = $superwallet;
    $status = 1;
    $updated_by = 'max_admin';
    $create_date = date('Y-m-d H:i:s');

    // echo'hellow';

    $query_insert = "
        INSERT INTO wallet_transaction (
            transaction_type, transaction_mode, user_id,  from_user_id, 
            transaction_amount, status, updated_by, create_date
        ) VALUES (
            '$transaction_type', '$transaction_mode', '$user_id', '$from_user_id','$transaction_amount', '$status', '$updated_by', '$create_date'
        )
    ";

    $res_insert = mysqli_query($conn, $query_insert);

    if (!$res_insert) {
        die('Error: ' . mysqli_error($conn));
    }
}

echo "Records updated and inserted successfully.";
?>
