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
	
	$auth_key = "72fede3cfdb461993f399b2ac0363e73";
    $client_id = "78262";

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

	// DEFAULT
		$page_id_home = 1;
		$pan_upload = $aadhaar_upload = 0;

	// if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `visits` FROM `counter` WHERE `page_id`='$page_id_home' "))) {
	// 	$count_visitor = $rw['visits'];
	// }
	
	// DEFAULT ORDER COLUMN SR
		$default_Order = 1;

	// ADD TRANSACTION
		$url_dir = "../../assets/files/transaction";
		$msg = $msg_type = "";
		$error = false;

		if (isset($_POST['submit'])) {

			/*****IMAGE UPLOAD*****/
				// Check if file was uploaded without errors
				if ((isset($_FILES["file"]) && $_FILES["file"]["error"] == 0)) {
					$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "JPG" => "image/jpg", "JPEG" => "image/jpeg", "png" => "image/png", "PNG" => "image/png", "webp" => "image/webp");
					$filename = $_FILES["file"]["name"];
					$filetype = $_FILES["file"]["type"];
					$filesize = $_FILES["file"]["size"];

					// Verify file extension
					$ext = pathinfo($filename, PATHINFO_EXTENSION);
					
					// Extract FileName
					$file_basename = basename($filename, ".$ext");            

					if (!array_key_exists($ext, $allowed)) {
						$error = true;
						$msg .= " >> Only JPEG/JPG/PNG/WEBP Formats are Allowed!!!";
						$msg_type = "danger";
					}

					// Verify file size - 100kB maximum
					$minsize = 1 * 1024;
					$maxsize = 100 * 1024;

					if ($filesize > $maxsize || $filesize < $minsize) {
						$error = true;
						$msg .= " >> Error!!! File size should not be greater than 100kb.";
						$msg_type = "danger";
					}

					//----------IMAGE IS JPEG/JPG AND NO ERROR-----------//
					if (!$error) {
						// Check whether file exists before uploading it
						// if(file_exists("../../collegeportal/images/files/$batch" . $filename))
						// {
						//     $error_message = "$reg_no already registered!!!";
						// } 
						// else 
						$uploadedfile = $_FILES["file"]["tmp_name"];

						$current_timestamp = time();
						// $filename = $current_timestamp.".$ext";
						// $filename = "$file_basename-$current_timestamp".".$ext";
						$filename = "$user_id-$to_user_id-$current_timestamp".".$ext";
						
						// FOR SELECT QUERIES
							$query_company_txn = "SELECT `id` FROM `fund_transaction` WHERE `user_id`='$user_id' AND `package_amount`='$package_amount' AND `to_user_id`='$to_user_id'";
							$res = mysqli_query($conn, $query_company_txn);
							$check_txn_available = mysqli_num_rows($res);

							if ($check_txn_available==0) {
								$query_insert_transaction = 
									"INSERT INTO `fund_transaction` (`package_amount`, `user_id`, `to_user_id`, `transaction_mode`, `transaction_date`, `transaction_amount`, `transaction_id`, `url_file`, `create_date`) 
									VALUES ('$package_amount', '$user_id', '$to_user_id', '$transaction_mode', '$transaction_date', '$transaction_amount', '$transaction_id', '$filename', '$current_date')";
								if (mysqli_query($conn, $query_insert_transaction)) {
									$file_dir = "$url_dir/$filename";

									// UPLOAD FILE
									if ((move_uploaded_file($_FILES['file']['tmp_name'], $file_dir))) {
										$msg = "New Record Added Successfully! ";
										$msg_type = "success";
										// echo "<script>window.close();</script>";
									}
								} else {
									$msg .= " >> Error in Inserting The Record...Try Again";
									$msg_type = "danger";
								}
							} else {
								$msg = ">> Transaction to $to_user_id Added Already! <<";
								$msg_type = "default";
							}
						

						// correctImageOrientation($file_dir);

					} else {
						$msg .= " >> There was a problem uploading your record. Please try again.";
						$msg_type = "danger";
					}
				} else {
					//ERROR IN fileS
					$msg .= " >> Error in file " . $_FILES["file"]["error"];
					$msg_type = "danger";
				}
			/*****IMAGE UPLOAD*****/
		}
	// ADD TRANSACTION

	// if ($rw = mysqli_fetch_array(mysqli_query($conn, "SELECT `fund_available`, SUM(`amount`) AS 'amount_cashback' FROM `users` INNER JOIN `fund_transaction` ON `fund_transaction`.`user_id`=`users`.`id` WHERE `users`.`id`='$user_id' AND `fund_transaction`.`trans_type`='CR' AND `fund_transaction`.`trans_id` LIKE 'CASHBACK' "))) {
	// 	$fund_available = $rw['fund_available'];
	// 	$amount_cashback = $rw['amount_cashback'];

	// 	$fund_available = isset($fund_available)?$fund_available:'0';
	// 	$amount_cashback = isset($amount_cashback)?$amount_cashback:'0';

	// 	$fund_available = round($fund_available, 2);
	// 	$amount_cashback = round($amount_cashback, 2);

	// }
		
	$query_detail = "SELECT `otp_total`,`otp_used`,(`otp_total`-`otp_used`) AS `otp_remaining` FROM `users_admin` WHERE `user_id`='$user_id'";
	$query_detail = mysqli_query($conn, $query_detail);
	if ($rw = mysqli_fetch_array($query_detail)) {
		$otp_total = $rw['otp_total'];
		$otp_used = $rw['otp_used'];
		$otp_remaining = $rw['otp_remaining'];
	}

	function get_sms_current_balance() {
        $curl = curl_init();
        $auth_key = $GLOBALS['auth_key'];
        $client_id = $GLOBALS['client_id'];
        
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://msg.msgclub.net/rest/services/sendSMS/getClientRouteBalance?AUTH_KEY=$auth_key&clientId=$client_id",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Cookie: JSESSIONID=A8FA45DF1EBAFD85046948FE9004A5D0.node3'
            // ),
			CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $response = json_decode($response);
        foreach ($response as $key => $value) {
            $userId = $value->userId;
            $balance = $value->balance;
            $displayRouteId = $value->displayRouteId;
            $displayRouteName = $value->displayRouteName;

            if ($displayRouteId == 1 && $displayRouteName == "Transactional sms" && $userId == $client_id) {
                return $balance;
            } else {
                continue;
            }
        }
    }

    function get_sms_total_log() {
        $curl = curl_init();
        $auth_key = $GLOBALS['auth_key'];
        $client_id = $GLOBALS['client_id'];
        
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://msg.msgclub.net/rest/services/transaction/transactionLog?AUTH_KEY=$auth_key",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_HTTPHEADER => array(
            //     'Cookie: JSESSIONID=F44CDB460C25DAB4A6481D2AABC94E06.node3'
            // ),
			CURLOPT_HTTPHEADER => array(
                "Cache-Control: no-cache",
                "Content-Type: application/json"
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $total_sms = 0;
        $response = json_decode($response);
        foreach ($response as $key => $value) {
            $balance = (float) $value->balance;
            $userNameFrom = $value->userNameFrom;
            $userIdT0 = $value->userIdT0;
            $routeName = $value->routeName;

            if ($routeName == "Transactional sms" && $userIdT0 == $client_id) {
                $total_sms += $balance;
            } else {
                continue;
            }
        }
        return $total_sms;
    }

    $total_credit_sms = get_sms_total_log();
    $current_available_sms = get_sms_current_balance();

	$otp_total = $total_credit_sms;
	$otp_used = $total_credit_sms - $current_available_sms;
	$otp_remaining = $current_available_sms;
	// $count_pending_kyc = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `users` WHERE `status`='pending'"));
	$count_pending_kyc = 0;
	$query_doc_users = mysqli_query($conn, "SELECT `user_id` FROM `user_document` GROUP BY `user_id`");
	$doc_users = mysqli_fetch_all($query_doc_users, MYSQLI_ASSOC);
	foreach ($doc_users as $item) {
		$doc_member_id = $item['user_id'];

		// $query_pending_kyc = mysqli_query($conn, "SELECT IF(`status`='pending',COUNT(`status`),0) AS 'pending_docs', IF(`status`='approved',COUNT(`status`),0) AS 'approved_docs', IF(`status`='rejected',COUNT(`status`),0) AS 'rejected_docs' FROM `user_document` WHERE `user_id`='$doc_member_id' GROUP BY `status`");
		// $pending_kyc = mysqli_fetch_array($query_pending_kyc);
		// extract($pending_kyc);
		
		// if ($pending_docs > 0) {
		// 	$count_pending_kyc++;
		// } else {
		// 	continue;
		// }

		$pending_docs = $approved_docs = $rejected_docs = $count_docs_status = 0;

        $query_doc = "SELECT COUNT(`id`) AS 'pending_docs' FROM `user_document` WHERE `user_id`='$doc_member_id' AND `status`='pending'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);
        $query_doc = "SELECT COUNT(`id`) AS 'approved_docs' FROM `user_document` WHERE `user_id`='$doc_member_id' AND `status`='approved'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);
        $query_doc = "SELECT COUNT(`id`) AS 'rejected_docs' FROM `user_document` WHERE `user_id`='$doc_member_id' AND `status`='rejected'";
        $res = mysqli_fetch_array(mysqli_query($conn,$query_doc));
        extract($res);

        $count_docs_status = $pending_docs + $approved_docs + $rejected_docs;

		// if ($count_docs_status > 3 && $pending_docs > 0) {
		if ($pending_docs > 0) {
			$count_pending_kyc++;
		} else {
			continue;
		}

		/*
			// $pending_kyc = mysqli_fetch_all($query_pending_kyc, MYSQLI_ASSOC);
			// foreach ($pending_kyc as $item) {
			// 	$pending = $item['pending_docs'];

			// 	echo "$pending <hr>";
			// 	if ($pending > 0) {
			// 		$count_pending_kyc++;
			// 	} else {
			// 		continue;
			// 	}
			// }
		*/
	}

	$count_pending_txn = mysqli_num_rows(mysqli_query($conn, "SELECT `fund_transaction`.`id` FROM `fund_transaction` WHERE `fund_transaction`.`to_user_id`='COMPANY' AND `fund_transaction`.`status`=2 ORDER BY `fund_transaction`.`create_date` DESC"));
	$count_pending_withdrawal = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `withdrawal` WHERE `status`='pending'"));
	// DATE(NOW()) >> ONLY GIVES DATE NOT THE TIME
	// $count_inactive = mysqli_num_rows(mysqli_query($conn, "SELECT TIMESTAMPDIFF(DAY, `create_date`, DATE(NOW())) FROM `users` WHERE `user_rank`='bronze' AND TIMESTAMPDIFF(DAY, `create_date`, DATE(NOW()))>360"));
	$count_inactive = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `users` WHERE `status`='inactive'"));
	$count_renewal = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `users` WHERE `status`='pending_renewal'"));
	
	// GRAPHS DATA
		$query = "SELECT COUNT(`id`) AS 'total_users' FROM `users` WHERE `delete_date` IS NULL";
		$res = mysqli_query($conn,$query);
		$res = mysqli_fetch_array($res);
		extract($res);

		// $query = "SELECT COUNT(`id`) AS 'active_users' FROM `users` WHERE `delete_date` IS NULL AND `status`='active'";
		$query = "SELECT COUNT(`id`) AS 'active_users' FROM `wallets` WHERE `delete_date` IS NULL AND `wallet_investment`>0";
		$res = mysqli_query($conn,$query);
		$res = mysqli_fetch_array($res);
		extract($res);
		
		// $query = "SELECT COUNT(`id`) AS 'inactive_users' FROM `users` WHERE `delete_date` IS NULL AND `status`='inactive'";
		$query = "SELECT COUNT(`id`) AS 'inactive_users' FROM `wallets` WHERE `delete_date` IS NULL AND `wallet_investment`<=0";
		$res = mysqli_query($conn,$query);
		$res = mysqli_fetch_array($res);
		extract($res);

		$graph_members = "$active_users, $inactive_users";

		// TOTAL FINANCE
			$query_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'total_investment' FROM `fund_transaction` WHERE `status`=1 AND `transaction_type` IN ('admin_credit','fresh','superwallet') AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query_invest);
			$res = mysqli_fetch_array($query);
			extract($res);

			$query = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'total_transfer' FROM `transaction_superwallet` WHERE `status`=1 AND `from_wallet` IN ('superwallet_transfer') AND `transaction_mode`='debit' AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query);
			$res = mysqli_fetch_array($query);
			extract($res);

			$query = "SELECT IF(SUM(`withdrawal_amount`) IS NULL,0,SUM(`withdrawal_amount`)) AS 'total_withdraw' FROM `withdrawal` WHERE `status`!='rejected' AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query);
			$res = mysqli_fetch_array($query);
			extract($res);
		// TOTAL FINANCE
		
		$date_today = date('Y-m-d'); //today date
        $weekOfdays = array();
        $weekOfdays[0] = $date_today;
        for($i = 6; $i >= 1; $i--){
            $date_today = date('Y-m-d', strtotime('-1 day', strtotime($date_today)));
            // $weekOfdays[] = date('l : Y-m-d', strtotime($date));
            $weekOfdays[$i] = date('Y-m-d', strtotime($date_today));
        }
        asort($weekOfdays);
        // echo '<p>Previous 7 days from the current date are as shown below</p>';
        // print_r($weekOfdays);

		$data_graph_investment = $data_graph_transfer = $data_graph_withdraw = $data_graph_activation = $data_graph_earning = $day_graph_earning = $day_graph_finance = "";
        foreach($weekOfdays as $date){
            $date_to_fetch = $date;

			$query_invest = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'data_investment' FROM `fund_transaction` WHERE `status`=1 AND `transaction_type` IN ('admin_credit','fresh','superwallet') AND `create_date` LIKE '%$date_to_fetch%' AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query_invest);
			$res = mysqli_fetch_array($query);
			extract($res);
			
			if ($data_investment != "") {
                $data_investment = round($data_investment,2);
                $data_graph_investment .= "$data_investment,";
            } else {
                $data_graph_investment .= "0,";
            }

			$query = "SELECT IF(SUM(`transaction_amount`) IS NULL,0,SUM(`transaction_amount`)) AS 'data_transfer' FROM `transaction_superwallet` WHERE `status`=1 AND `from_wallet` IN ('superwallet_transfer') AND `transaction_mode`='debit' AND `create_date` LIKE '%$date_to_fetch%' AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query);
			$res = mysqli_fetch_array($query);
			extract($res);
			
			if ($data_transfer != "") {
                $data_transfer = round($data_transfer,2);
                $data_graph_transfer .= "$data_transfer,";
            } else {
                $data_graph_transfer .= "0,";
            }

            $query = "SELECT IF(SUM(`withdrawal_amount`) IS NULL,0,SUM(`withdrawal_amount`)) AS 'data_withdraw' FROM `withdrawal` WHERE `status`!='rejected' AND `create_date` LIKE '%$date_to_fetch%' AND `delete_date` IS NULL";
			$query = mysqli_query($conn,$query);
			$res = mysqli_fetch_array($query);
			extract($res);
			
			if ($data_withdraw != "") {
                $data_withdraw = round($data_withdraw,2);
                $data_graph_withdraw .= "$data_withdraw,";
            } else {
                $data_graph_withdraw .= "0,";
            }

            $day = date('D', strtotime($date_to_fetch));

            //CHECK DATE FOR YYYY-MM-DD FORMAT
                if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $date_to_fetch)) {
                    $date_show = date_create($date_to_fetch);
                    $date_show = date_format($date_show, "d M");
                }
            //CHECK DATE FOR YYYY-MM-DD FORMAT

            $day_graph_finance .= "['$day', '$date_show'],"; //  MORE TEXT IN NEW LINE
            // $day_graph_earning .= "'$day',";    // NORMAL WAY
        }
        
        $data_graph_investment = ($data_graph_investment == "0,0,0,0,0,0,0,")?"":$data_graph_investment;
        $data_graph_transfer = ($data_graph_transfer == "0,0,0,0,0,0,0,")?"":$data_graph_transfer;
        $data_graph_withdraw = ($data_graph_withdraw == "0,0,0,0,0,0,0,")?"":$data_graph_withdraw;

		$xaxis_style_graph_finance = "colors: '#120E43', fontSize: '14.5px', fontFamily:'georgia', fontWeight:'bold', cssClass: 'apexcharts-xlabel-custom'";
        $yaxis_style_graph_finance = "colors: '#120E43', fontSize: '14px', fontFamily:'georgia', fontWeight:'bold'";
	// GRAPHS DATA

