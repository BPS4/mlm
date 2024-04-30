<!DOCTYPE html>
<html lang="en">
<link rel="shortcut icon" href="../../assets/images/favicon.svg" type="image/x-icon">
<?php
	// COLLEGE DASHBOARD PAGE
	ob_start();
	require_once("../../db_connect.php");
	session_start();

	mysqli_query($conn, "set names 'utf8'");  //-------WORKING UTF8 CODE------//

	//-------CURRENT DATE AND TIME TO FEED---------//
	date_default_timezone_set('Asia/Kolkata');
	$current_date = date('Y-m-d H:i:s');

	$company_payment_amount = 51000;

	extract($_REQUEST);

	// check login status
		// if (isset($_GET['name']) && isset($_SESSION['session_id_admin'])) {
		if (isset($_SESSION['session_id_admin'])) {
			// $name = json_decode(base64_decode($_GET['name']));    //DECODE
			// $name_encode = base64_encode(json_encode($name));     //ENCODE

			$user_id = $_SESSION['user_id_admin'];
			$session_id = $_SESSION['session_id_admin'];
			$name = $_SESSION['name_admin'];    //DECODE
			$name_encode = base64_encode(json_encode($name));     //ENCODE
			$user_id_encode = base64_encode(json_encode($user_id));     //ENCODE

			// $user_id = strtoupper($user_id);

			// USER AUTHENTICATION
				$session_id_pass = $_SESSION['session_id_admin'];
				$query = mysqli_query($conn, "SELECT `unique_id`, `session_id` FROM `login_sessions` WHERE `unique_id`='$user_id' ORDER BY `create_date` DESC LIMIT 1");
				if ($row = mysqli_fetch_array($query)) {
					$session_id = $row['session_id'];
					// $name = $row['name'];

					// ADMIN LOGIN MULTIPLE LOGIN ALLOWED 11-02-2022
					// if ($session_id_pass != $session_id) {
					//   echo "<script>alert('Session Expired. Login Again!!!');</script>";
					//   echo "Redirecting...Please Wait";
					//   header("Refresh:0, url=../logout/");
					//   exit;
					// }
				} else {
					echo "<script>alert('You Are Not An Authorised Person!!!');</script>";
					echo "Redirecting...Please Wait";
					header("Refresh:0, url=../logout/");
					exit;
				}
			// USER AUTHENTICATION
		} else {
			echo "<script>alert('You Are Not Logged In!!!');</script>";
			echo "Redirecting...Please Wait";
			header("Refresh:0, url=../logout/");
			exit;
		}
	// check login status

	$allowed_txn = array("fresh");
	$type = "";
	// CHECK AVAILABILITY
		if (isset($_GET['type'])) {
			$type = json_decode(base64_decode($_GET['type']));    //--DECODE THE SECRET CODE PASSED--//
			$type_encode = base64_encode(json_encode($type));     //ENCODE
			$type_caps = strtoupper($type);

			if (!in_array($type, $allowed_txn)) {
				// echo "Match Not Found";
				echo "<script>alert('Passed Parameter Is Not Valid!!!');</script>";
				echo "Redirecting...Please Wait";
				header("Refresh:0, url=view_transaction_pending.php");
				exit;
			}

			if ($type == "fresh") {
				$type_caps = "INVESTMENT";
			}

			$header = "$type_caps Transactions Pending For Approval";
			$check_type = "AND `fund_transaction`.`transaction_type`='$type'";
		} else {
			$header = "All Transactions Pending For Approval";
			$check_type = "";
		}
	// CHECK AVAILABILITY

	// DEFAULT
		$page_id_home = 1;
		
		$pan_upload = $aadhaar_upload = 0;

	// $count_school = mysqli_num_rows(mysqli_query($conn, "SELECT `udise_code` FROM `schools` WHERE `verified`=1 AND `allowed_internship`=1 "));
	// $count_trainee = mysqli_num_rows(mysqli_query($conn, "SELECT `id_member` FROM `trainee` WHERE `batch`='$batch' AND `college_code` IS NOT NULL "));
	// if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `visits` FROM `counter` WHERE `page_id`='$page_id_home' "))) {
	// 	$count_visitor = $rw['visits'];
	// }
	
		$url_dir = "../../assets/files/transaction";
		$msg = $msg_type = "";
		$error = false;

	function get_level_data_for_wallet($txn_id) {
		$conn = $GLOBALS['conn'];
		// $query_lvl = "SELECT `fund_transaction`.`user_id`,`levels`.`level`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent` FROM `fund_transaction` LEFT JOIN `levels` ON `levels`.`user_id`=`fund_transaction`.`user_id` WHERE `fund_transaction`.`id`='$txn_id'";
		// GET ONLY ACTIVE IDS
		$query_lvl = "SELECT `fund_transaction`.`user_id`,`levels`.`level`,`levels`.`user_id_up`,`levels`.`wallet_amount_percent`,`levels`.`commission_amount_percent` FROM `fund_transaction` LEFT JOIN `levels` ON `levels`.`user_id`=`fund_transaction`.`user_id` LEFT JOIN `users` ON `users`.`user_id`=`levels`.`user_id_up` WHERE `users`.`status`='active' AND `fund_transaction`.`id`='$txn_id'";
		$res = mysqli_query($conn, $query_lvl);
		$records = mysqli_fetch_all($res, MYSQLI_ASSOC);
		return $records;
	}

	// APPROVE TRANSACTION
		$msg=$type_msg="";
			
		// APPROVED COUNT
			$count_approved = 0;

		// INSERT QUERY FOR ALLOTMENT ACTIVITY
			// $query_insert_activity_allotment = "INSERT INTO `activity_allotment`(`activity`, `semester`, `id_member`, `choice_allotted`, `performed_by`, `create_date`) VALUES";
			// $query_insert_activity_allotment_values = "";

		if(isset($_POST['submit'])){
			if(!empty($_POST['approve_transaction'])) {
				$query_update = 
					"
						UPDATE `fund_transaction`                            
					";

				$query_update_status = 
					"
						SET `status` = CASE `id`
					";
				$query_updated_by = 
					"
						`updated_by` = CASE `id`
					";
				$query_update_date = 
					"
						`update_date` = CASE `id`
					";

				// $did=json_encode($_POST['approve_transaction']);
				// $did_encode = base64_encode(json_encode($_POST['approve_transaction']));     //ENCODE

				// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE
					$query_insert_wallet_transaction = "INSERT INTO `wallet_transaction`(`transaction_type`, `user_id`, `from_user_id`, `transaction_amount`, `comment`, `create_date`) VALUES ";
					$value_insert_wallet_transaction = "";

					$query_insert_log = "INSERT INTO `activity_log`(`user_id`, `action`, `activity`, `performed_by`, `create_date`) VALUES ";
					$value_insert_log = "";
      				// mysqli_query($conn, $query_insert_form_download_log);
				// INSERT INTO WALLET TRANSACTION AND ACTIVITY LOG TABLE
				
				$user_status_update_array = array();
				$s = 0;

				foreach($_POST['approve_transaction'] as $key => $value) {
                    $txn_id = $value;
                    
                    // No Transaction Selected
                        if ($txn_id=="") {
                            continue;
                        }
                    // No Transaction Selected
					
					$query_txn_type = "SELECT `transaction_type` AS 'txn_type',`user_id` AS 'uid', `transaction_amount` AS 'txn_amount' FROM `fund_transaction` WHERE `id`='$txn_id'";
					$res = mysqli_query($conn,$query_txn_type);
					$res = mysqli_fetch_array($res);
					extract($res);

					$level_array = get_level_data_for_wallet($txn_id);

					switch ($txn_type) {
						case 'fresh':
							$comment = NULL;
							break;
						
						case 'renewal':
							$comment = "direct_renewal";
							break;
						
						case 'upgrade':
							$comment = "direct_upgrade";
							break;
					}
					
					$receiver_wallet_update_array = array();

					// $txn_amount = $txn_transaction_amount[$key];		//	CREATING ERROR FOR ONE SELECTION
					// $uid = $txn_user_id_member[$key];

					// FOR STATUS UPDATE FOR TRANSACTION USER
						$user_status_update_array[$s]['user_id_donor'] = $uid;
						$user_status_update_array[$s]['txn_type'] = $txn_type;
						$s++;
					// FOR STATUS UPDATE FOR TRANSACTION USER

					// SET WALLET TRANSACTION INSERT DATA
						$j = 0;
						foreach ($level_array as $row) {
							$user_id_donor = $row['user_id'];
							$user_level = $row['level'];
							$user_id_receiver = $row['user_id_up'];
							$wallet_amount_percent = $row['wallet_amount_percent'];
							$commission_amount_percent = $row['commission_amount_percent'];
							
							// NO USER LEVEL DATA
								if ($user_level == "") {
									continue;
								}
							// NO USER LEVEL DATA
								
								// $credited_amount = ($company_payment_amount*$wallet_amount_percent)/100;
								$credited_amount = ($txn_amount*$wallet_amount_percent)/100;	// TXN AMOUNT DIVIDED PERCENT-WISE

								// CHECK TRANSACTION EXIST
									$count_exist = 0;
									if ($txn_type == "fresh") {
										$count_exist = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `wallet_transaction` WHERE `transaction_type`='direct' AND `user_id`='$user_id_receiver' AND `from_user_id`='$user_id_donor' AND `transaction_amount`='$credited_amount'"));
									}
								// CHECK TRANSACTION EXIST

								if ($count_exist == 0) {
									$value_insert_wallet_transaction .= "('direct', '$user_id_receiver', '$user_id_donor', '$credited_amount', '$comment', '$current_date'),";
									
									$value_insert_log .= "('$user_id_receiver', 'direct_commission', 'Credited $credited_amount', '$user_id', '$current_date'),";

									$receiver_wallet_update_array[$j]['user_id_receiver'] = $user_id_receiver;
									$receiver_wallet_update_array[$j]['credited_amount'] = $credited_amount;
									$j++;
								}
						}
					// SET WALLET TRANSACTION INSERT DATA
					
					// UPDATE QUERY
						$query_update_status .= 
							"
								WHEN '$txn_id' THEN '1'
							";
						$query_updated_by .= 
							"
								WHEN '$txn_id' THEN '$user_id'
							";
						$query_update_date .= 
							"
								WHEN '$txn_id' THEN '$current_date'
							";
					// UPDATE QUERY

					$count_already_approved = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `fund_transaction` WHERE `id`='$txn_id' AND `status`=2"));
					if ($count_already_approved > 0) {
						$count_approved++;
					}
				}

				if ($count_approved>0) {
					$query_update_status .= 
                        "
                            ELSE `status`
                            END,
                        ";
                    $query_updated_by .= 
                        "
                            ELSE `updated_by`
                            END,
                        ";
                    $query_update_date .= 
                        "
                            ELSE `update_date`
                            END
                        ";

                    $query_update = 
                        "
                            $query_update
                            $query_update_status
                            $query_updated_by
                            $query_update_date
                        ";

                        mysqli_query($conn, $query_update);
                        $rows_affected_update = mysqli_affected_rows($conn);
						if ($rows_affected_update) {
							// INSERT INTO WALLET TRANSACTION
								if ($value_insert_wallet_transaction != "") {

									$value_insert_wallet_transaction = rtrim($value_insert_wallet_transaction, ",");
									$query_insert_wallet_transaction .= "$value_insert_wallet_transaction";

									if (mysqli_query($conn, "$query_insert_wallet_transaction")) {
										// UPDATE USER FUNDS AFTER APPROVING THE TRANSACTION AND INSERTING INTO WALLET TRANSACTION TABLE
											$query_update_users = 
												"
													UPDATE `users`
												";
											$query_update_users_fund_wallet = 
												"
													SET `fund_wallet` = CASE `user_id`
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
												
												$fund_wallet = 0;

												$query_fund_wallet = mysqli_query($conn, "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'fund_wallet' FROM `wallet_transaction` WHERE `user_id`='$user_id_receiver' AND `transaction_type`='direct'");
												if ($rw = mysqli_fetch_array($query_fund_wallet)) {
													$fund_wallet = $rw['fund_wallet'];
												}

												$query_update_users_fund_wallet .= 
													"
														WHEN '$user_id_receiver' THEN '$fund_wallet'
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

											$query_update_users_fund_wallet .= 
												"
													ELSE `fund_wallet`
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
													$query_update_users_fund_wallet
													$query_update_users_fund_total
													$query_update_users_update_date
												";
											mysqli_query($conn, $query_update_users);																						

										// UPDATE USER FUNDS AFTER APPROVING THE TRANSACTION AND INSERTING INTO WALLET TRANSACTION TABLE
									}
								}

								if ($value_insert_log != "") {
									$value_insert_log = rtrim($value_insert_log, ",");
									$query_insert_log .= "$value_insert_log";
									mysqli_query($conn, "$query_insert_log");
								}
							// INSERT INTO WALLET TRANSACTION
							
							// FOR STATUS UPDATE TO ACTIVE FOR TRANSACTION USER
								$query_update_status_users = 
									"
										UPDATE `users`
									";
								$query_update_status_users_status = 
									"
										SET `status` = CASE `user_id`
									";
								$query_update_status_users_active_date = 
									"
										`active_date` = CASE `user_id`
									";
								$query_update_status_users_update_date = 
									"
										`update_date` = CASE `user_id`
									";

								foreach ($user_status_update_array as $row) {
									
										$user_id_donor = $row['user_id_donor'];
										$txn_type = $row['txn_type'];

										$status_new = "active";		// REGARDLESS OF TXN TYPE
										
										$query_update_status_users_status .= 
											"
												WHEN '$user_id_donor' THEN '$status_new'
											";

										// ONLY FOR TRANSACTION TYPE FRESH
											if ($txn_type == "fresh") {
												$query_update_status_users_active_date .= 
													"
														WHEN '$user_id_donor' THEN '$current_date'
													";
											}											
										// ONLY FOR TRANSACTION TYPE FRESH
										
										$query_update_status_users_update_date .= 
											"
												WHEN '$user_id_donor' THEN '$current_date'
											";
								}

								$query_update_status_users_status .= 
									"
										ELSE `status`
										END,
									";
								$query_update_status_users_active_date .= 
									"
										ELSE `active_date`
										END,
									";
								$query_update_status_users_update_date .= 
									"
										ELSE `update_date`
										END
									";

								$query_update_status_users = 
									"
										$query_update_status_users
										$query_update_status_users_status
										$query_update_status_users_active_date
										$query_update_status_users_update_date
									";
								mysqli_query($conn, $query_update_status_users);
							// FOR STATUS UPDATE TO ACTIVE FOR TRANSACTION USER

							// SET ACTIVE MEMBERS DATA
								set_active_member_rank($uid);
							// SET ACTIVE MEMBERS DATA

							$msg="{$count_approved} Transactions Approved Successfully!";
							$msg_type="success";
						} else {
							$msg="Error occurred in Approval! Try Again";
							$msg_type="danger";
						}
				} else {
					$msg="Transaction Already Approved OR No Transaction Selected! Try Again";
					$msg_type="warning";
				}
				
				// INSERT INTO ALLOTMENT ACTIVITY
					// $query_insert_activity_allotment_values = rtrim($query_insert_activity_allotment_values, ",");
					// mysqli_query($conn, "$query_insert_activity_allotment $query_insert_activity_allotment_values");
			} else {
				$msg="No Transaction Selected for Approval! Try Again";
				$msg_type="default";
			}
		}
	// APPROVE TRANSACTION
	
	function set_active_member_rank($user_id_new) {
		$conn = $GLOBALS['conn'];
		$current_date = $GLOBALS['current_date'];
		$user_id_admin = $_SESSION['user_id_admin'];
		$today_date = date('Y-m-d');
		
		$member_required_for_champion = 51000;
		$member_required_for_diamond = 5100;
		$member_required_for_gold = 500;
		$member_required_for_silver = 100;

		// UPDATE SELF RANK IN CASE OF ACTIVE MEMBERS COMPLETED BEFORE ACTIVATION
			$query_users = "SELECT `status`, `level1_member_active`, `level2_member_active`, `level3_member_active`, `level4_member_active`, `level5_member_active`,`active_date` FROM `users` WHERE `user_id`='$user_id_new' AND `status`='active' AND `active_date` LIKE '%$today_date%'";
			$res = mysqli_query($conn,$query_users);
			if ($row = mysqli_fetch_array($res)) {
				$m_status = $row['status'];
				$m_l1 = $row['level1_member_active'];
				$m_l2 = $row['level2_member_active'];
				$m_l3 = $row['level3_member_active'];
				$m_l4 = $row['level4_member_active'];
				$m_l5 = $row['level5_member_active'];

				$new_rank = "bronze";

                if ($m_l5>=$member_required_for_champion) {
                    $new_rank = "champion";
                } else if ($m_l4>=$member_required_for_diamond) {
                    $new_rank = "diamond";
                } else if ($m_l3>=$member_required_for_gold) {
                    $new_rank = "gold";
                } else if ($m_l2>=$member_required_for_silver) {
                    $new_rank = "silver";
                } else {
                    $new_rank = "bronze";
                }

			}
		// UPDATE SELF RANK IN CASE OF ACTIVE MEMBERS COMPLETED BEFORE ACTIVATION
		
		// foreach ($users as $item) {
		// 	$member_id = $item['user_id'];

		// 	print_r($member_id);

		// }
				
		// GET MEMBER LEVEL IN UPLINE TILL LEVEL 5
			$level = 0;
			$levels_array[0] = $user_id_new;
			while ($level < 6) {
				$sp_id_obtained = get_level_members($conn,$level,$levels_array[$level]);
				$level++;
				if ($sp_id_obtained != "NULL") {
					$levels_array[$level] = $sp_id_obtained;
				} else {
					break;
				}
			}
		// GET MEMBER LEVEL IN UPLINE TILL LEVEL 5

		// UPDATE THE UPLINE MEMBERS FOR DOWNLINE ACTIVE MEMBER COUNT
			// CHECK LEVEL MEMBER IS UPDATING OR NOT
				$set_level1 = $set_level2 = $set_level3 = $set_level4 = $set_level5 = 0;
			// CHECK LEVEL MEMBER IS UPDATING OR NOT

			$query_update = 
				"
					UPDATE `users`
				";

			$query_update_level1_member = 
				"
					SET `level1_member_active` = CASE `user_id`
				";

			$query_update_level2_member = 
				"
					`level2_member_active` = CASE `user_id`
				";

			$query_update_level3_member = 
				"
					`level3_member_active` = CASE `user_id`
				";

			$query_update_level4_member = 
				"
					`level4_member_active` = CASE `user_id`
				";

			$query_update_level5_member = 
				"
					`level5_member_active` = CASE `user_id`
				";

			$query_update_date = 
				"
					`update_date` = CASE `user_id`
				";

			foreach ($levels_array as $key => $value) {
				$user_level = $key;
				$user_id_up = $value;
				
				if ($user_id_up == "" || $user_level == 0 || $user_level > 5) {
					continue;
				}
				
				switch ($user_level) {
					case '1':
						$set_level1 = 1;

						$query_update_level1_member .= 
							"
								WHEN '$user_id_up' THEN `level1_member_active`+1
							";
						break;
					
					case '2':
						$set_level2 = 1;
						
						$query_update_level2_member .= 
							"
								WHEN '$user_id_up' THEN `level2_member_active`+1
							";
						break;

					case '3':
						$set_level3 = 1;
						
						$query_update_level3_member .= 
							"
								WHEN '$user_id_up' THEN `level3_member_active`+1
							";
						break;
					
					case '4':
						$set_level4 = 1;

						$query_update_level4_member .= 
							"
								WHEN '$user_id_up' THEN `level4_member_active`+1
							";
						break;
					
					case '5':
						$set_level5 = 1;

						$query_update_level5_member .= 
							"
								WHEN '$user_id_up' THEN `level5_member_active`+1
							";
						break;
				}
				
				$query_update_date .= 
					"
						WHEN '$user_id_up' THEN '$current_date'
					";
					
			}

			$query_update_level1_member .= 
				"
					ELSE `level1_member_active`
					END,
				";
			$query_update_level2_member .= 
				"
					ELSE `level2_member_active`
					END,
				";
			$query_update_level3_member .= 
				"
					ELSE `level3_member_active`
					END,
				";
			$query_update_level4_member .= 
				"
					ELSE `level4_member_active`
					END,
				";
			$query_update_level5_member .= 
				"
					ELSE `level5_member_active`
					END,
				";
			$query_update_date .= 
				"
					ELSE `update_date`
					END
				";

			$query_update = "$query_update";
			if ($set_level1 == 1) {
				$query_update .= "$query_update_level1_member";
			}
			if ($set_level2 == 1) {
				$query_update .= "$query_update_level2_member";
			}
			if ($set_level3 == 1) {
				$query_update .= "$query_update_level3_member";
			}
			if ($set_level4 == 1) {
				$query_update .= "$query_update_level4_member";
			}
			if ($set_level5 == 1) {
				$query_update .= "$query_update_level5_member";
			}
			$query_update .= "$query_update_date";

			// UPDATE MEMBER COUNT
				mysqli_query($conn, $query_update);
			// UPDATE MEMBER COUNT
		// UPDATE THE UPLINE MEMBERS FOR DOWNLINE ACTIVE MEMBER COUNT

		// goto skip;
		// UPDATE USER RANK BASED ON THE MEMBERS COUNT
            // GET DOWNLINE MEMBERS
                $member_count_array = array();
            // GET DOWNLINE MEMBERS
            
            foreach ($levels_array as $key => $value) {
                $user_level = $key;
                $user_id_up = $value;
                
                if ($user_id_up == "" || $user_level == 0 || $user_level > 5) {
                    continue;
                }
                
                // GET DOWNLINE MEMBERS
                    array_push($member_count_array,get_downline_member($user_id_up));
                // GET DOWNLINE MEMBERS
            }
            
            $query_update =
                "
                    UPDATE `users`
                ";

            $query_update_user_rank = 
                "
                    SET `user_rank` = CASE `user_id`
                ";
            $query_update_status =
                "
                    `status` = CASE `user_id`
                ";
            $query_update_date =
                "
                    `update_date` = CASE `user_id`
                ";

            // INSERT QUERY FOR ACTIVITY
                $query_insert_activity_log = "INSERT INTO `activity_log`(`user_id`, `activity`, `performed_by`, `create_date`) VALUES ";
                $query_insert_activity_log_values = "";
			// INSERT QUERY FOR NOTIFICATION
				$query_insert_notification = "INSERT INTO `notification`(`user_id`, `message`, `create_date`) VALUES ";
				$query_insert_notification_values = "";

            foreach ($member_count_array as $data) {
                if ($data == "") {
                    continue;
                }

                $user_id = $data['user_id'];
                $user_rank = $data['user_rank'];
                $level1_member_active = $data['level1_member_active'];
                $level2_member_active = $data['level2_member_active'];
                $level3_member_active = $data['level3_member_active'];
                $level4_member_active = $data['level4_member_active'];
                $level5_member_active = $data['level5_member_active'];
                
                $new_rank = "bronze";

                if ($level5_member_active>=$member_required_for_champion) {
                    $new_rank = "champion";
                } else if ($level4_member_active>=$member_required_for_diamond) {
                    $new_rank = "diamond";
                } else if ($level3_member_active>=$member_required_for_gold) {
                    $new_rank = "gold";
                } else if ($level2_member_active>=$member_required_for_silver) {
                    $new_rank = "silver";
                } else {
                    $new_rank = "bronze";
                }
                    
                // ONLY RANK UPGRADE CASE
                    if ($new_rank != "bronze" && $user_rank != $new_rank) {
                        $query_update_user_rank .= 
                            "
                                WHEN '$user_id' THEN '$new_rank'
                            ";
                        $query_update_status .=
                            "
                                WHEN '$user_id' THEN 'pending_upgrade'
                            ";
                        $query_update_date .=
                            "
                                WHEN '$user_id' THEN '$current_date'
                            ";
                        
						$new_rank_caps = strtoupper($new_rank);
                        $query_insert_activity_log_values .= "('$user_id','Rank Upgrade To $new_rank_caps','$user_id_admin','$current_date'),";
                        $query_insert_notification_values .= "('$user_id','Congratulations on your Rank Upgrade To $new_rank_caps. All your earnings will be freezed until you pay for upgrade. Kindly Pay The Upgrade Fee To Continue All Your Earnings.','$current_date'),";
                    }
                // ONLY RANK UPGRADE CASE
            }

            $query_update_user_rank .= 
                "
                    ELSE `user_rank`
                    END,
                ";
            $query_update_status .=
                "
                    ELSE `status`
                    END,
                ";
            $query_update_date .= 
                "
                    ELSE `update_date`
                    END
                ";

            $query_update = 
                "
                    $query_update
                    $query_update_user_rank
                    $query_update_status
                    $query_update_date
                ";
            
            // UPDATE USER RANK AND INSERT INTO ACTIVITY LOG
                mysqli_query($conn, $query_update);

                if ($query_insert_activity_log_values != "") {
                    $query_insert_activity_log_values = rtrim($query_insert_activity_log_values, ",");
                    $query_insert_activity_log .= "$query_insert_activity_log_values";

                    mysqli_query($conn, $query_insert_activity_log);
                }
                if ($query_insert_notification_values != "") {
                    $query_insert_notification_values = rtrim($query_insert_notification_values, ",");
                    $query_insert_notification .= "$query_insert_notification_values";

                    mysqli_query($conn, $query_insert_notification);
                }
            // UPDATE USER RANK AND INSERT INTO ACTIVITY LOG
        // UPDATE USER RANK BASED ON THE MEMBERS COUNT
	}

	function get_level_members($conn,$level,$user_id_member) {
		$query_get_data = "SELECT `sponsor_id`,`name` FROM `users` WHERE `user_id`='$user_id_member'";
		$res = mysqli_query($conn, $query_get_data);
		if ($row = mysqli_fetch_array($res)) {
			$response['sponsor_id'] = $row['sponsor_id'];
			$response['name'] = $row['name'];
			$response['level'] = $level;
			
			$sponsor_id = $row['sponsor_id'];
		} else {
			$sponsor_id = "NULL";
		}
		
		return($sponsor_id);
	}

	function get_downline_member($user_id) {
		$conn = $GLOBALS['conn'];
		// $res = mysqli_query($conn, "SELECT `user_rank`,`level1_member`, `level2_member`, `level3_member`, `level4_member`, `level5_member` FROM `users` WHERE `user_id`='$user_id'");
		
		// ONLY GET ACTIVE IDs
		$res = mysqli_query($conn, "SELECT `user_rank`,`level1_member_active`, `level2_member_active`, `level3_member_active`, `level4_member_active`, `level5_member_active` FROM `users` WHERE `user_id`='$user_id' AND `status`='active'");
		if ($row = mysqli_fetch_array($res)) {
			$user_rank = $row['user_rank'];
			$level1_member_active = $row['level1_member_active'];
			$level2_member_active = $row['level2_member_active'];
			$level3_member_active = $row['level3_member_active'];
			$level4_member_active = $row['level4_member_active'];
			$level5_member_active = $row['level5_member_active'];
	
			$response['user_id'] = $user_id;
			$response['user_rank'] = $user_rank;
			$response['level1_member_active'] = $level1_member_active;
			$response['level2_member_active'] = $level2_member_active;
			$response['level3_member_active'] = $level3_member_active;
			$response['level4_member_active'] = $level4_member_active;
			$response['level5_member_active'] = $level5_member_active;
	
			// return (json_encode($response));
			return (($response));
		}
	}
	
	// GET PENDING TRANSACTION
		// FOR SELECT QUERIES
			$query_transaction = "SELECT `fund_transaction`.`id`,`fund_transaction`.`transaction_type`,`fund_transaction`.`user_id`,`users`.`name`,`fund_transaction`.`transaction_mode`,`fund_transaction`.`transaction_date`,`fund_transaction`.`transaction_amount`,`fund_transaction`.`transaction_id`,`fund_transaction`.`url_file`,`fund_transaction`.`status`,`fund_transaction`.`create_date` FROM `fund_transaction` LEFT JOIN `users` ON `users`.`user_id`=`fund_transaction`.`user_id` WHERE `fund_transaction`.`to_user_id`='COMPANY' AND `fund_transaction`.`status`=2 $check_type ORDER BY `fund_transaction`.`create_date` ASC";
			$query_transaction = mysqli_query($conn, $query_transaction);
			// FETCH ALL DATA
			$records = mysqli_fetch_all($query_transaction,MYSQLI_ASSOC);
			$count_available_txn = mysqli_num_rows($query_transaction);
	// GET PENDING TRANSACTION
	
	$total_count = mysqli_num_rows($query_transaction);
	// USED FOR CENTER THE TEXT PDF EXPORT
		echo "<input type='hidden' id='total_count' value='$total_count'>";
	
	// DEFAULT ORDER COLUMN SR
		$default_Order = 0;

	// LENGTH MENU
    	$length_menu = "all";
