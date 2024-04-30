<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	// require_once("../../db_connect.php");

	// DB CONNECT TO BE PUT HERE ALWAYS FOR CRON JOBS
	/* Database connection start */
	$servername = "localhost";
	$username = "maxizone";
	$password = "Maxizone@2023Bhushan";
	$dbname = "maxizone";
	$conn = mysqli_connect($servername, $username, $password, $dbname) or die("Connection failed: " . mysqli_connect_error());
	if (mysqli_connect_errno()) {
		printf("Connect failed: %s\n", mysqli_connect_error());
		exit();
	}
	/* Database connection start */
	
	session_start();
	
	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');
	$today_date_only = date('Y-m-d');
	$today_time_only = date('H:i');
	$today_date = date('d');
	$current_day = date('D');
	$current_month = date('m');
	$current_month = date('m', strtotime($today_date_only));
	$current_year = date('Y', strtotime($today_date_only));
	// t >> GIVES LAST DAY OF MONTH
	// echo $current_date;

	apply3xplan();
 
	function apply3xplan(){
		$conn = $GLOBALS['conn'];
		$userlistquery = "SELECT * FROM `users`
		inner join `wallets` on users.user_id = wallets.user_id
		where  `wallets`.`wallet_investment`>0;";
		$resuserlist = mysqli_query($conn,$userlistquery);
		$userlist = mysqli_fetch_all($resuserlist,MYSQLI_ASSOC);
		foreach($userlist as $user){
			// echo "Switch User Called"." <br/>";
			// echo $user['user_id']." <br/>";
			$userid = $user['user_id'];
		
			$query = "SELECT 
						ft.id,
						ft.transaction_amount,
						ft.transaction_type,
						ft.user_id,
						ft.status,
						ft.create_date
					FROM
						`fund_transaction` ft
					WHERE
						ft.transaction_type IN ('fresh' , 'admin_credit', 'superwallet')
							AND ft.status = 1 and ft.user_id = '$userid'
					ORDER BY  ft.user_id, ft.create_date  ASC";
			// echo $query."; <br/>";
					
			$res = mysqli_query($conn,$query);
			$transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);
			// $query = "SELECT 
			// 				ft.id,
			// 				ft.transaction_amount,
			// 				ft.transaction_type,
			// 				ft.user_id,
			// 				ft.status,
			// 				ft.create_date
			// 			FROM
			// 				`fund_transaction` ft
			// 					inner JOIN
			// 				users u ON ft.user_id = u.user_id
			// 			WHERE
			// 				ft.transaction_type IN ('fresh' , 'admin_credit', 'superwallet')
			// 					AND ft.status = 1 and u.status = 'active'
			// 			ORDER BY  ft.user_id, ft.create_date  ASC";
			// 			echo $query."; <br/>";
			// $res = mysqli_query($conn,$query);
			// $transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);
			
			foreach ($transactions as $item) {
					$user_id= $item['user_id'];
					//echo "New User Start: ".$user_id." <br/>";
					$status = $item['status'];
					$create_date = $item['create_date'];
					$total = (float) $item['transaction_amount'] * 3;
					$transaction_amount = $item['transaction_amount'];
					$transactionid = $item['id'];
					$totaltransactionamountQuery = "SELECT sum(transaction_amount) as totaltransactionamount FROM wallet_transaction WHERE user_id = '$user_id'  AND ifnull(used_status,0) = 0
					AND status = 1 AND transaction_type IN ('sponsor_commission', 'level_commission', 'roi')
					AND create_date >= '$create_date' ORDER BY `create_date` ASC";
					// echo $totaltransactionamountQuery."; <br/>";
					$resulttotaltransactionamount = mysqli_query($conn, $totaltransactionamountQuery);     
					$res = mysqli_fetch_array($resulttotaltransactionamount);
					extract($res);
				
					// echo "Total Transaction Amount ".$totaltransactionamount." <br/>";
					if($totaltransactionamount >= $total)
					{
						$walletinvestmentselectQuery = "SELECT wallet_investment FROM wallets WHERE user_id = '$user_id'  ORDER BY `create_date` ASC";
						//echo $walletinvestmentselectQuery."; <br/>";
						$resultwalletinvestmentselectQuery = mysqli_query($conn, $walletinvestmentselectQuery);
						
						$transactionswalletinvestmentselectQuery = mysqli_fetch_all($resultwalletinvestmentselectQuery,MYSQLI_ASSOC);
						$wallet_investment = 0;
						foreach ($transactionswalletinvestmentselectQuery as $transactionswalletinvestment)
						{
							$wallet_investment = $transactionswalletinvestment['wallet_investment'];
						}	
						// echo "Transaction Amount : ".$transaction_amount." <br/>";
						// echo "3x Transaction Amount : ".$transaction_amount * 3 ." <br/>";
						// echo "Wallet Investment : ".$wallet_investment." <br/>";
						$updatedwalletid = [];
						setStatusForUsedInvestment($conn, $user_id, $create_date, $total, $transactionid, $transaction_amount,$wallet_investment,$updatedwalletid);
						//die;		
					}
					else{
						// echo "Switch User Calling"." <br/>";
						goto switchuser;
						//break;
					}
					// echo "New User Completed: ".$user_id." <br/>";
			}
			
			switchuser:
			// if(array_search($user, $userlist) ==50)
			// 		{
			// 			die;
			// 		}
		}
	}

	