?>

	<head>
		<?php include("head.php"); ?>

		<title><?php echo "$user_id-$name" ?>  - Dashboard - Maxizone</title>

		<link rel="canonical" href="index.php">

		<style>
			.apexcharts-xlabel-custom {
				font-style: italic !important;
				font-size: smaller !important;
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
								<h3>Dashboard</h3>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-6 col-xxl-4 d-flex">
								<div class="card illustration flex-fill">
									<div class="card-body p-0 d-flex flex-fill">
										<div class="row g-0 w-100">
												<h4 class="illustration-text p-3 pb-0">Welcome Back, Admin</h4>
												
												<div class="mb-0 text-center">
													<!-- <a target="" href="profile.php"><span class="badge badge-soft-success me-2" style="font-weight:bold;"> View Profile </span></a> -->
												</div>
											<div class="col-7">
												<div class="illustration-text p-3 m-1">
													<!-- <p class="mb-0">Dashboard</p> -->
													<div class="mb-0">
														<!-- User ID- <b><?php echo $user_id; ?></b> -->
														<!-- <br> -->
														<b class="lead text-bold"><?php echo $name; ?></b>
													</div>
												</div>
											</div>
											<div class="col-5 align-self-end text-end">
												<img src="../assets/img/illustrations/customer-support.png" alt="Customer Support" class="img-fluid illustration-img">
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-xxl-4 d-flex">
								<div class="card flex-fill alert-success gradient-peach">
									<div class="card-body py-4">
										<div class="d-flex align-items-start">
											<div class="flex-grow-1">
												<h3 class="mb-2">KYC Pending</h3>
												<p class="mb-2">Pending for Admin Approval</p>
												<div class="mb-0">
													<a class="dropdown-item" href="view_member.php?type=<?php echo base64_encode(json_encode("kyc")); ?>">
														<i class="align-middle me-1" data-feather="eye"></i> <span class="badge bg-success me-2" style="font-weight:bold;">View List </span>
													</a>
												</div>
											</div>
											<div class="d-inline-block ms-3">
												<div class="stat text-center text-bold bg-info text-white">
													<?php echo $count_pending_kyc; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-xxl-4 d-flex">
								<div class="card flex-fill alert-danger gradient-juicy-orange text-white text-bold">
									<div class="card-body py-4">
										<div class="d-flex align-items-start">
											<div class="flex-grow-1">
												<h3 class="mb-2 text-white"><?php echo "Pending Txn"; ?></h3>
												<p class="mb-2">Pending for Admin Approval</p>
												<div class="mb-0">
													<a class="dropdown-item" href="view_transaction_pending.php?id=company">
														<i class="align-middle me-1 text-white" data-feather="eye"></i> <span class="badge bg-success me-2" style="font-weight:bold;">View List </span>
													</a>
												</div>
											</div>
											<div class="d-inline-block ms-3">
												<div class="stat text-center text-bold bg-info text-white">
													<?php echo $count_pending_txn; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-xxl-4 d-flex">
								<div class="card flex-fill alert-dark gradient-peach">
									<div class="card-body py-4">
										<div class="d-flex align-items-start">
											<div class="flex-grow-1">
												<h3 class="mb-2"><?php echo "Pending Withdrawal"; ?></h3>
												<p class="mb-2">Pending for Admin Approval</p>
												<div class="mb-0">
													<a class="dropdown-item" href="view_withdrawal_pending.php">
														<i class="align-middle me-1" data-feather="eye"></i> <span class="badge bg-success me-2" style="font-weight:bold;">View List </span>
													</a>
												</div>
											</div>
											<div class="d-inline-block ms-3">
												<div class="stat text-center text-bold bg-info text-white">
													<?php echo $count_pending_withdrawal; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<?php
								/*
									$i = 0;
									while ($i < 5) {
										$i++;
									// foreach ($records as $record) {
										$count_member = mysqli_num_rows(mysqli_query($conn, "SELECT `id` FROM `levels` WHERE `level`='$i'"));
										?>
											<div class="col-12 col-sm-6 col-xxl-3 d-flex">
												<div class="card flex-fill alert-success">
													<div class="card-body py-4">
														<div class="d-flex align-items-start">
															<div class="flex-grow-1">
																<h3 class="mb-2"><?php echo "Level $i"; ?></h3>
																<p class="mb-2">Total Members in Level <?php echo $i;?></p>
																<div class="mb-0">
																	<!-- <a class="dropdown-item" href="view_member_total.php?<?php echo "uid=$user_id_encode&l=$i";?>">
																		<i class="align-middle me-1" data-feather="eye"></i> <span class="badge bg-success me-2" style="font-weight:bold;">View List </span>
																	</a> -->
																</div>
															</div>
															<div class="d-inline-block ms-3">
																<div class="stat text-center text-bold bg-warning text-white">
																	<?php echo $count_member; ?>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										<?php
									}
								*/
							?>
							<div class="col-12 col-sm-6 col-xxl-4 d-flex">
								<div class="card flex-fill alert-warning gradient-juicy-orange text-white">
									<div class="card-body py-4">
										<div class="d-flex align-items-start">
											<div class="flex-grow-1">
												<h3 class="mb-2"><?php echo "OTP Summary"; ?></h3>
												<p class="mb-2 text-bold">Purchased OTP:-<span class="badge bg-success me-2" style="font-weight:bold;"><?php echo $otp_total;?></span></p>
												<p class="mb-2 text-bold">Used OTP:-<span class="badge bg-info me-2" style="font-weight:bold;"><?php echo $otp_used;?></span></p>
												<p class="mb-2 text-bold">Remaining OTP:-<span class="badge bg-danger me-2" style="font-weight:bold;"><?php echo $otp_remaining;?></span></p>
											</div>
										</div>
									</div>
								</div>
							</div>
							<!-- <div class="col-12 col-sm-6 col-xxl-3 d-flex">
								<div class="card flex-fill alert-danger">
									<div class="card-body py-4">
										<div class="d-flex align-items-start">
											<div class="flex-grow-1">
												<h3 class="mb-2"><?php echo "Renewal Pending"; ?></h3>
												<p class="mb-2">Pending IDs After 12Months</p>
												<div class="mb-0">
													<a class="dropdown-item" href="view_member.php?type=<?php echo base64_encode(json_encode("renewal")); ?>">
														<i class="align-middle me-1" data-feather="eye"></i> <span class="badge bg-success me-2" style="font-weight:bold;">View List </span>
													</a>
												</div>
											</div>
											<div class="d-inline-block ms-3">
												<div class="stat text-center text-bold bg-info text-white">
													<?php echo $count_renewal; ?>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div> -->
						</div>

						<div class="row">
							<div class="col-12 col-sm-6 col-xxl-6 d-none">
								<div class="card flex-fill w-100">
									<div class="card-header">
										<div class="card-actions float-end">
											<div class="dropdown position-relative">
												<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
													<i class="align-middle" data-feather="more-horizontal"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a class="dropdown-item" href="#">Action</a>
													<a class="dropdown-item" href="#">Another action</a>
													<a class="dropdown-item" href="#">Something else here</a>
												</div>
											</div>
										</div>
										<h5 class="card-title mb-0">Sales / Revenue</h5>
									</div>
									<div class="card-body d-flex w-100">
										<div class="align-self-center chart chart-lg">
											<canvas id="chartjs-dashboard-bar"></canvas>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-xxl-6 d-none">
								<div class="card flex-fill w-100">
									<div class="card-header">
										<div class="card-actions float-end">
											<div class="dropdown position-relative">
												<a href="#" data-bs-toggle="dropdown" data-bs-display="static">
													<i class="align-middle" data-feather="more-horizontal"></i>
												</a>

												<div class="dropdown-menu dropdown-menu-end">
													<a class="dropdown-item" href="#">Action</a>
													<a class="dropdown-item" href="#">Another action</a>
													<a class="dropdown-item" href="#">Something else here</a>
												</div>
											</div>
										</div>
										<h5 class="card-title mb-0">Weekly sales</h5>
									</div>
									<div class="card-body d-flex">
										<div class="align-self-center w-100">
											<div class="py-3">
												<div class="chart chart-xs">
													<canvas id="chartjs-dashboard-pie"></canvas>
												</div>
											</div>

											<table class="table mb-0">
												<thead>
													<tr>
														<th>Source</th>
														<th class="text-end">Revenue</th>
														<th class="text-end">Value</th>
													</tr>
												</thead>
												<tbody>
													<tr>
														<td><i class="fas fa-square-full text-primary"></i> Direct</td>
														<td class="text-end">$ 2602</td>
														<td class="text-end text-success">+43%</td>
													</tr>
													<tr>
														<td><i class="fas fa-square-full text-warning"></i> Affiliate</td>
														<td class="text-end">$ 1253</td>
														<td class="text-end text-success">+13%</td>
													</tr>
													<tr>
														<td><i class="fas fa-square-full text-danger"></i> E-mail</td>
														<td class="text-end">$ 541</td>
														<td class="text-end text-success">+24%</td>
													</tr>
													<tr>
														<td><i class="fas fa-square-full text-dark"></i> Other</td>
														<td class="text-end">$ 1465</td>
														<td class="text-end text-success">+11%</td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-sm-6 col-xxl-6 d-fle">
								<div class="card">
									<div class="card-header gradient-peach shadow">
										<h5 class="card-title">Members</h5>
										<h6 class="card-subtitle text-muted">All Members Summary</h6>
									</div>
									<div class="card-body text-center">
										<div class="chart w-100">
											<div id="apexcharts-pie-users" style="max-width: auto;margin:auto;"></div>
										</div>
									</div>
								</div>
							</div>

							<div class="col-12 col-sm-6 col-xxl-6 d-fle">
								<div class="card">
									<div class="card-header gradient-peach shadow">
										<h5 class="card-title">Finance</h5>
										<h6 class="card-subtitle text-muted">Finance Summary</h6>
									</div>
									<div class="card-body">
										<div class="chart w-100">
											<div id="apexcharts-column-finance"></div>
										</div>
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

	<!-- BEGIN ModalPay -->
		<div class="modal fade" id="ModalPay" tabindex="-1" role="dialog" aria-hidden="true">
			<div class="modal-dialog modal-dialog-centered modal-lg" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title">Add Record</h3>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
					</div>
					<div class="modal-body">
						<p class="mb-0">
						<form method="POST" action="" id="form_add_record" enctype="multipart/form-data">
							<div class="row align-items-center">
								<div class="mb-3 col-md-6">
									<label for="user_id" class="form-label">User ID</label>
									<input type="text" class="form-control text-readonly" value="<?php echo $user_id; ?>" name="user_id" id="user_id" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="sponsor_id" class="form-label">Sponsor</label>
									<input type="text" class="form-control text-readonly" value="<?php echo "$sponsor_id-$sponsor_name"; ?>" readonly>
									<input type="hidden" value="<?php echo $sponsor_id; ?>" name="sponsor_id">
								</div>
								<div class="mb-3 col-md-6">
									<label for="package_amount" class="form-label">Package Selected</label>
									<input type="text" class="form-control text-readonly" name="package_amount" id="package_amount" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_amount" class="form-label">Amount Transfer</label>
									<input type="text" class="form-control text-readonly" name="transaction_amount" id="transaction_amount" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_amount" class="form-label">Transfer To</label>
									<input type="text" class="form-control text-readonly" name="to_user_id" id="to_user_id" readonly>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_mode" class="form-label">Mode of Payment</label>
									<select name="transaction_mode" id="transaction_mode" class="form-control" required>
										<option value="" hidden>Select Mode</option>
										<optgroup label="Online Mode (NEFT/UPI/Online Bank Transfer)">
											<option value="1">Online Mode</option>
										</optgroup>
										<optgroup label="Offline Mode (Bank Branch Transfer/Money Order)">
											<option value="0">Offline Mode</option>
										</optgroup>
									</select>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_date" class="form-label">Transaction Date</label>
									<input type="date" class="form-control" name="transaction_date" id="transaction_date" required>
								</div>
								<div class="mb-3 col-md-6">
									<label for="transaction_id" class="form-label">Transaction ID</label>
									<input type="text" class="form-control" name="transaction_id" id="transaction_id" placeholder="Transaction ID" required>
								</div>
								<div class="mb-3 col-md-12">
									<label for="file" class="form-label">Receipt of Payment (Offline Slip/ Online Payment Screenshot) <span class="text-danger">*jpg/jpeg/png format; 100KB max size</span></label>
									<input type="file" name="file" id="file" class="form-control" required>
								</div>
							</div>
							<div style="float:right;">
								<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
								<button type="submit" class="btn btn-success" id="btn_submit" name="submit">Submit</button>
							</div>
						</form>
						</p>
					</div>
					<!-- <div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-success">Save changes</button>
					</div> -->
				</div>
			</div>
		</div>
	<!-- END ModalPay -->

	<?php
		// COUNTER
			$page_id = 1;
			counter($page_id);
		// COUNTER
	?>

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
                $('#ModalPay').on('show.bs.modal', function (event) {
                    var button = $(event.relatedTarget); // Button that triggered the modal
                    var package = button.data('package'); // Extract info from data-* attributes
                    var payment = button.data('payment'); // Extract info from data-* attributes
                    var beneficiary = button.data('beneficiary'); // Extract info from data-* attributes
                    // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                    // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
					// package = "Package "+package;
					// payment = "20% Amount of "+package+"= Rs."+payment;
                    var modal = $(this);
                    modal.find('#package_amount').val(package);
                    modal.find('#transaction_amount').val(payment);
                    modal.find('#to_user_id').val(beneficiary);
                });
            });
        // PASS DATA TO MODAL POPUP
    </script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Bar chart
			new Chart(document.getElementById("chartjs-dashboard-bar"), {
				type: "bar",
				data: {
					labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
					datasets: [{
						label: "Last year",
						backgroundColor: window.theme.primary,
						borderColor: window.theme.primary,
						hoverBackgroundColor: window.theme.primary,
						hoverBorderColor: window.theme.primary,
						data: [54, 67, 41, 55, 62, 45, 55, 73, 60, 76, 48, 79],
						barPercentage: .325,
						categoryPercentage: .5
					}, {
						label: "This year",
						backgroundColor: window.theme["primary-light"],
						borderColor: window.theme["primary-light"],
						hoverBackgroundColor: window.theme["primary-light"],
						hoverBorderColor: window.theme["primary-light"],
						data: [69, 66, 24, 48, 52, 51, 44, 53, 62, 79, 51, 68],
						barPercentage: .325,
						categoryPercentage: .5
					}]
				},
				options: {
					maintainAspectRatio: false,
					cornerRadius: 15,
					legend: {
						display: false
					},
					scales: {
						yAxes: [{
							gridLines: {
								display: false
							},
							ticks: {
								stepSize: 20
							},
							stacked: true,
						}],
						xAxes: [{
							gridLines: {
								color: "transparent"
							},
							stacked: true,
						}]
					}
				}
			});
		});
	</script>
	
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Pie chart
			new Chart(document.getElementById("chartjs-dashboard-pie"), {
				type: "pie",
				data: {
					labels: ["Direct", "Affiliate", "E-mail", "Other"],
					datasets: [{
						data: [2602, 1253, 541, 1465],
						backgroundColor: [
							window.theme.primary,
							window.theme.warning,
							window.theme.danger,
							"#E8EAED"
						],
						borderWidth: 5,
						borderColor: window.theme.white
					}]
				},
				options: {
					responsive: !window.MSInputMethodContext,
					maintainAspectRatio: false,
					cutoutPercentage: 70,
					legend: {
						display: false
					}
				}
			});
		});
	</script>

	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Column chart
			var options = {
				// theme: {
				// 	palette: 'palette7' // upto palette10
				// },
				// theme: {
				// 	mode: 'light', 
				// 	palette: 'palette5', 
				// 	monochrome: {
				// 		enabled: false,
				// 		color: '#255aee',
				// 		shadeTo: 'light',
				// 		shadeIntensity: 0.65
				// 	},
				// },
				chart: {
					height: 335,
					type: "bar",
					parentHeightOffset: 0,
					toolbar: { show: !1 },	// DOWNLOAD GRAPH OPTION
				},
				plotOptions: {
					bar: {
						horizontal: false,
						endingShape: "rounded",
						columnWidth: "70%",

						barHeight: "80%",
                        // columnWidth: "30%",
                        startingShape: "rounded",
                        endingShape: "rounded",
                        // borderRadius: 6,
                        // distributed: !0,

						dataLabels: {
							position: 'bottom',
							orientation: 'vertical',	// VERTICAL ROTATION
						}
					},
				},
				// colors: ['#d4526e', '#13d8aa', '#33b2df', '#f9a3a4', '#2b908f', '#A5978B', '#546E7A'],
				colors: ['#F9C80E', '#13D8AA', '#81D4FA', '#f9a3a4', '#2b908f', '#A5978B', '#546E7A'],
				dataLabels: {
					enabled: 1,
					// textAnchor: "start",
					orientation: 'vertical',
					style: {
						colors: ["blue"],
						fontSize: "9px",
						fontFamily: '"Nunito", sans-serif',
						fontWeight: 500,
					},
					offsetY: 10,
					dropShadow: {
						// enabled: true
					},
					formatter: function(e) {
						return "Rs." + e + ""
					},
				},
				grid: {
					show: !1,
					padding: { top: -20, bottom: -12, left: -10, right: 0 },
				},
				stroke: {
					show: true,
					width: 1,
					colors: ["transparent"]
				},
				series: [
					{
						name: "Investment<br><?php echo "(Total-Rs.$total_investment)";?>",
						data: [<?php echo "$data_graph_investment";?>]
					},
					{
						name: "Transfer<br><?php echo "(Total-Rs.$total_transfer)";?>",
						data: [<?php echo "$data_graph_transfer";?>]
					},
					{
						name: "Withdrawal<br><?php echo "(Total-Rs.$total_withdraw)";?>",
						data: [<?php echo "$data_graph_withdraw";?>]
					}
				],
				legend: {
					show: 1,
					offsetY: 10,
					floating: false,
					fontSize: '12px',
					fontFamily: 'Helvetica, Arial',
					fontWeight: 600,
					labels: {
						colors: undefined,
						useSeriesColors: 1
					},
					itemMargin: {
						horizontal: 15,
						vertical: 10
					},
					onItemClick: {
						toggleDataSeries: true
					},
					onItemHover: {
						highlightDataSeries: true
					},
				},
				xaxis: {
					categories: [<?php echo $day_graph_finance; ?>],
					axisBorder: { show: !1 },
					axisTicks: { show: !1 },
					labels: {
						style: { <?php echo $xaxis_style_graph_finance; ?> },
						rotate: 0,
						formatter: function (value) {
							return value;
						}
					},
				},
				// yaxis: {
				// 	title: {
				// 		text: ""
				// 	}
				// },
				yaxis: { 
					// tickAmount: 2,  // NO. OF SLABS
					labels: {
						show: 1,
						style: { <?php echo $yaxis_style_graph_finance; ?> },
						formatter: function(val) {
							if (val === undefined || val == "" || val == 0) {
								return "0";
							}
							return val.toFixed(0);  // TO MAKE ONLY INTEGERS ON AXIS
						},
						rotate: -45
					}
				},
				states: { hover: { filter: { type: "none" } } },
				noData: {
					text: "There is not enough data to display...",
					align: 'center',
					verticalAlign: 'middle',
					offsetX: 0,
					offsetY: 0,
					style: {
						color: "red",
						fontSize: '17px',
						fontFamily: "georgia",
					}
				},
				fill: {
					opacity: 1
				},
				title: {
					text: 'Finance Summary',
					align: 'center',
					floating: true
				},
				subtitle: {
					text: 'Transactions Wise Data',
					align: 'center',
				},
				tooltip: { 
					enabled: 1,

					// FOR COMBINED TOOLTIP
					shared: true,
					intersect: false,
					// FOR COMBINED TOOLTIP

					y: {
						formatter: function (val) {
							return "Rs." + val + ""
						}
					}
				},
			}
			var chart = new ApexCharts(
				document.querySelector("#apexcharts-column-finance"),
				options
			);
			chart.render();
		});
	</script>
	
	<script>
		document.addEventListener("DOMContentLoaded", function() {
			// Pie chart
			var options = {
				series: [<?php echo $graph_members; ?>],
				labels: ['Active', 'Inactive'],
				chart: {
					height: 350,
					type: "donut",
				},
				tooltip: {
					enabled: true,
					fillSeriesColor: true
				},
				colors: ["green", "red"],	// FOR TOOLTIP
				// markers: {
				// 	colors: ['green', 'red']
				// },
				// legend: {
				// 	show: true,
				// 	showForSingleSeries: false,
				// 	showForNullSeries: true,
				// 	showForZeroSeries: true,
				// 	position: 'bottom',
				// 	horizontalAlign: 'center', 
				// 	floating: false,
				// 	fontSize: '14px',
				// 	fontFamily: 'Helvetica, Arial',
				// 	fontWeight: 400,
				// 	formatter: undefined,
				// 	inverseOrder: false,
				// 	width: undefined,
				// 	height: undefined,
				// 	tooltipHoverFormatter: undefined,
				// 	customLegendItems: [],
				// 	offsetX: 0,
				// 	offsetY: 0,
				// 	labels: {
				// 		colors: undefined,
				// 		useSeriesColors: false
				// 	},
				// 	markers: {
				// 		width: 12,
				// 		height: 12,
				// 		strokeWidth: 0,
				// 		strokeColor: '#fff',
				// 		fillColors: undefined,
				// 		radius: 12,
				// 		customHTML: undefined,
				// 		onClick: undefined,
				// 		offsetX: 0,
				// 		offsetY: 0
				// 	},
				// 	itemMargin: {
				// 		horizontal: 5,
				// 		vertical: 0
				// 	},
				// 	onItemClick: {
				// 		toggleDataSeries: true
				// 	},
				// 	onItemHover: {
				// 		highlightDataSeries: true
				// 	},
				// },
				dataLabels: {
					enabled: true,
					// style: {
					// 	colors: ['#F44336', '#E91E63']
					// }
					// formatter: function (val) {
					// 	return val.toFixed(2) + "%"
					// },
				},
				fill: {
					type: 'gradient',
					colors: ['green', 'red'],
					gradient: {
						shade: 'dark',
						type: "horizontal",
						shadeIntensity: 0.5,
						gradientToColors: undefined, // optional, if not defined - uses the shades of same color in series
						inverseColors: true,
						opacityFrom: 1,
						opacityTo: 1,
						stops: [0, 50, 100],
						colorStops: []
					}
				},
				legend: {
					formatter: function(val, opts) {
						return val + " - " + opts.w.globals.series[opts.seriesIndex]
					}
				},
				title: {
					text: 'All Members Summary'
				},
				noData: {
					text: "There is not enough data to display...",
					align: 'center',
					verticalAlign: 'middle',
					offsetX: 0,
					offsetY: 0,
					style: {
						color: "red",
						fontSize: '17px',
						fontFamily: "georgia",
					}
				},
				plotOptions: {
					pie: {
						donut: {
							labels: {
								show: true,
								name: {
									show: true,
									fontSize: '22px',
									fontFamily: 'Helvetica, Arial, sans-serif',
									fontWeight: 600,
									color: undefined,
									offsetY: -10,
									formatter: function (val) {
										return val
									}
								},
								value: {
									show: true,
									fontSize: '16px',
									fontFamily: 'Helvetica, Arial, sans-serif',
									fontWeight: 400,
									color: undefined,
									offsetY: 16,
									formatter: function (val) {
										return val
									}
								},
								total: {
									show: true,
									showAlways: false,
									label: 'Total Members',
									fontSize: '22px',
									fontFamily: 'Helvetica, Arial, sans-serif',
									fontWeight: 600,
									color: '#373d3f',
									formatter: function (w) {
										return w.globals.seriesTotals.reduce((a, b) => {
										return a + b
										}, 0)
									}
								}
							}
						}
					}
				},
			}
			var chart = new ApexCharts(
				document.querySelector("#apexcharts-pie-users"),
				options
			);
			chart.render();
		});
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