?>

	<head>
		<?php include("head.php"); ?>

		<title><?php echo $header; ?> - Admin Dashboard - Maxizone - <?php echo "$current_date" ?></title>

		<link rel="canonical" href="view_transaction.php">

		<style>
			input[type="checkbox"],#check_all {
				box-shadow: 0px 0px 2px 4px #D9534F;
				margin: 5px !important;
			}
			input[type="checkbox"]:checked,#check_all:checked {
				box-shadow: 0 0 2px 4px #4BBF73;
			}

			/* Labels for checked inputs */
			label.check

			/*check class label*/
				{
				text-align: justify;
				color: black;

			}

			input:checked+label {
				color: green;
				font-weight: bold;
			}

			#check_all:checked+label {
				color:green;
			}
			
			.blink {
				animation: blinker 2s linear infinite both;
			}

			/* FADE IN OUT BLINK ANIMATION */
			@keyframes blinker {
				25%,
				75% {
					opacity: 0;
				}
				12.5%,
				37.5%,
				62.5% {
					opacity: 0.7;
				}
				0%,
				50%,
				100% {
					opacity: 1;
				}
			}

			@page {
				size: ledger LANDSCAPE;
				/*//auto, portrait, landscape or length (2 parameters width and height. sets both equal if only one is provided. % values not allowed.)*/

				margin-top: 0.5cm;
				margin-bottom: 0.5cm;
				margin-left: 0.5cm;
				margin-right: 0.5cm;
			}
			@media print {
				body {
					-webkit-print-color-adjust: exact;
				}
				.dataTables_length, .dataTables_filter, .dt-buttons, .no_print {
					display: none;
					visibility: hidden;
				}
			}
		</style>
	</head>

	<body data-theme="default" data-layout="fluid" data-sidebar-position="left" data-sidebar-behavior="sticky">
		<div class="wrapper">
			<?php include("sidebar.php"); ?>

			<div class="main">
				<?php include("navbar.php"); ?>

				<main class="content">
					<div class="container-fluid p-0">
						<div class="row mb-2 mb-xl-3">
							<div class="col-auto">
								<h3><?php echo $header; ?></h3>
							</div>
						</div>
						<div class="row">
							<div class="col-12">
								<div class="no_print" id="loader"></div>
								<div class="card">
									<div class="card-header no_print" style="margin-bottom:0px;padding: 0.5rem 1.25rem;">
										<h5 class="card-title">Total Records Present <span class="badge bg-success" style="font-weight: bold;"><?php echo $total_count; ?></span></h5>
										<span style="position:absolute;left:0px;margin-left:15px;">
											<a href='./' class="badge bg-warning fs-100" style="text-decoration:none;">
												Dashboard
											</a>
											<b>
												<i class="align-middle" data-feather="chevrons-right"></i>
											</b>
											<a href="view_transaction.php" class="badge bg-success fs-100" style="text-decoration:none;">
												Transaction List
											</a>
											<b>
												<i class="align-middle" data-feather="chevrons-right"></i>
											</b>
											<a href="view_transaction_pending.php" class="badge bg-info fs-100" style="text-decoration:none;">
												Pending Transaction
											</a>
											<a onclick="javascript:window.print()" class="badge bg-danger ms-2" style="text-decoration:none;">
												<i class="align-middle" data-feather="printer" style="border-radius: 50%;margin-right:5px;"></i>
												Print This Page
											</a>
										</span>
										
										<!-- <button type="button" class="btn btn-success my-1" data-bs-toggle="modal" data-bs-target="#ModalCustomerAdd" style="text-decoration:none;position:absolute;right:0px;margin:15px;">
											<i class="align-middle" data-feather="user-plus" style="border-radius: 50%;margin-right:5px;"></i>
											New Customer
										</button> -->
									</div>
									<div class="card-body" style="padding:0.5rem;">
										<marquee class="no_print mt-4" behavior="scroll" direction="left" width="100%" scrollamount="10" style="font-weight:bold;font-style:italic;font-size: medium;">
											<!-- To Approve Transactions Either Select <span style="color: maroon;">Approve All Checkbox</span> or Select <span style="color: maroon;">User IDs Individual Checkbox</span> Then Click <span style="color: maroon;">Approve Transactions</span> Button -->
											<!-- To Approve Transactions Select <span style="color: maroon;">User IDs Individual Checkbox</span> Then Click <span style="color: maroon;">Approve Transactions</span> Button -->
											To Approve/Reject Transactions Click On <span style="color: maroon;">Approve/Reject Transaction</span> Button <span style="color: maroon;"></span> Then Select Option To Approve or Reject It
										</marquee>
										<form action="" method="post" id="formChoiceAllot">
											<div class="d-none no_print" style="margin-bottom:5px;display:flex;justify-content:center;">
												<span class="btn bg-success rounded-pill">
													<input type="submit" name="submit" value="&#x1F5B6; Approve Transactions" style="font-weight:bold;border-style:hidden;background:transparent;color:white;">
													<!-- <input onclick="submitFormChoiceAllot()" name="submit" value="Submit" style="font-weight:bold;border-style:hidden;background:transparent;color:white;"> -->
												</span>
											</div>
											<table id="datatables" class="table table-striped" style="width:100%">
												<colgroup>
													<!-- <col width="5%">
													<col width="10%">
													<col width="5%">
													<col width="10%">
													<col width="5%">
													<col width="5%">
													<col width="15%">
													<col width="15%">
													<col width="15%">
													<col width="5%">
													<col width="5%">
													<col width="5%"> -->
												</colgroup>
												<thead>
													<tr>
														<th>Sr</th>
														<th class="no_print">
															<input class="d-none" type="checkbox" id="check_all" name="approve_all" value="approve_all">
															<label class="d-none" for="check_all">Approve All</label>
															Action
														</th>
														<th style="white-space: nowrap;">User ID</th>
														<th style="white-space: nowrap;">User Name</th>
														<th>Transaction For</th>
														<th class="no_print">Transaction Receipt</th>
														<th>Transaction Mode</th>
														<th>Transaction Amount</th>
														<th>Transaction ID</th>
														<th>Transaction Date</th>
														<th>Joining Date</th>
													</tr>
												</thead>
												<tbody id="tableBody">
													<?php
													$i = 0;
													foreach ($records as $row) {
														$i++;
														//DEFAULT VALUES SET
															$class_center = "has-html text-center";

														// DATA
															$id_member = $row['id'];
															$transaction_type = $row['transaction_type'];
															$user_id_member = $row['user_id'];
															$name_member = $row['name'];
															$transaction_mode = $row['transaction_mode'];
															$transaction_date = $row['transaction_date'];
															$transaction_amount = $row['transaction_amount'];
															$transaction_id = $row['transaction_id'];
															$url_file = $row['url_file'];
															$status = $row['status'];
															$create_date = $row['create_date'];
														// DATA
															
														// CUSTOMIZATION
															$transaction_mode = ($transaction_mode==1)?"ONLINE":"OFFLINE";
														// CUSTOMIZATION

														switch ($transaction_type) {
															case 'fresh':
																$user_txn_for = "Investment";
																$class_txn = "primary";
																break;
															
															default:
																$user_txn_for = $class_txn = "";
																break;
														}

														//CHECK DATE FOR YYYY-MM-DD FORMAT
															if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $transaction_date)) {
																$transaction_date = date_create($transaction_date);
																$transaction_date = date_format($transaction_date, "d-M-Y");
															}
														//CHECK DATE FOR YYYY-MM-DD FORMAT

														//CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT
															$regEx = '/(\d{4})-(\d{2})-(\d{2}) (\d{2}):(\d{2}):(\d{2})/';
															// preg_match($regEx, $details['date'], $result);
															if (preg_match($regEx, $create_date) || preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $create_date)) {
																$create_date = date_create($create_date);
																$create_date = date_format($create_date, "d M Y h:ia");
															}
														//CHECK DATE FOR YYYY-MM-DD OR YYYY-MM-DD hh:ii:ss FORMAT

														// PHOTO PATH
															$photo_path = "$url_dir/$url_file";

														?>
															<!-- REPEAT ITEM -->
															<tr>
																<td class="text-center"><?php echo $i; ?></td>
																<td class="no_print text-center">
																	<?php
																		if ($status!=1) {
																			?>
																				<!-- <input type="hidden" name="approve_transaction[<?php echo $id_member;?>]" value=""> -->
																				<!-- <input type="checkbox" id="approve_transaction_<?php echo $id_member;?>" name="approve_transaction[]" class="checkSingle" value="<?php echo $id_member;?>"> -->
																				<!-- <label for="approve_transaction_<?php echo $id_member;?>"><?php echo $user_id_member;?></label> -->
																				<!-- <?php echo $id_member;?> -->
																				<!-- <a target='_blank' href='print_relieving.php?id_member=<?php echo $id_member_encode; ?>' class='badge' style='background:#E07C24;margin-top:5px;margin-bottom:5px;' >
																					<i class="align-middle" data-feather="printer"></i>  Approve
																				</a> -->
																				<span class='badge bg-success rounded-ppill show-pointer shadow' style="background: #E07C24. !important;" data-bs-toggle="modal" data-bs-target="#ModalAction" data-id_txn="<?php echo $id_member; ?>" data-user_id="<?php echo $user_id_member; ?>">Approve/Reject Transaction</span>
																			<?php
																		} else {
																			?>
																				<span class='badge bg-danger rounded-pill' style="background: #6A1B4D !important;">Approved</span>
																			<?php
																		}
																	?>
																</td>
																<td class="has-html text-center">
																	<span>
																		<?php echo $user_id_member; ?>
																		<input type="hidden" id="txn_user_<?php echo $id_member;?>" name="txn_user_id_member[]" class="" value="<?php echo $user_id_member;?>">
																	</span>
																</td>
																<td class="text-center"><?php echo $name_member; ?></td>
                                                                	<input type="hidden" id="txn_transaction_amount_<?php echo $id_member;?>" name="txn_transaction_amount[]" class="" value="<?php echo $transaction_amount;?>">
																<td class="has-html text-center" onclick='copyToClipboard("<?php echo "$user_txn_for"; ?>")' style="cursor:pointer;" title='Click to copy'>
																	<span class="badge bg-<?php echo $class_txn; ?> text-uppercase" style="white-space: normal;">
																		<?php echo $user_txn_for; ?>
																	</span>
																</td>
																<td class="text-center no_print">
																	<img src='<?php echo $photo_path; ?>' class="show-pointer" style='width:80px;height:80px;border: 1px solid #555;' loading='lazy' data-bs-toggle='modal' data-bs-target='#ModalShowReceipt' id="img-<?php echo $id_member;?>" data-id="img-<?php echo $id_member;?>" data-src='<?php echo $photo_path; ?>'>
																</td>
																<td class="text-center"><?php echo $transaction_mode; ?></td>
																<td class="has-html text-center"><span class="badge bg-info" style="white-space: normal;"><?php echo $transaction_amount; ?></span></td>
																<td class="text-center"><?php echo $transaction_id; ?></td>
																<td class="text-center"><?php echo $transaction_date; ?></td>
																<td class="text-center"><?php echo $create_date; ?></td>
															</tr>

														<?php
													}
													?>
												</tbody>
												<tfoot class="no_print">
													<tr>
														<th>Sr</th>
														<th class="no_print">Action</th>
														<th style="white-space: nowrap;">User ID</th>
														<th style="white-space: nowrap;">User Name</th>
														<th>Transaction For</th>
														<th class="no_print">Transaction Receipt</th>
														<th>Transaction Mode</th>
														<th>Transaction Amount</th>
														<th>Transaction ID</th>
														<th>Transaction Date</th>
														<th>Joining Date</th>
													</tr>
												</tfoot>
											</table>
											<div class="d-none no_print" style="margin-bottom:5px;display:flex;justify-content:center;">
												<span class="btn bg-success rounded-pill">
													<input type="submit" name="submit" value="&#x1F5B6; Approve Transactions" style="font-weight:bold;border-style:hidden;background:transparent;color:white;">
													<!-- <input onclick="submitFormChoiceAllot()" name="submit" value="Submit" style="font-weight:bold;border-style:hidden;background:transparent;color:white;"> -->
												</span>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</main>

				<?php include("footer.php"); ?>
			</div>
		</div>
	</body>

	<?php include("scripts.php"); ?>

	<!-- BEGIN ModalAction -->
		<div class="modal fade" id="ModalAction" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Transaction</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
								<input type="hidden" id="id_txn">
								<input type="hidden" id="id_user">
								<div class="mb-3 col-md-12">
									<label for="select_action" class="text-bold">Select Action To Perform</label>
									<select name="" id="select_action" class="form-control" onchange="toggle_comment();">
										<option value="">Choose Action</option>
										<option value="approved">Approve</option>
										<option value="rejected">Reject</option>
									</select>
								</div>
								<div class="mb-3 col-md-12 d-none" id="reject_comment_container">
									<label for="reject_comment" class="text-bold">Reason/Comment For Rejection <span class="text-danger">{Required For Rejection}</span></label>
									<input type="text" name="reject_comment" id="reject_comment" class="form-control" placeholder="Enter comment">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" onclick="process_withdrawal();">Submit</button>
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalAction -->

	<!-- BEGIN ModalShowReceipt -->
		<div class="modal fade" id="ModalShowReceipt" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Transaction Receipt</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
							<div class="row align-items-center">
								<div class="mb-3 col-md-12">
									<img src="" alt="" id="image" style="width: 100%;">
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<!-- <button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button> -->
							</div>
						</p>
					</div>
				</div>
			</div>
		</div>
	<!-- END ModalShowReceipt -->

	<script>
		function toggle_comment() {
			var select_action = document.getElementById("select_action");
			var reject_comment_container = document.getElementById("reject_comment_container");
			var reject_comment = document.getElementById("reject_comment");

			if (select_action.value == "rejected") {
				reject_comment_container.classList.remove('d-none');
				reject_comment.setAttribute('required','true');
			} else {
				reject_comment_container.classList.add('d-none');
				reject_comment.setAttribute('required','false');
			}
		}

		function process_withdrawal() {
			var action = "process_transaction";
			var select_action = document.getElementById("select_action");
			var reject_comment = document.getElementById("reject_comment");
			var id_txn = document.getElementById("id_txn");
			var id_user = document.getElementById("id_user");

			if (select_action.value == "") {
				showNotif("Please select an action to perform!","danger");
				return false;
			}

			if (select_action.value == "rejected") {
				if (reject_comment.value.trim() == "") {
					reject_comment.focus();
					showNotif("Comment is required for Rejection!","danger");
					return false;
				}
			}

			$.ajax({
				type: "POST",
				url: "ajax.php",
				async: false,	//**** WAIT FOR RESPONSE****/
				data: {
					action: action,
					id_user: id_user.value,
					updated_by: "<?php echo $user_id; ?>",
					txn_id: id_txn.value,
					action_to_perform: select_action.value,
					reject_comment: reject_comment.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				// xhr: function () {
					//     var xhr = $.ajaxSettings.xhr();
					//     xhr.onprogress = function (e) 
					//     {
					//         // For downloads
					//         if (e.lengthComputable)                             
					//         {
					//             console.log(e.loaded / e.total);
					//         }
					//     };
					//     xhr.upload.onprogress = function (e) 
					//     {
					//         // For uploads
					//         if (e.lengthComputable) 
					//         {
					//             document.getElementById("overlay").style.display = "block";
					//             console.log(e.loaded / e.total);
					//         }
					//     };
					//     return xhr;
				// },

				success: function(response) {
					if (response == "approved") {
						showNotif("Transaction Approved Successfully","success");
					} else if (response == "rejected") {
						showNotif("Transaction Rejected Successfully","danger");
					}
					
					setTimeout(function(){
						location.reload();
					},1500);
					// document.getElementById("overlay").style.display = "none";

					// var response_obj = $.parseJSON(response); // create an object with the key of the array
					// PARSE/DECODE THE JSON OBJECT
					// var response_obj = JSON.parse(response);
					// alert(response_obj.html_data); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

				}
			});
		}

		// PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalAction').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var id = button.data('id_txn'); // Extract info from data-* attributes
                    var id_user = button.data('user_id'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					document.getElementById("id_txn").value = id;
					document.getElementById("id_user").value = id_user;
                });
            });
        // PASS DATA TO MODAL POPUP
	</script>
	
	<?php
		// COUNTER
			$page_id = 1;
			counter($page_id);
		// COUNTER
	?>

	<script type="text/javascript">
        var restrict_all = document.getElementById("restrict_all");
        $(document).ready(function() { 
            // $("#restrict_all").click(function(){
            //     $('input:checkbox').not(this).prop('checked', this.checked);
            // });

            $("#check_all").change(function() {
                if (this.checked) {
                    $(".checkSingle").each(function() {
                        this.checked=true;
                    });
                } else {
                    $(".checkSingle").each(function() {
                        this.checked=false;
                    });
                }
            });

            $(".checkSingle").click(function () {
                if ($(this).is(":checked")) {
                    var isAllChecked = 1;
                    $(".checkSingle").each(function() {
                        if (!this.checked)
                            isAllChecked = 0;   // 0 if any one not checked
                    });

                    if (isAllChecked == 1) {
                        $("#check_all").prop("checked", true);
                    }
                }
                else {
                    var isAllChecked = 0;
                    $(".checkSingle").each(function() {
                        if (this.checked)
                            isAllChecked = 1;   // 1 if any one or (all-1) remain checked || (all-1) >> Bcz it is called when One uncheck is performed
                    });

                    if (isAllChecked == 1) {
                        $("#check_all").prop("checked", false);
                    }
                }
            });

			// MAKE ONLY SINGLE ITEM CLICKED
			$('input[type="checkbox"]').on('change', function() {
				$('input[type="checkbox"]').not(this).prop('checked', false);
			});
        });
    </script>

	<script>
        // DataTables with Column Search by Text Inputs
        document.addEventListener("DOMContentLoaded", function () {
            // DataTables
            var $filename = document.title;
            var default_Order = '<?php echo $default_Order;?>';
            var length_menu = '<?php echo isset($length_menu)?$length_menu:"";?>';
            var table = $('#datatables').DataTable({
                dom: /*'lBfrtip'*/ 'lBfrtip',
                // Default Sort
                "order": [[default_Order, "asc"]],
                // "scrollX": true,
                "lengthMenu": (length_menu == "all")?[[-1],["All"]]:[[25, 50, 100, -1],[25, 50, 100, "All"]],
                buttons: [{
                    extend: 'excel',
                    text: 'Export In Excel',
                    //className: 'btn-success', TO CHANGE THE BUTTON STYLE, ADD A CLASS THEN MODIFY IT IN CSS
                    filename: $filename,
                    title: $filename,
                    titleAttr: "Export In Excel",
                    //orientation : 'landscape',
                    //pageSize:'LEGAL',    //fullpage
                    "oSekectorOpts":{filter:'applied', order:'current'},
                    exportOptions: //EXPORTING PARTICULAR COLUMN STARTS FROM 0, 1, 2 ETC ETC
                    {
                        //**IMPORTANT**//columns: ':visible',  //exporting only visible columns
                        // columns: [1, 2, 3, 4, 5, 6], //-----------HIDING ACTION COLUMN IN PDF EXPORT----------//
                        columns: ':visible:not(.no_print)', //HIDE ACTION COLUMN
                        // rows: ':visible'

                        // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                            format: {
                                body: function (data, row, column, node) {
                                    // if it is select
                                        // if (column == 9) {
                                        //     data = $(data).find("option:selected").text()
                                        // }
                                    // STRIP OUT HTML TAGS
                                        // console.log(node.classList.contains("has-html"));
                                        // CUSTOMIZE ELEMENT BY CLASS NAME
                                        if(hasClass(node,"has-html")) {
                                            var column_has_class = column;
                                            if (column == column_has_class) {
                                                data = $(data).text();
                                            }
                                        }
                                        // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                        //     // console.log($(data).find('span').textContent + "");
                                        //     // data = $(data).find('span').innerText
                                        //     data = $(data).text();
                                        // }
                                    // REPLACE <BR> WITH NEW LINE
                                        // if (column == 7) {
                                        //     data = data.replace( /<br\s*\/?>/ig, ";" );
                                        // }
                                    return data
                                }
                            }
                    },
                    key: { // press E for export EXCEL
                        key: 'e',
                        altKey: false
                    },
                },
                {
                    text: 'Download PDF',
                    extend: 'pdfHtml5',
                    className: 'btn-warning',
                    // TO CHANGE THE BUTTON STYLE, ADD A CLASS THEN MODIFY IT IN CSS
                    filename: $filename,
                    orientation: 'landscape',
                    pageSize: 'A4', //A3 , A5 , A6 , legal , letter, TABLOID
                    //pageSize:'LEGAL',    //fullpage
                    // pageMargins: [0, 0, 0, 0], // try #1 setting margins
                    // margin: [0, 0, 0, 0], // try #2 setting margins
                    exportOptions: //EXPORTING PARTICULAR COLUMN STARTS FROM 0, 1, 2 ETC ETC
                    {
                        // columns: [ 0, 1, 2, 3, 4, 5, 6, 7],
                        // EXPORTING WITH DEFAULT INITIAL ORDER
                        columns: ':visible:not(.no_print)', //HIDE ACTION COLUMN
                        // rows: ':visible',

                        modifier: {
                            order: 'index',

                            // pageMargins: [0, 0, 0, 0], // try #3 setting margins
                            // margin: [0, 0, 0, 0], // try #4 setting margins
                            // alignment: 'center'
                        },
                        body: {
                            // margin: [0, 0, 0, 0],
                            // pageMargins: [0, 0, 0, 0]
                        } // try #5 setting margins         
                        ,
                        // columns: [0, 1], //column id visible in PDF    
                        // columnGap: 1 // optional space between columns

                        // EXPORT SELECT VALUES IN DATATABLE AND STRIP OUT HTML TAGS
                            format: {
                                body: function (data, row, column, node) {
                                    // if it is select
                                        // if (column == 9) {
                                        //     data = $(data).find("option:selected").text()
                                        // }
                                    // STRIP OUT HTML TAGS
                                        // console.log(node.classList.contains("has-html"));
                                        // CUSTOMIZE ELEMENT BY CLASS NAME
                                        if(hasClass(node,"has-html")) {
                                            var column_has_class = column;
                                            if (column == column_has_class) {
                                                data = $(data).text();
                                            }
                                        }
                                        // if ((column >= 2 && column <=3) || (column >= 8 && column <=9)) {
                                        //     // console.log($(data).find('span').textContent + "");
                                        //     // data = $(data).find('span').innerText
                                        //     data = $(data).text();
                                        // }
                                    // REPLACE <BR> WITH NEW LINE
                                        // if (column == 7) {
                                        //     data = data.replace( /<br\s*\/?>/ig, "\n" );
                                        // }
                                    return data
                                }
                            }
                    },

                    key: { // press D for export PDF
                        key: 'd',
                        altKey: false
                    },
                    // content: [{
                    //     style: 'fullWidth'
                    // }],
                    // styles: { // style for printing PDF body
                    //     fullWidth: {
                    //         fontSize: 18,
                    //         bold: true,
                    //         alignment: 'right',
                    //         margin: [0, 0, 0, 0]
                    //     }
                    // },
                    // download: 'download',
                    customize: function (doc) {
                        // Splice the image in after the header, but before the table
                        // doc.content.splice(1, 0, {
                        //     margin: [0, 0, 0, 12],
                        //     alignment: 'center',
                        //     image: 'data:image/png;base64,'
                        // });

                        var filteredRows = getNumFilteredRows('#datatables');

                        //Remove the title created by datatTables
                        doc.content.splice(0, 1);
                        //Create a date string that we use in the footer. Format is dd-mm-yyyy
                        var now = new Date();
                        var jsDate = now.getDate() + '-' + (now.getMonth() + 1) + '-' + now.getFullYear();
                        // Logo converted to base64
                        // var logo = getBase64FromImageUrl('https://datatables.net/media/images/logo.png');
                        // The above call should work, but not when called from codepen.io
                        // So we use a online converter and paste the string in.
                        // Done on http://codebeautify.org/image-to-base64-converter
                        // It's a LONG string scroll down to see the rest of the code !!!

                        // 		var logo = database image path

                        // A documentation reference can be found at
                        // https://github.com/bpampuch/pdfmake#getting-started
                        // Set page margins [left,top,right,bottom] or [horizontal,vertical]
                        // or one number for equal spread
                        // It's important to create enough space at the top for a header !!!
                        // 		doc.pageMargins = [20,60,20,30];
                        doc.pageMargins = [20, 60, 20, 80];

                        // Set the font size fot the entire document

                        // 		doc.defaultStyle.fontSize = 7;
                        // doc.defaultStyle.alignment = 'center';

                        // UNIFORM COLUMN WIDTH (100% WIDTH)
                        // doc.content[0].table.widths = Array(doc.content[0].table.body[0].length + 1).join('*').split('');

                        // VARIABLE COLUMN WIDTH
                        // doc.content[0].table.widths = [90,60,90,60,90,90,60,90,60,90,60];

                        // Set the fontsize for the table header
                        doc.styles.tableHeader.fontSize = 12;
                        doc.styles.tableHeader.alignment = 'center';

                        // doc.styles.tableHeader.fillColor = "green";

                        // var countTotal = <?php /* echo $total_trainee; */ ?>;
                        // TOTAL ROWS WITH HEADER FOOTER >> ID OF TABLE
                        // var countRows = $('#datatables tr').length;
                        // TOTAL ROWS WITHOUT HEADER FOOTER >> ID OF TABLE-BODY
                        var countRows = document.getElementById("tableBody").rows.length;
                        // ABOVE METHOD ONLY COUNTS THE DISPLAYED ROWS
                        
                        // var countRows = document.getElementById("total_count").value;
                        // ABOVE METHOD SETS THE ROW COUNT TO TOTAL RECORDS
                        
                        for (var i = 1; i <= countRows; i++) {
                            doc.content[0].table.body[i][0].alignment = 'center';
                            doc.content[0].table.body[i][0].bold = 'true';
                            // doc.content[0].table.find(".letter_download").html().bold = 'true';
                            // doc.content[0].table.body[0].getElementById("letter_download").style.bold = 'true';
                        }

                        // var myTab = document.getElementById('list');
                        // var obj = myTab.rows[2].cells.namedItem("letter_download");
                        // obj.style.fontWeight = "bold";
                        // obj.innerHTML = '<b>' + obj.innerHTML + '</b>';
                        // myTab.rows[1].cells.namedItem("letter_download").innerHTML;

                        // LOOP THROUGH EACH ROW OF THE TABLE AFTER HEADER.
                        // for (i = 1; i < myTab.rows.length; i++) {
                        //     // GET THE CELLS COLLECTION OF THE CURRENT ROW.
                        //     var objCells = myTab.rows.item(i).cells;

                        //     // LOOP THROUGH EACH CELL OF THE CURENT ROW TO READ CELL VALUES.
                        //     for (var j = 0; j < objCells.length; j++) {
                        //         // alert(objCells.item(j).innerHTML)
                        //     }
                        // }


                        // for (var i = 0; i < 40; i++) {
                        //     // COLUMN COLOR
                        //     // doc.content[0].table.body[i+1][0].fillColor = 'blue';

                        //     // ROW COLOR
                        //     for (var j = 0; j < 5; j++) {
                        //         doc.content[0].table.body[1][j].fillColor = 'lime';
                        //     }
                        // }

                        // doc.content[1].margin = [ 100, 0, 100, 0 ]; //left, top, right, bottom

                        // doc.styles.tableBodyOdd.noWrap = true;
                        // doc.styles.tableBodyEven.noWrap = true;

                        // AVOID BREAKING OF ROWS
                        doc.content[0].table.dontBreakRows = true;

                        var pageTitle = document.title;
                        // Create a header object with 3 columns
                        // Left side: Logo
                        // Middle: brandname
                        // Right side: A document title
                        doc['header'] = (function () {
                            return {
                                columns: [
                                    {
                                        alignment: 'left',
                                        italics: true,
                                        bold: true,
                                        text: ['', { text: pageTitle.toString() }],

                                        fontSize: 12,
                                        color: "#B4161B",
                                        margin: [5, 0]
                                    },
                                    // {
                                    //     alignment: 'right',
                                    //     text: 'School : ',
                                    //     fontSize: 12,
                                    //     color: "#B4161B",
                                    //     margin: [10, 0],
                                    //     bold: true
                                    // },
                                    // 	{
                                    // 		alignment: 'right',
                                    // 		fontSize: 14,
                                    // 		text: 'Total Trainees: <?php /* echo " $total_count, Present: $present_count";*/ ?>',
                                    // 		margin: [10,0]
                                    // 	}
                                ],
                                margin: 20
                            }
                        });
                        // Create a footer object with 2 columns
                        // Left side: report creation date
                        // Right side: current page and total pages
                        doc['footer'] = (function (page, pages) {
                            return {
                                columns: [{
                                    alignment: 'left',
                                    text: '<?php echo "Print Date: $print_date";?>',
                                    color: "#120E43",
                                    margin: [5, 0]
                                },
                                {
                                    text: ['Total Records Exported: ', { text: filteredRows.toString() }],
                                    margin: [5, 0]
                                },
                                // {
                                //     text: ['Result Date:     25 May 2020'],
                                //     // 		text: ['Result Declared on: ', { text: jsDate.toString() }],
                                //     margin: [5, 0]
                                // },
                                // {
                                //     text: '<?php /* echo "Total Trainees: $total_trainee";*/ ?>',
                                //     margin: [3, 0]
                                // },
                                // {
                                //     text: '<?php /* echo "Allotted Trainees: $allotted_trainee";*/ ?>',
                                //     margin: [3, 0]
                                // },
                                // {
                                //     text: '<?php /* echo "Total Trainees: {" . $total_trainee . "} Allotted: {" . $allotted_trainee . "} Remaining: {" . $remaining_trainee . "}" */ ?>',
                                //     color: "#120E43",
                                //     margin: [3, 0]
                                // },

                                {
                                    alignment: 'right',
                                    bold: true,
                                    fontSize: 12,
                                    // text: "Generated By - Maxizone",
                                    text: ['Generated By - ', {
                                        text: "Maxizone"
                                    }],
                                    color: "#120E43",
                                    margin: [10, 0]
                                    // 		text: ['page ', { text: page.toString() },	' of ',	{ text: pages.toString() }]
                                }
                                ],
                                marginTop: 55
                            }
                        });


                        // Change dataTable layout (Table styling)
                        // To use predefined layouts uncomment the line below and comment the custom lines below
                        // doc.content[0].layout = 'lightHorizontalLines'; // noBorders , headerLineOnly
                        var objLayout = {};
                        objLayout['hLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['vLineWidth'] = function (i) {
                            return .5;
                        };
                        objLayout['hLineColor'] = function (i) {
                            return '#aaa';
                        };
                        objLayout['vLineColor'] = function (i) {
                            return '#aaa';
                            // return '#00FFFFFF';
                        };
                        objLayout['paddingLeft'] = function (i) {
                            return 4;
                        };
                        objLayout['paddingRight'] = function (i) {
                            return 4;
                        };
                        doc.content[0].layout = objLayout;
                    }

                },
                // {
                //     extend: 'colvis',
                //     text: 'COLUMNS',
                //     className: 'btn-info',
                //     key: { // press L for COLUMNS
                //         key: 'l',
                //         altKey: false
                //     },
                //     postfixButtons: [{
                //         extend: 'colvisRestore',
                //         text: 'Show All'
                //     }]
                // },
                ],
                "columnDefs": [{
                    // //   DISABLE ORDERING OF ACTION COLUMN
                    "targets": [1],
                    // // DISABLE SORTING ON LAST COLUMN
                    // "targets": [-1],
                    "orderable": false,
                },],

                // Row Callback for Customization of Row
                // 'rowCallback': function(row, data, index){
                //     if(data[3]> 11.7){
                //         $(row).find('td:eq(3)').css('color', 'red');
                //     }
                //     if(data[2].toUpperCase() == 'EE'){
                //         $(row).find('td:eq(2)').css('color', 'blue');
                //     }
                // },
            });
            // Apply the search
            table.columns().every(function () {
                var that = this;
                $('input', this.footer()).on('keyup change clear', function () {
                    if (that.search() !== this.value) {
                        that.search(this.value).draw();
                    }
                });
            });
        });

        function hasClass( elem, klass ) {
            // https://stackoverflow.com/questions/10960573/what-is-the-best-way-to-check-if-element-has-a-class
            return (" " + elem.className + " " ).indexOf( " "+klass+" " ) > -1;
        }
    </script>

	<script>
		function callAjaxMemberName() {
			var action = "get_sponsor";
			var sponsor_id = document.getElementById("member_user_id");
			var sponsor_name = document.getElementById("member_name");

			// Call ajax for pass data to other place
			$.ajax({
				type: "POST",
				url: "ajax.php",
				data: {
					action: action,
					sponsor_id: sponsor_id.value
				},
				
				// data: $("#form_register").serialize() + "&action=" +action , // getting filed value in serialize form And Passing additional Value of 'action' To perform Task Accordingly
				// dataType: 'JSON',
				cache: false,

				// xhr: function () 
				// {
				//     var xhr = $.ajaxSettings.xhr();
				//     xhr.onprogress = function (e) 
				//     {
				//         // For downloads
				//         if (e.lengthComputable)                             
				//         {
				//             console.log(e.loaded / e.total);
				//         }
				//     };
				//     xhr.upload.onprogress = function (e) 
				//     {
				//         // For uploads
				//         if (e.lengthComputable) 
				//         {
				//             document.getElementById("overlay").style.display = "block";
				//             console.log(e.loaded / e.total);
				//         }
				//     };
				//     return xhr;
				// },

				success: function(response) {
					// document.getElementById("overlay").style.display = "none";

					// var response_obj = $.parseJSON(response); // create an object with the key of the array
					// PARSE/DECODE THE JSON OBJECT
					// var response_obj = JSON.parse(response);
					// alert(response_obj.html_data); // where html is the key of array that you want, $response['html'] = "<a>something..</a>";

					sponsor_name.value = response;
				}
			});
		}
	</script>

	<script>
        // PASS DATA TO MODAL POPUP
            $(function () {
                $('#ModalShowReceipt').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var src = button.data('src'); // Extract info from data-* attributes
                    var id = button.data('id'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
					
                    // modal.find(id).val(src);
                    // modal.find('#image').src=src;
					$("#image").attr("src", src);
                });
            });
        // PASS DATA TO MODAL POPUP
    </script>

	<?php
		if($msg!=""){
			?>
				<script>
					showNotif("<?php echo $msg;?>", "<?php echo $msg_type;?>")
				</script>
			<?php
			exit;
		}
	?>
</html>