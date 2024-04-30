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
	//apply3xplan();
 //goto skip_checks;

	// SECURED ACCESS ONLY FROM CLI >> CRON JOB AND PREVENT ACCESS FROM BROWSER
		if (isset($GLOBALS['argv'])){ 
			//if the script is being accessed from cli 
			//code to do whatever cron needs to do 
		} else{ 
			//if the script is being accessed from the browser 
			//could be exit(0) 
			//or 
			//a redirect to 'your '404 not found' page. 
			echo "Unauthorised access!";
			exit;
		}
//	SECURED ACCESS ONLY FROM CLI >> CRON JOB AND PREVENT ACCESS FROM BROWSER

	if ($today_time_only == "01:10") {

	} else {
		echo "Time not permitted";
		exit;
	}

	skip_checks:

	function last_day_of_the_month($date = '') {
		$month  = date('m', strtotime($date));
		$year   = date('Y', strtotime($date));
		$result = strtotime("{$year}-{$month}-01");
		$result = strtotime('-1 second', strtotime('+1 month', $result));
		
		return date('d', $result);
	}

	$lastday =  last_day_of_the_month($today_date_only);

	$date_first = "$current_year-$current_month-01";


	// FOR FD MATURITY DATES
		$date = "2016-02-29 11:11:11";
		// $end_date = date("d-m-Y",strtotime("+1 months",strtotime(date("Y-m-01",strtotime("now") ) )));
		// echo $end_date;
		// echo date('d-m-Y', strtotime($date.'+365 days'));	// WORKING
	// FOR FD MATURITY DATES



	// SALARY
		if ($today_date == $lastday) {
			// LAST DAY OF MONTH
			// CREDIT SALARY/SUBSIDY
			// release_salary($date_first);
		}
	// SALARY
	
	// ROI
		$query_roi_dates = "SELECT `roi_date` FROM `roi_calendar` WHERE `is_active`=1";
		$res = mysqli_query($conn,$query_roi_dates);
		$roi_dates = mysqli_fetch_all($res,MYSQLI_ASSOC);
		$roi_date_array = array();
		$i=0;
		foreach ($roi_dates as $row) {
			$roi_date = $row['roi_date'];
			$roi_date_array[$i] = $roi_date;
			$i++;
		}
		// if (!in_array($today_date_only, $roi_date_array) && ($current_day != "Sat" && $current_day != "Sun")) {
		if (!in_array($today_date_only, $roi_date_array)) {
			 release_investment_roi();
			generate_level_income();
			//apply3xplan();
			// test_cron($today_date_only,$today_time_only,$current_day);
		}
	// ROI

	// test_cron($today_date_only,$today_time_only,$current_day);

	// set_renewal();

	// RENEWAL
		function set_renewal() {
			$conn = $GLOBALS['conn'];
			$current_date = $GLOBALS['current_date'];
			$query_users = "SELECT `user_id`,TIMESTAMPDIFF(YEAR, `create_date`, DATE(NOW())) AS 'month_created' FROM `users` WHERE `user_id`!='wealthride' AND `user_rank`='bronze' AND `status`='active' AND TIMESTAMPDIFF(YEAR, `create_date`, DATE(NOW()))>=1";
			$res = mysqli_query($conn,$query_users);
			$members = mysqli_fetch_all($res,MYSQLI_ASSOC);

			$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
			$value_insert_log = "";

			$query_update_users = 
				"
					UPDATE `users`
				";
			$query_update_users_status = 
				"
					SET `status` = CASE `user_id`
				";
			$query_update_users_update_date = 
				"
					`update_date` = CASE `user_id`
				";
				
			foreach ($members as $row) {
				$member_id = $row['user_id'];

				$value_insert_log .= "('$member_id', 'pending_renewal', 'ID Renewal Needed', 'SYSTEM', '$current_date'),";

				$query_update_users_status .= 
					"
						WHEN '$member_id' THEN 'pending_renewal'
					";
				$query_update_users_update_date .= 
					"
						WHEN '$member_id' THEN '$current_date'
					";
			}

			$query_update_users_status .= 
				"
					ELSE `status`
					END,
				";
			$query_update_users_update_date .= 
				"
					ELSE `update_date`
					END
				";

			$query_update_users = 
				"
					$query_update_users
					$query_update_users_status
					$query_update_users_update_date
				";
			mysqli_query($conn, $query_update_users);

			if ($value_insert_log != "") {
				$value_insert_log = rtrim($value_insert_log,",");
				$query_insert_log = "$query_insert_log $value_insert_log";
				mysqli_query($conn,$query_insert_log);
			}
		}
	// RENEWAL

    function release_salary($date_first) {
		$salary_amount = 5000;
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];

		// $query = "SELECT `user_id`,TIMESTAMPDIFF(MONTH, '2022-01-30', DATE('2022-02-28')) AS 'month_created' FROM `users` WHERE TIMESTAMPDIFF(MONTH, `create_date`, DATE(NOW()))>=1";
		$query = "SELECT `user_id` FROM `users` WHERE `create_date`<'$date_first'";
		$res = mysqli_query($conn,$query);
		$members = mysqli_fetch_all($res,MYSQLI_ASSOC);

		$query_insert_salary = "INSERT INTO `wallet_transaction`(`transaction_type`, `user_id`, `transaction_amount`, `create_date`) VALUES ";
		$value_insert_salary = "";
		
		$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
		$value_insert_log = "";

		$receiver_wallet_update_array = array();
		$j = 0;
		foreach ($members as $row) {
			$member_id = $row['user_id'];
			$query_salary_data = "SELECT COUNT(`id`) AS 'salary_done' FROM `wallet_transaction` WHERE `transaction_type`='salary' AND `user_id`='$member_id'";
			$res = mysqli_query($conn,$query_salary_data);
			$res = mysqli_fetch_array($res);
			extract($res);
			if ($salary_done>=12) {
				continue;
			}

			// INSERT ONLY ONE TIME PER MONTH SALARY
				$query_salary_done = "SELECT `id` FROM `wallet_transaction` WHERE `transaction_type`='salary' AND `user_id`='$member_id' AND `create_date` LIKE '%$today_date_only%'";
				$res = mysqli_query($conn,$query_salary_done);
				$count_salary_done = mysqli_num_rows($res);
				if ($count_salary_done>0) {
					continue;
				}
			// INSERT ONLY ONE TIME PER MONTH SALARY
			

			$value_insert_salary .= "('salary', '$member_id', '$salary_amount', '$current_date'),";
			$value_insert_log .= "('$member_id', 'salary', 'Credited $salary_amount', 'SYSTEM', '$current_date'),";

			$receiver_wallet_update_array[$j]['user_id_receiver'] = $member_id;
			$receiver_wallet_update_array[$j]['credited_amount'] = $salary_amount;
			$j++;
		}
		
		if ($value_insert_salary != "") {
			$value_insert_salary = rtrim($value_insert_salary,",");
			$query_insert_salary = "$query_insert_salary $value_insert_salary";
			if (mysqli_query($conn,$query_insert_salary)) {
				// UPDATE USER FUNDS AFTER INSERTING SALARY INTO WALLET TRANSACTION TABLE
					$query_update_users = 
						"
							UPDATE `users`
						";
					$query_update_users_fund_salary = 
						"
							SET `fund_salary` = CASE `user_id`
						";
					$query_update_users_fund_total = 
						"
							`fund_total` = CASE `user_id`
						";
					$query_update_users_update_date = 
						"
							`update_date` = CASE `user_id`
						";

					foreach ($receiver_wallet_update_array as $row) {
						$user_id_receiver = $row['user_id_receiver'];
						$credited_amount = $row['credited_amount'];
						
						$fund_salary = 0;

						$query_fund_salary = mysqli_query($conn, "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'fund_salary' FROM `wallet_transaction` WHERE `user_id`='$user_id_receiver' AND `transaction_type`='salary'");
						if ($rw = mysqli_fetch_array($query_fund_salary)) {
							$fund_salary = $rw['fund_salary'];
						}

						$query_update_users_fund_salary .= 
							"
								WHEN '$user_id_receiver' THEN '$fund_salary'
							";
						$query_update_users_fund_total .= 
							"
								WHEN '$user_id_receiver' THEN `fund_wallet`+`fund_commission`+`fund_salary`
							";
						
						$query_update_users_update_date .= 
							"
								WHEN '$user_id_receiver' THEN '$current_date'
							";
					}

					$query_update_users_fund_salary .= 
						"
							ELSE `fund_salary`
							END,
						";
					$query_update_users_fund_total .= 
						"
							ELSE `fund_total`
							END,
						";
					$query_update_users_update_date .= 
						"
							ELSE `update_date`
							END
						";

					$query_update_users = 
						"
							$query_update_users
							$query_update_users_fund_salary
							$query_update_users_fund_total
							$query_update_users_update_date
						";
					mysqli_query($conn, $query_update_users);

				// UPDATE USER FUNDS AFTER INSERTING SALARY INTO WALLET TRANSACTION TABLE
			}
		}

		if ($value_insert_log != "") {
			$value_insert_log = rtrim($value_insert_log,",");
			$query_insert_log = "$query_insert_log $value_insert_log";
			mysqli_query($conn,$query_insert_log);
		}
	}

	function get_level_data_for_wallet() {
		$conn = $GLOBALS['conn'];
		// $query_lvl = "SELECT `levels`.`level`,`levels`.`user_id`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent` FROM `levels`";
		// GET ONLY ACTIVE IDS
		// $query_lvl = "SELECT `levels`.`level`,`levels`.`user_id`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent` FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id_up` WHERE `users`.`status`='active'";
		// $query_lvl_with_invest_amt = "SELECT `levels`.`level`,`levels`.`user_id`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent`,`wallets`.`wallet_investment` FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id_up` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id` WHERE `users`.`status`='active'";
		// GET DATA WITH LEVEL AND INVESTED AMOUNT
		$query_lvl_with_invest_amt = "SELECT `levels`.`level`,`levels`.`user_id`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent`,(SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`levels`.`user_id`) AS 'donor_investment',(SELECT `wallets`.`wallet_investment` FROM `wallets` WHERE `user_id`=`levels`.`user_id_up`) AS 'receiver_investment' FROM `levels` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id_up` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id_up` WHERE `users`.`status`='active'";
		$res = mysqli_query($conn, $query_lvl_with_invest_amt);
		$records = mysqli_fetch_all($res, MYSQLI_ASSOC);
		return $records;
	}
	
	function test_cron($today_date_only,$today_time_only,$current_day) {
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$query_insert = "INSERT INTO `cron_test`(`message`, `create_date`) VALUES ('$today_date_only, $today_time_only, $current_day Message Inserted By Cron Task', '$current_date')";
		mysqli_query($conn,$query_insert);
	}

	function release_roi() {
		$company_payment_amount = 51000;
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];
		
		$level_array = get_level_data_for_wallet();

		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE
			$query_insert_wallet_transaction = "INSERT INTO `wallet_transaction`(`transaction_type`, `user_id`, `from_user_id`, `transaction_amount`, `create_date`) VALUES ";
			$value_insert_wallet_transaction = "";

			$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
			$value_insert_log = "";
		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE

		$receiver_wallet_update_array = array();

		// SET WALLET TRANSACTION INSERT DATA
			$j = 0;
			foreach ($level_array as $row) {
				$user_id_donor = $row['user_id'];
				$user_level = $row['level'];
				$user_id_receiver = $row['user_id_up'];	//ACTIVE FETCHED
				$wallet_amount_percent = $row['wallet_amount_percent'];
				$commission_amount_percent = $row['commission_amount_percent'];
				
				// NO USER LEVEL DATA
					if ($user_level == "") {
						continue;
					}
				// NO USER LEVEL DATA
				
				$query_check_level = "SELECT * FROM `levels` WHERE `level` = 1 AND `user_id_up` LIKE '$user_id_receiver'";
				$res = mysqli_query($conn,$query_check_level);
				$count_direct_joining = mysqli_num_rows($res);

				if ($count_direct_joining < $user_level) {
					continue;
				}

				// CHECK ACTIVE STATUS OF DONOR ID
					$query = "SELECT `user_id` FROM `users` WHERE `user_id`='$user_id_donor' AND `status`='active'";
					$res = mysqli_query($conn,$query);
					$count_status = mysqli_num_rows($res);
					if ($count_status == 0) {
						continue;
					}
				// CHECK ACTIVE STATUS OF DONOR ID
				
				$credited_amount = ($company_payment_amount*$commission_amount_percent)/3000;
				$credited_amount = round($credited_amount,2);
	
				// CHECK TRANSACTION EXIST
					$count_exist = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `wallet_transaction` WHERE `transaction_type`='roi' AND `user_id`='$user_id_receiver' AND `from_user_id`='$user_id_donor' AND `transaction_amount`='$credited_amount' AND `create_date` LIKE '%$today_date_only%'"));
				// CHECK TRANSACTION EXIST

				if ($count_exist == 0) {
					// print_r("From: $user_id_donor >> Level: $user_level >> To: $user_id_receiver >> Amount: $credited_amount");					
					
					$value_insert_wallet_transaction .= "('roi', '$user_id_receiver', '$user_id_donor', '$credited_amount', '$current_date'),";
					
					$value_insert_log .= "('$user_id_receiver', 'monthly_roi', 'Credited $credited_amount', 'SYSTEM', '$current_date'),";

					$receiver_wallet_update_array[$j]['user_id_receiver'] = $user_id_receiver;
					$receiver_wallet_update_array[$j]['credited_amount'] = $credited_amount;
					$j++;
				}
			}

			if ($value_insert_wallet_transaction != "") {
				$value_insert_wallet_transaction = rtrim($value_insert_wallet_transaction,",");
				$query_insert_wallet_transaction = "$query_insert_wallet_transaction $value_insert_wallet_transaction";
				if (mysqli_query($conn,$query_insert_wallet_transaction)) {
					// UPDATE USER FUNDS AFTER INSERTING ROI INTO WALLET TRANSACTION TABLE
						$query_update_users = 
							"
								UPDATE `users`
							";
						$query_update_users_fund_roi = 
							"
								SET `fund_commission` = CASE `user_id`
							";
						$query_update_users_fund_total = 
							"
								`fund_total` = CASE `user_id`
							";
						$query_update_users_update_date = 
							"
								`update_date` = CASE `user_id`
							";

						foreach ($receiver_wallet_update_array as $row) {
							$user_id_receiver = $row['user_id_receiver'];
							$credited_amount = $row['credited_amount'];
							
							$fund_roi = 0;

							$query_fund_roi = mysqli_query($conn, "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'fund_roi' FROM `wallet_transaction` WHERE `user_id`='$user_id_receiver' AND `transaction_type`='roi'");
							if ($rw = mysqli_fetch_array($query_fund_roi)) {
								$fund_roi = $rw['fund_roi'];
							}

							$query_update_users_fund_roi .= 
								"
									WHEN '$user_id_receiver' THEN '$fund_roi'
								";
							$query_update_users_fund_total .= 
								"
									WHEN '$user_id_receiver' THEN `fund_wallet`+`fund_commission`+`fund_salary`
								";
							
							$query_update_users_update_date .= 
								"
									WHEN '$user_id_receiver' THEN '$current_date'
								";
						}

						$query_update_users_fund_roi .= 
							"
								ELSE `fund_commission`
								END,
							";
						$query_update_users_fund_total .= 
							"
								ELSE `fund_total`
								END,
							";
						$query_update_users_update_date .= 
							"
								ELSE `update_date`
								END
							";

						$query_update_users = 
							"
								$query_update_users
								$query_update_users_fund_roi
								$query_update_users_fund_total
								$query_update_users_update_date
							";
						mysqli_query($conn, $query_update_users);																						

					// UPDATE USER FUNDS AFTER INSERTING ROI INTO WALLET TRANSACTION TABLE
				}
			}
			
			if ($value_insert_log != "") {
				$value_insert_log = rtrim($value_insert_log,",");
				$query_insert_log = "$query_insert_log $value_insert_log";
				mysqli_query($conn,$query_insert_log);
			}
		// SET WALLET TRANSACTION INSERT DATA
	}
	
	// BASED ON ROI CALENDAR
		// release_investment_roi();
	// BASED ON ROI CALENDAR

	// BASED ON ROI CALENDAR
		// generate_level_income();
	// BASED ON ROI CALENDAR
	
	// ON DATE 01 AND 16
		if ($today_date == "01" || $today_date == "16") {
			release_level_income_fortnight();
		}
	// ON DATE 01 AND 16

	// generate_fd_income();

	function generate_fd_income() {
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];
		
		$query_transactions = "SELECT `id` AS 'fd_id',`user_id`,`transaction_amount`,TIMESTAMPDIFF(YEAR, `create_date`, DATE(NOW())) AS 'month_created' FROM `transaction_fixeddeposit` WHERE `status`='active' AND TIMESTAMPDIFF(YEAR, `create_date`, DATE(NOW()))>=1";
		$res = mysqli_query($conn,$query_transactions);
		$transactions = mysqli_fetch_all($res,MYSQLI_ASSOC);

		// INSERT INTO FD INTEREST TRANSACTION AND ACTIVITY LOG TABLE
			$query_insert_interest = "INSERT INTO `transaction_fixeddeposit_interest`(`fd_id`, `user_id`, `interest_amount`, `create_date`) VALUES ";
			$value_insert_interest = "";

			$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
			$value_insert_log = "";
		// INSERT INTO FD INTEREST TRANSACTION AND ACTIVITY LOG TABLE

		$id_to_update = "";
		$receiver_wallet_update_array = array();

		
		// SET INTEREST TRANSACTION INSERT DATA
			$j = 0;
			foreach ($transactions as $row) {
				$fd_id = $row['fd_id'];
				$member_id = $row['user_id'];
				$transaction_amount = $row['transaction_amount'];

				$id_to_update .= "$fd_id,";

				$interest_rate = 60;
				if ($transaction_amount >= 1000 && $transaction_amount <= 25000) {
					$interest_rate = 60;
				} elseif ($transaction_amount >= 26000 && $transaction_amount <= 75000) {
					$interest_rate = 90;
				} elseif ($transaction_amount >= 76000 && $transaction_amount <= 150000) {
					$interest_rate = 120;
				} elseif ($transaction_amount >= 151000) {
					$interest_rate = 150;
				}

				$interest_paid = $transaction_amount*$interest_rate/100;
				$interest_paid = round($interest_paid,2);

				// CHECK TRANSACTION EXIST
					$count_exist = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `transaction_fixeddeposit_interest` WHERE `fd_id`='$fd_id' AND `interest_amount`='$interest_paid' AND `create_date` LIKE '%$today_date_only%'"));
				// CHECK TRANSACTION EXIST

				if ($count_exist == 0) {
					$value_insert_interest .= "('$fd_id', '$member_id', '$interest_paid', '$current_date'),";
					
					$value_insert_log .= "('$member_id', 'fd_matured', 'Credited $interest_paid As Interest', 'SYSTEM', '$current_date'),";

					$receiver_wallet_update_array[$j]['fd_id'] = $fd_id;
					$receiver_wallet_update_array[$j]['user_id_receiver'] = $member_id;
					$receiver_wallet_update_array[$j]['credited_amount'] = $interest_paid;
					$j++;					
				}
			}

		if ($value_insert_interest != "") {
			$value_insert_interest = rtrim($value_insert_interest,",");
			$query_insert_interest = "$query_insert_interest $value_insert_interest";
			if (mysqli_query($conn,$query_insert_interest)) {
				// UPDATE USER FUNDS AFTER INSERTING ROI INTO INTEREST TRANSACTION TABLE
					$query_update_wallets = 
						"
							UPDATE `wallets`
						";
					$query_update_wallets_wallet_fd = 
						"
							SET `wallet_fd` = CASE `user_id`
						";
					$query_update_wallets_update_date = 
						"
							`update_date` = CASE `user_id`
						";
						
					
					foreach ($receiver_wallet_update_array as $row) {
						$fd_id = $row['fd_id'];
						$user_id_receiver = $row['user_id_receiver'];
						$credited_amount = $row['credited_amount'];
						
						$query_interest = "SELECT SUM(`interest_amount`) AS 'total_interest_paid' FROM `transaction_fixeddeposit_interest` WHERE `user_id`='$user_id_receiver' AND `create_date` LIKE '%$today_date_only%'";
						$query = mysqli_query($conn,$query_interest);
						$res = mysqli_fetch_array($query);
						extract($res);

						$query_update_wallets_wallet_fd .= 
							"
								WHEN '$user_id_receiver' THEN `wallet_fd`+$total_interest_paid
							";
						$query_update_wallets_update_date .= 
							"
								WHEN '$user_id_receiver' THEN '$current_date'
							";
					}

					$query_update_wallets_wallet_fd .= 
						"
							ELSE `wallet_fd`
							END,
						";
					$query_update_wallets_update_date .= 
						"
							ELSE `update_date`
							END
						";

					$query_update_wallets = 
						"
							$query_update_wallets
							$query_update_wallets_wallet_fd
							$query_update_wallets_update_date
						";
					mysqli_query($conn, $query_update_wallets);
				// UPDATE USER FUNDS AFTER INSERTING ROI INTO INTEREST TRANSACTION TABLE
				
				if ($id_to_update != "") {
					$id_to_update = rtrim($id_to_update,",");
					$query_update_fd = "UPDATE `transaction_fixeddeposit` SET `status`='matured', `update_date`='$current_date' WHERE `id` IN ($id_to_update)";
					mysqli_query($conn, $query_update_fd);
				}
			}
		}

		if ($value_insert_log != "") {
			$value_insert_log = rtrim($value_insert_log,",");
			$query_insert_log = "$query_insert_log $value_insert_log";
			mysqli_query($conn,$query_insert_log);
		}
	}

	function release_investment_roi() {
		$company_payment_amount = 51000;
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];
		
		$level_array = get_level_data_for_wallet();

		$query_investment = "SELECT `wallets`.`user_id`, `wallets`.`wallet_investment` FROM `wallets` LEFT JOIN `users` ON `users`.`user_id`=`wallets`.`user_id` WHERE `wallets`.`wallet_investment`>0 AND `users`.`status`='active' ORDER BY `wallets`.`wallet_investment` DESC";
		//echo $query_investment;
		$res = mysqli_query($conn, $query_investment);
		$transactions = mysqli_fetch_all($res, MYSQLI_ASSOC);

		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE
			$query_insert_wallet_transaction = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `user_id`, `from_user_id`, `transaction_amount`, `create_date`) VALUES ";
			$value_insert_wallet_transaction = "";

			$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
			$value_insert_log = "";
		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE

		$receiver_wallet_update_array = array();

		// SET WALLET TRANSACTION INSERT DATA
			$j = 0;
			foreach ($transactions as $row) {
				$user_id_receiver = $row['user_id'];
				$user_investment = $row['wallet_investment'];
		
				// NO USER INVESTMENT
					if ($user_investment == "" || $user_investment == 0) {
						continue;
					}
				// NO USER INVESTMEN
				if ($user_investment >= 1000 && $user_investment <= 25000) {
					$roi_percent = 4;
				} else if ($user_investment >= 26000 && $user_investment <= 75000) {
					$roi_percent = 6;
				} else if ($user_investment >= 76000 && $user_investment <= 150000) {
					$roi_percent = 8;
				} else if ($user_investment >= 151000) {
					$roi_percent = 10;
				} else {
					continue;
				}
				
				$credited_amount = ($user_investment*$roi_percent)/3000;
				$credited_amount = round($credited_amount,2);
				
				// CHECK TRANSACTION EXIST
					$count_exist = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `wallet_transaction` WHERE `transaction_type`='roi' AND `user_id`='$user_id_receiver' AND `from_user_id`='$user_id_receiver' AND `transaction_amount`='$credited_amount' AND `create_date` LIKE '%$today_date_only%'"));
				// CHECK TRANSACTION EXIST

				if ($count_exist == 0) {
					// print_r("From: $user_id_donor >> Level: $user_level >> To: $user_id_receiver >> Amount: $credited_amount");					
					
					$value_insert_wallet_transaction .= "('roi', 'credit', '$user_id_receiver', '$user_id_receiver', '$credited_amount', '$current_date'),";
					
					$value_insert_log .= "('$user_id_receiver', 'investment_roi', 'Credited $credited_amount', 'SYSTEM', '$current_date'),";

					$receiver_wallet_update_array[$j]['user_id_receiver'] = $user_id_receiver;
					$receiver_wallet_update_array[$j]['credited_amount'] = $credited_amount;
					$j++;
				}
			}
			
			if ($value_insert_wallet_transaction != "") {
				$value_insert_wallet_transaction = rtrim($value_insert_wallet_transaction,",");
				$query_insert_wallet_transaction = "$query_insert_wallet_transaction $value_insert_wallet_transaction";
				try {
				//	echo mysqli_query($conn,$query_insert_wallet_transaction);
			
				if (mysqli_query($conn,$query_insert_wallet_transaction)) {
					
					echo "query running";
					// UPDATE USER FUNDS AFTER INSERTING ROI INTO WALLET TRANSACTION TABLE
						$query_update_wallets = 
							"
								UPDATE `wallets`
							";
						$query_update_wallets_wallet_roi = 
							"
								SET `wallet_roi` = CASE `user_id`
							";
						$query_update_wallets_update_date = 
							"
								`update_date` = CASE `user_id`
							";
							
						
						foreach ($receiver_wallet_update_array as $row) {
							$user_id_receiver = $row['user_id_receiver'];
							$credited_amount = $row['credited_amount'];
							
							$query_update_wallets_wallet_roi .= 
								"
									WHEN '$user_id_receiver' THEN `wallet_roi`+$credited_amount
								";
							$query_update_wallets_update_date .= 
								"
									WHEN '$user_id_receiver' THEN '$current_date'
								";
						}

						$query_update_wallets_wallet_roi .= 
							"
								ELSE `wallet_roi`
								END,
							";
						$query_update_wallets_update_date .= 
							"
								ELSE `update_date`
								END
							";

						$query_update_wallets = 
							"
								$query_update_wallets
								$query_update_wallets_wallet_roi
								$query_update_wallets_update_date
							";
						mysqli_query($conn, $query_update_wallets);

					// UPDATE USER FUNDS AFTER INSERTING ROI INTO WALLET TRANSACTION TABLE
				}
				else{
					echo "query not running";
				}
			} catch (Exception $ex) {
				// handle the exception
				echo $ex;
				}
			}
			if ($value_insert_log != "") {
				$value_insert_log = rtrim($value_insert_log,",");
				$query_insert_log = "$query_insert_log $value_insert_log";
				mysqli_query($conn,$query_insert_log);
			}
		// SET WALLET TRANSACTION INSERT DATA
	}
	
	function release_level_income_fortnight() {
		$company_payment_amount = 51000;
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];

		$query_level_income = "SELECT `user_id`, SUM(transaction_amount) AS 'level_income' FROM `wallet_transaction` WHERE `transaction_type` = 'level_commission' AND `status`=2 AND `create_date`<'$today_date_only' GROUP BY user_id";
		$res = mysqli_query($conn, $query_level_income);
		$pending_transactions = mysqli_fetch_all($res, MYSQLI_ASSOC);

		$query_update_wallets = 
			"
				UPDATE `wallets`
			";
		$query_update_wallets_wallet_commission = 
			"
				SET `wallet_commission` = CASE `user_id`
			";
		$query_update_wallets_update_date = 
			"
				`update_date` = CASE `user_id`
			";
			
		foreach ($pending_transactions as $transaction) {
			$user_id_receiver = $transaction['user_id'];
			$level_income = $transaction['level_income'];

			$query_update_wallets_wallet_commission .= 
				"
					WHEN '$user_id_receiver' THEN `wallet_commission`+$level_income
				";
			$query_update_wallets_update_date .= 
				"
					WHEN '$user_id_receiver' THEN '$current_date'
				";
		}

		$query_update_wallets_wallet_commission .= 
			"
				ELSE `wallet_commission`
				END,
			";
		$query_update_wallets_update_date .= 
			"
				ELSE `update_date`
				END
			";

		$query_update_wallets = 
			"
				$query_update_wallets
				$query_update_wallets_wallet_commission
				$query_update_wallets_update_date
			";

		$query_approve = "UPDATE `wallet_transaction` SET `status`='1', `updated_by`='SYSTEM', `update_date`='$current_date' WHERE `transaction_type` = 'level_commission' AND `status`=2 AND `create_date`<'$today_date_only'";
		$res = mysqli_query($conn,$query_approve);
		$count_record_update = mysqli_affected_rows($conn);
		if ($count_record_update > 0) {  
			mysqli_query($conn, $query_update_wallets);
		}
	}

	function generate_level_income() {
		$company_payment_amount = 51000;
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$today_date_only = $GLOBALS['today_date_only'];
		
		$level_array = get_level_data_for_wallet();

		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE
			$query_insert_wallet_transaction = "INSERT INTO `wallet_transaction`(`transaction_type`, `transaction_mode`, `level`, `user_id`, `from_user_id`, `transaction_amount`, `status`, `create_date`) VALUES ";
			$value_insert_wallet_transaction = "";

			$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
			$value_insert_log = "";
		// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE

		$receiver_wallet_update_array = array();

		// SET WALLET TRANSACTION INSERT DATA
			$j = 0;
			foreach ($level_array as $row) {
				$user_id_donor = $row['user_id'];
				$user_level = $row['level'];
				$user_id_receiver = $row['user_id_up'];	//ACTIVE FETCHED
				$wallet_amount_percent = $row['wallet_amount_percent'];
				$commission_amount_percent = $row['commission_amount_percent'];
				$donor_investment = $row['donor_investment'];
				$receiver_investment = $row['receiver_investment'];
				
				// NO USER LEVEL DATA
					if ($user_level == "") {
						continue;
					}
				// NO USER LEVEL DATA
				
				// NO INVESTMENT BY ANY PARTY
					if ($donor_investment == 0 || $receiver_investment == 0) {
						continue;
					}
				// NO INVESTMENT BY ANY PARTY

				// CHECK DIRECT JOINING FOR LEVEL INCOME
					// $query_check_level = "SELECT * FROM `levels` WHERE `level` = 1 AND `user_id_up` LIKE '$user_id_receiver'";
					$query_check_level = "SELECT `levels`.`id` FROM `levels` LEFT JOIN `wallets` ON `wallets`.`user_id`=`levels`.`user_id` WHERE `level` = 1 AND `user_id_up` LIKE '$user_id_receiver' AND `wallet_investment`>0";
					$res = mysqli_query($conn,$query_check_level);
					$count_direct_joining = mysqli_num_rows($res);

					if ($count_direct_joining < $user_level) {
						continue;
					}
				// CHECK DIRECT JOINING FOR LEVEL INCOME
				
				// CHECK ACTIVE STATUS OF DONOR ID
					$query = "SELECT `user_id` FROM `users` WHERE `user_id`='$user_id_donor' AND `status`='active'";
					$res = mysqli_query($conn,$query);
					$count_status = mysqli_num_rows($res);
					if ($count_status == 0) {
						continue;
					}
				// CHECK ACTIVE STATUS OF DONOR ID
				
				// $credited_amount = ($company_payment_amount*$commission_amount_percent)/3000;

				// if ($donor_investment > $receiver_investment) {
				// 	$credited_amount = ($receiver_investment*$commission_amount_percent)/100;
				// } else {
				// 	$credited_amount = ($donor_investment*$commission_amount_percent)/100;
				// }

				if (($receiver_investment >= 151000) || ($receiver_investment > $donor_investment)) {
					$credited_amount = ($donor_investment*$commission_amount_percent)/3000;
				} else {
					$credited_amount = ($receiver_investment*$commission_amount_percent)/3000;
				}
				$credited_amount = round($credited_amount,2);
						
				// CHECK TRANSACTION EXIST
					$count_exist = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `wallet_transaction` WHERE `transaction_type`='level_commission' AND `user_id`='$user_id_receiver' AND `from_user_id`='$user_id_donor' AND `transaction_amount`='$credited_amount' AND `create_date` LIKE '%$today_date_only%'"));
				// CHECK TRANSACTION EXIST

				if ($count_exist == 0) {
					// print_r("From: $user_id_donor >> Level: $user_level >> To: $user_id_receiver >> Amount: $credited_amount");					
					
					$value_insert_wallet_transaction .= "('level_commission', 'credit', '$user_level', '$user_id_receiver', '$user_id_donor', '$credited_amount', '2', '$current_date'),";
					
					$value_insert_log .= "('$user_id_receiver', 'level_commission', 'Credited $credited_amount', 'SYSTEM', '$current_date'),";

					$receiver_wallet_update_array[$j]['user_id_receiver'] = $user_id_receiver;
					$receiver_wallet_update_array[$j]['credited_amount'] = $credited_amount;
					$j++;
				}
			}
			
			if ($value_insert_wallet_transaction != "") {
				$value_insert_wallet_transaction = rtrim($value_insert_wallet_transaction,",");
				$query_insert_wallet_transaction = "$query_insert_wallet_transaction $value_insert_wallet_transaction";
				mysqli_query($conn,$query_insert_wallet_transaction);
			}
			
			if ($value_insert_log != "") {
				$value_insert_log = rtrim($value_insert_log,",");
				$query_insert_log = "$query_insert_log $value_insert_log";
				mysqli_query($conn,$query_insert_log);
			}
		// SET WALLET TRANSACTION INSERT DATA
	}

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
				
				$updatedtransaction_amount = $wallet_investment - $transaction_amount;
				$updatetransactionamountwallet = "UPDATE wallets SET wallet_investment =  $updatedtransaction_amount , update_date = '$current_date' WHERE user_id = $user_id";
				mysqli_query($conn, $updatetransactionamountwallet);
						
			}
			//echo join(', ', $updatedwalletid);
		//	echo "Status updated successfully.";	
		}
	}
?>