function setStatusForUsedInvestment($conn, $user_id, $create_date, $total, $transactionid, $transaction_amount,$wallet_investment,$updatedwalletid) {
    $amounts = 0;
    $current_date= $GLOBALS['current_date'];
    // Select records based on the conditions
    $selectQuery = "SELECT id, transaction_amount FROM wallet_transaction WHERE user_id = '$user_id'  AND ifnull(used_status,0) = 0
    AND status = 1 AND transaction_type IN ('sponsor_commission', 'level_commission', 'roi')
    AND create_date >= '$create_date' ORDER BY `id` ASC";
 	//echo $selectQuery."; <br/>";
	$result = mysqli_query($conn, $selectQuery);
		// Check if there are any matching records
		if (mysqli_num_rows($result) > 0) {
			$totaltransactionamountQuery = "SELECT sum(transaction_amount) as totaltransactionamount FROM wallet_transaction WHERE user_id = '$user_id'  AND ifnull(used_status,0) = 0
			AND status = 1 AND transaction_type IN ('sponsor_commission', 'level_commission', 'roi')
			AND create_date >= '$create_date' ORDER BY `id` ASC";
			// echo $totaltransactionamountQuery."; <br/>";
			$resulttotaltransactionamount = mysqli_query($conn, $totaltransactionamountQuery);     
			$res = mysqli_fetch_array($resulttotaltransactionamount);
			extract($res);
			if($totaltransactionamount >= $total)
			{
				$balanceamount = 0;
				
				$balanceamountid = 0;
				// Loop through the results
				while ($row = mysqli_fetch_assoc($result)) {
						// Get the id of each record
						$id = $row['id'];
					// echo "SUM--".
					$sum_amount = $row['transaction_amount'];
					$amounts = $amounts + $sum_amount;
					//echo "user id : ".$user_id." AmountOne : ".$amounts." <br/>";
					if($amounts >= $total) {
						$balanceamount = $amounts - $total;
						$balanceamountid = $id;
						array_push($updatedwalletid,$id);
						//echo ",".$id;
						// echo "IF". 
						// $updateQuery = "UPDATE wallet_transaction SET used_status = 1, used_amount = '$fix' , update_date = '$current_date' WHERE id = $id";
						// mysqli_query($conn, $updateQuery);
						
						// $updatefundtransaction = "UPDATE fund_transaction SET status = 3, update_date = '$current_date' WHERE id = $transactionid";
						// mysqli_query($conn, $updatefundtransaction);
						
						// $updatedtransaction_amount = $wallet_investment - $transaction_amount;
						// $updatetransactionamountwallet = "UPDATE wallets SET wallet_investment =  $updatedtransaction_amount , update_date = '$current_date' WHERE user_id = $user_id";
						// mysqli_query($conn, $updatetransactionamountwallet);
						//goto getfundtrans;
						
						//die;
						break;
					}
					else{
						array_push($updatedwalletid,$id);
						// echo "Else". 
						// $updateQuery = "UPDATE wallet_transaction SET used_status = 1,  update_date = '$current_date'  WHERE id = $id";
						// mysqli_query($conn, $updateQuery);
					}
					
				}
				$updateQuery = "UPDATE wallet_transaction SET used_status = 1, update_date = '$current_date' WHERE id in (".join(', ', $updatedwalletid).")";
				//echo $updateQuery;
				mysqli_query($conn, $updateQuery);
				
				$updatebalanceQuery = "UPDATE wallet_transaction SET used_amount = $balanceamount, update_date = '$current_date' WHERE id in ($balanceamountid)";
				//echo $updatebalanceQuery;
				mysqli_query($conn, $updatebalanceQuery);

				$updatefundtransaction = "UPDATE fund_transaction SET status = 3, update_date = '$current_date' WHERE id = $transactionid";
				mysqli_query($conn, $updatefundtransaction);
				//echo $wallet_investment.",".$user_id.",";		

				$updatedtransaction_amount = $wallet_investment - $transaction_amount;
				$updatetransactionamountwallet = "UPDATE wallets SET wallet_investment =  '$updatedtransaction_amount' , update_date = '$current_date' WHERE user_id = '$user_id'";
				//echo $updatetransactionamountwallet;
				mysqli_query($conn, $updatetransactionamountwallet);
				//echo $updatedtransaction_amount.",".$wallet_investment.",".$transaction_amount.",".$user_id;		
				$plandiactivationlogsquery = "insert into  plandiactivationlogs (
					`user_id`,
					`wallet_imvestment`,
					`deactivatedplanamount`,
					`updatedwallet_investment`,
					`createddate`,
					`createdBy`) VALUES (
						'$user_id',
					'$wallet_investment',
					'$transaction_amount',
					'$updatedtransaction_amount',
					'$current_date',
					'System')";
					mysqli_query($conn, $plandiactivationlogsquery);
		//die;	
	}
			//echo join(', ', $updatedwalletid);
		//	echo "Status updated successfully.";	
		}
	}
?>